<?php
require_once "functions.php";
require_once "data.php";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $required_fields = ["lot-name", "lot-category", "lot-message", "lot-rate", "lot-step", "lot-date"];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "Поле не заполнено";
        } else {
            if ($field = "lot-rate") {
                if (!is_numeric($_POST[$field])) {
                    $errors[$field] = "Начальная цена должна быть числом";
                }
            }
            if ($field = "lot-step") {
                if (!is_numeric($_POST[$field])) {
                    $errors[$field] = "Шаг ставки должен быть числом";
                }
            }
            if ($field = "lot-category") {
                if (($_POST[$field]) == "Выберите категорию") {
                    $errors[$field] = "Выберите категорию";
                }
            }
            if ($field = "lot-date") {
                $date = explode("-", $_POST[$field]);
                if (!empty($date[0]) && !checkdate($date[1], $date[2], $date[0])) {
                    $errors[$field] = "Введите верный формат даты";
                }
            }
        }
    }

    if (!empty($_FILES["lot-photo"]["name"])) {
        $tmp_name  = $_FILES["lot-photo"]["tmp_name"];
        $path      = $_FILES["lot-photo"]["name"];
        $photo     = $_POST;
        $fInfo     = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($fInfo, $tmp_name);
        $file_size = $_FILES["lot-photo"]["size"];
        if ($file_type !== "image/jpeg") {
            $errors["lot-photo"] = "Загрузите картинку в формате jpg или png";
        } else if ($file_size > 200000) {
            $errors["lot-photo"] = "Максимальный размер файла: 200Кб";
        } else {
            move_uploaded_file($tmp_name, "uploads/" . $path);
            $_POST["lot-photo"] = $path;
        }
    } else {
        $errors["lot-photo"] = "Вы не загрузили файл";
    }
}
$page_content = include_template("add-lot.php", ["errors" => $errors, "categories" => $categories]);

$layout_content = include_template(
    "layout.php",
    [
        "content"     => $page_content,
        "title"       => "Добавить лот",
        "categories"  => $categories,
        "is_auth"     => $is_auth,
        "user_name"   => $user_name,
        "user_avatar" => $user_avatar,
    ]
);
print($layout_content);
