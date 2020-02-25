<?php
define ('CACHE_DIR', "/Open/OSPanel/domains/yeticave/cash");

function debug($arr)
{
    echo "<pre>" . print_r($arr, true), "</pre>";
}

/**
 * Функция принимает два аргумента: имя файла шаблона и ассоциативный массив с данными для этого шаблона.
 * Функция возвращает строку — итоговый HTML-код с подставленными данными или описание ошибки
 *
 * @param $name string
 * @param $data array
 *
 * @return false|string
 */
function include_template($name, $data = null)
{
    $name = "templates/" . $name;
    if (!is_readable($name)) {
        return "Шаблон с именем " . $name . " не существует или недоступен для чтения";
    }
    ob_start();
    if ($data) extract($data);

    require $name;

    return ob_get_clean();
}

function searchUserByEmail($email, $users)
{
    $result = null;
    foreach ($users as $user) {
        if ($user["email"] == $email) {
            $result = $user;
            break;
        }
    }

    return $result;
}

/**
 * Проверяет на целочисленное значение
 *
 * @param $input string
 *
 * @return false|true
 */
function isInteger($input)
{
    return (ctype_digit(strval($input)));
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param       $link mysqli Ресурс соединения
 * @param       $sql  string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types     = "";
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = "i";
            } else if (is_string($value)) {
                $type = "s";
            } else if (is_double($value)) {
                $type = "d";
            }

            if ($type) {
                $types       .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = "mysqli_stmt_bind_param";
        $func(...$values);
    }

    return $stmt;
}

function cache_get_key($sql, $params, $tag) {
    $str = md5(implode($params) . $tag);
    $key = $str . md5($sql);

    return $key;
}

function cache_save_data($filepath, $data) {
    $res = false;

    if (!file_exists($filepath)) {
        $res = file_put_contents($filepath, json_encode($data));
    }

    return $res;
}

function cache_del_data($data, $tag) {
    $cache_part = md5(implode($data) . $tag);
    $files = scandir(CACHE_DIR);

    foreach ($files as $file) {
        if (substr($file, 0, 32) == $cache_part) {
            unlink(CACHE_DIR . DIRECTORY_SEPARATOR . $file);
        }
    }
}

function is_cache_expired($filename, $ttl) {
    $res = false;

    $mod_time = filemtime($filename);

    if ($mod_time + $ttl < time()) {
        unlink($filename);
        $res = true;
    }

    return $res;
}

function cache_get_data($link, $sql, $params, $tag, $ttl = 1) {
    $filename = cache_get_key($sql, $params, $tag) . '.json';
    $filepath = CACHE_DIR . DIRECTORY_SEPARATOR . $filename;

    if (file_exists($filepath) && !is_cache_expired($filepath, $ttl)) {
        $content = file_get_contents($filepath);
        $res_data = json_decode($content, true);
    }
    else {
        $stmt = db_get_prepare_stmt($link, $sql, $params);
        mysqli_stmt_execute($stmt);

        $res  = mysqli_stmt_get_result($stmt);

        $res_data = mysqli_fetch_all($res, MYSQLI_ASSOC);
        cache_save_data($filepath, $res_data);
    }

    return $res_data;
}

function showDate($date)
{ // Определяем количество и тип единицы измерения
    $time = time() - strtotime($date);
    $date = strtotime($date);

    if ($time < 60) {
        return "меньше минуты назад";
    } elseif ($time < 3600) {
        return dimension((int) ($time / 60), "i");
    } elseif ($time < 86400) {
        return dimension((int) ($time / 3600), "G");
    } else if ($time < 172800) {
        return "Вчера в " . date("H:i", $date);
    } elseif ($time < 2592000) {
        return date("d.m.y", $date) . " в " . date("H:i", $date);
    }

    return false;
}
/**
 * Форматирует число в соответствии с заданием
 *
 * @param $price число для форматирования
 * @return string $price результат — отформатированное число
 */
function get_formatted_amount($price): string
{
    $price = ceil($price);

    if ($price >= 1000) {
        $price = number_format($price, 0, '', ' ');
    }

    return $price;
}
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}
/**
 * Показывает сколько времени назад была сделана ставка
 * @param $time время в формате unix
 * @return string результат
 */
function show_time($time)
{
    $time_ago = time() - $time;
    if ($time_ago < 3600) {
        return intval($time_ago / 60).' '.get_noun_plural_form($time_ago / 60, 'минута', 'минуты',
                                                               'минут').' назад';
    } elseif ($time_ago < 86400) {
        return intval($time_ago / 3600).' '.get_noun_plural_form($time_ago / 3600, 'час', 'часа',
                                                                 'часов').' назад';
    }
    return date('d.m.y', $time).' в '.date('H:i', $time);
}

/**
 * Время до истечения лота
 *
 * @param $date_end дата окончания
 */
function showTimeEnd($date)
{
    $seconds = strtotime($date) - time();

    $times        = seconds2times($seconds);
    $times_values = ['', ':', ':', 'д.', 'лет'];

    for ($i = count($times) - 1; $i >= 0; $i--) {
        echo $times[$i] . '' . $times_values[$i] . '';
    }

}

/**
 * Преобразование секунд в секунды/минуты/часы/дни/года
 *
 * @param int $seconds - секунды для преобразования
 *
 * @return array $times:
 *        $times[0] - секунды
 *        $times[1] - минуты
 *        $times[2] - часы
 *        $times[3] - дни
 *        $times[4] - года
 *
 */
function seconds2times($seconds)
{
    $times = [];

    // считать нули в значениях
    $count_zero = false;

    // количество секунд в году не учитывает високосный год
    // поэтому функция считает что в году 365 дней
    // секунд в минуте|часе|сутках|году
    $periods = [60, 3600, 86400, 31536000];

    for ($i = 3; $i >= 0; $i--) {
        $period = floor($seconds / $periods[$i]);
        if (($period > 0) || ($period == 0 && $count_zero)) {
            $times[$i + 1] = $period;
            $seconds       -= $period * $periods[$i];

            $count_zero = true;
        }
    }

    $times[0] = $seconds;

    return $times;
}

function dimension($time, $type)
{ // Определяем склонение единицы измерения
    $dimension = [
        "n" => ["месяцев", "месяц", "месяца", "месяц"],
        "j" => ["дней", "день", "дня"],
        "G" => ["часов", "час", "часа"],
        "i" => ["минут", "минуту", "минуты"],
        "Y" => ["лет", "год", "года"],
    ];
    if ($time >= 5 && $time <= 20)
        $n = 0;
    else if ($time == 1 || $time % 10 == 1)
        $n = 1;
    else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
        $n = 2;
    else
        $n = 0;

    return $time . " " . $dimension[$type][$n] . " назад";
}

function getWord($number, $suffix) {
    $keys = array(2, 0, 1, 1, 1, 2);
    $mod = $number % 100;
    $suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
    return $suffix[$suffix_key];
}

