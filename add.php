<?php
require_once "functions.php";
require_once "data.php";
$errors = [];
$form   = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form            = $_POST;
    $required_fields = ["lot-name", "lot-category", "lot-message", "lot-rate", "lot-step", "lot-date-end"];

    foreach ($required_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Поле не заполнено";
        } else {
            if ($field = "lot-rate") {
                if (!is_numeric($form[$field])) {
                    $errors[$field] = "Начальная цена должна быть числом";
                } else if ($form[$field] <= 0) {
                    $errors[$field] = "Начальная цена должна быть больше 0";
                }
            }
            if ($field = "lot-step") {
                if (!isInteger($form[$field])) {
                    $errors[$field] = "Шаг ставки должен быть целым числом";
                } else if ($form[$field] <= 0) {
                    $errors[$field] = "Шаг ставки должен быть больше 0";
                }
            }
            if ($field = "lot-category") {
                if (($form[$field]) == "Выберите категорию") {
                    $errors[$field] = "Выберите категорию";
                }
            }
            if ($field = "lot-date-end") {
                $date = explode("-", $form[$field]);
                if (!empty($date[0]) && !checkdate($date[1], $date[2], $date[0])) {
                    $errors[$field] = "Введите верный формат даты";
                }

                $date_end = new DateTime($form[$field]);
                $tomorrow = new DateTime("tomorrow");
                if ($date_end < $tomorrow) {
                    $errors[$field] = "Дата завершения должна быть больше текущей даты хотя бы на один день";
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
            $form["lot-photo"] = $path;
        }
    } else {
        $errors["lot-photo"] = "Вы не загрузили файл";
    }
    if (!count($errors)) {
        $sql_add   = "INSERT INTO lots (
 `date_start`,
 `date_end`,
  category_id,
  `name`, 
  `user_id`,
  step, 
  description,
  image,
  price) VALUES (NOW(),?, ?, ?, ?, ?, ?, ? , ?)";
        $lot_image = "uploads/" . $form["lot-photo"];
        $stmt_add  = db_get_prepare_stmt(
            $connect,
            $sql_add,
            [
                $form["lot-date-end"],
                $form["lot-category"],
                $form["lot-name"],
                $_SESSION["user"]["id"],
                $form["lot-step"],
                $form["lot-message"],
                $lot_image,
                $form["lot-rate"],
            ]
        );
        $res_add   = mysqli_stmt_execute($stmt_add);

        if ($res_add) {
            $lot_id = mysqli_insert_id($connect);

            header("Location: lot.php?lot_id=" . $lot_id);
        }
    }
}
if (!isset($_SESSION["user"])) {
    http_response_code(403);
    header("Location: /login.php");
    exit();
}
$page_content = include_template("add.php", ["form" => $form, "errors" => $errors, "categories" => $categories]);

$layout_content = include_template(
    "layout.php",
    [
        "content"    => $page_content,
        "title"      => $app_name . " | Добавить лот",
        "categories" => $categories,
        "is_auth"    => isset($_SESSION["user"]),
    ]
);
print($layout_content);
