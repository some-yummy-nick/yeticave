<?php
require_once "functions.php";
require_once "data.php";

$lots     = null;
$category = null;

if (!$connect) {
    $error        = mysqli_connect_error();
    $title        = "Ошибка";
    $page_content = include_template("error.php", ["error" => $error]);
} else {
    $id                     = intval($_GET["category_id"]);
    $sql_lots               = "SELECT l.id, l.name, l.date_start, IFNULL(MAX(b.price), l.price) AS price, l.image, l.date_end, c.name AS category, COUNT(b.price) AS count_bet"
        . " FROM lots l"
        . " JOIN categories c ON l.category_id = c.id"
        . " LEFT JOIN bets b ON b.lot_id = l.id"
        . " WHERE c.id = $id && l.date_end >= NOW()"
        . " GROUP BY l.id"
        . " ORDER BY l.date_start DESC";
    $sql_category           = "SELECT c.name FROM categories c WHERE c.id = " . $id;
    $result_select_lots     = mysqli_query($connect, $sql_lots);
    $result_select_category = mysqli_query($connect, $sql_category);

    if (mysqli_num_rows($result_select_lots)) {
        $lots = mysqli_fetch_all($result_select_lots, MYSQLI_ASSOC);
    }

    if (mysqli_num_rows($result_select_category)) {
        $category = mysqli_fetch_array($result_select_category, MYSQLI_ASSOC);
    }
}
$page_content = include_template(
    "lots.php",
    [
        "lots"     => $lots,
        "category" => $category,
    ]
);

$layout_content = include_template(
    "layout.php",
    [
        "content"    => $page_content,
        "title"      => $app_name . " | " . "Лоты",
        "categories" => $categories,
        "is_auth"    => isset($_SESSION["user"]),
    ]
);
print($layout_content);
