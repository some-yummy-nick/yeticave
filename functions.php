<?php
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

function setNumberToFormat($price)
{
    return number_format($price, 0, "", " ");
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
