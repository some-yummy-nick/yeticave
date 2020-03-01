<?php
require_once "functions.php";
require_once "data.php";
require_once "getwinner.php";

if ($categories && $lots) {
    $title = $app_name . " | Главная";
    $page_content = include_template("index.php", ["lots" => $lots, "categories" => $categories]);
} else {
    $error = mysqli_error($connect);
    $title = "Ошибка данных";
    $page_content = include_template("error.php", ["error" => $error, "title" => $title]);
}

$layout_content = include_template(
    "layout.php",
    [
        "content" => $page_content,
        "title" => $title,
        "categories" => $categories,
    ]
);
print($layout_content);
