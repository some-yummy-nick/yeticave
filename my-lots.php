<?php
require_once "functions.php";
require_once "data.php";
$lots = null;
if (!$connect) {
    $error        = mysqli_connect_error();
    $title        = "Ошибка";
    $page_content = include_template("error.php", ["error" => $error]);
} else {
    $sql_lots =  "SELECT b.date AS time, b.price, l.id, "
                                ."l.name, l.date_end, l.image, l.winner_id, c.name AS category, u.contacts "
                                ."FROM bets b JOIN lots l ON b.lot_id = l.id "
                                ."JOIN categories c ON l.category_id = c.id "
                                ."JOIN users u ON b.user_id = u.id "
                                ."WHERE b.user_id = " . $_SESSION["user"]["id"] . " ORDER BY b.date DESC";

    $result_select_lots = mysqli_query($connect, $sql_lots);
    if (mysqli_num_rows($result_select_lots)) {
        $lots = mysqli_fetch_all($result_select_lots, MYSQLI_ASSOC);
    }
}

$page_content = include_template(
    "my-lots.php",
    ["lots" => $lots]
);

$layout_content = include_template(
    "layout.php",
    [
        "content"    => $page_content,
        "title"      => $app_name . " | " . "Мои лоты",
        "categories" => $categories,
        "is_auth"    => isset($_SESSION["user"]),
    ]
);
print($layout_content);
