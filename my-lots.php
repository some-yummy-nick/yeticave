<?php
require_once "functions.php";
require_once "data.php";
$lots = null;
$user_id = $_SESSION["user"]["id"];
$time_to_close = 3600;
if (!$connect) {
    $error        = mysqli_connect_error();
    $title        = "Ошибка";
    $page_content = include_template("error.php", ["error" => $error]);
} else {
//    $sql_lots =  "SELECT b.date AS time, b.price, l.id, "
//                                ."l.name, l.date_end, l.image, l.winner_id, c.name AS category, u.contacts "
//                                ."FROM bets b JOIN lots l ON b.lot_id = l.id "
//                                ."JOIN categories c ON l.category_id = c.id "
//                                ."JOIN users u ON b.user_id = u.id "
//                                ."WHERE b.user_id = " . $_SESSION["user"]["id"] . " ORDER BY b.date DESC";
    $sql_lots           = "SELECT bets.date, bets.price, bets.lot_id, lots.name, lots.category_id, users.contacts,
 categories.name AS category,lots.date_end, lots.winner_id, lots.image FROM bets
        JOIN lots  ON bets.lot_id = lots.id
        JOIN categories  ON lots.category_id = categories.id
        JOIN users ON users.id = bets.user_id
        WHERE bets.id IN (SELECT MAX(id) FROM bets
        WHERE user_id = $user_id
        GROUP BY lot_id)
        ORDER BY bets.date desc";
    $result_select_lots = mysqli_query($connect, $sql_lots);
    if (mysqli_num_rows($result_select_lots)) {
        $lots = mysqli_fetch_all($result_select_lots, MYSQLI_ASSOC);
    }
}

$page_content = include_template(
    "my-lots.php",
    [
        "lots"    => $lots,
        'time_to_close' => $time_to_close,
        'user_id' => $user_id,
    ]
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
