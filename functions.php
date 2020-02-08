<?php
function debug($arr)
{
    echo '<pre>' . print_r($arr, true), '</pre>';
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
    $name = 'templates/' . $name;
    if (!is_readable($name)) {
        return 'Шаблон с именем ' . $name . ' не существует или недоступен для чтения';
    }
    ob_start();
    if($data) extract($data);

    require $name;

    return ob_get_clean();
}

function setNumberToFormat($price)
{
    return number_format($price, 0, '', ' ');
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
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}
