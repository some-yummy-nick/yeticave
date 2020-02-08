<?php
require_once "functions.php";
require_once "data.php";

$lot = null;
if (!$connect) {
    $error        = mysqli_connect_error();
    $title        = "Ошибка";
    $page_content = include_template("error.php", ["error" => $error]);
} else {
    $id = intval($_GET['lot_id']);
    $sql_lot = "SELECT l.name, l.id, l.image, l.price, c.name 'category' FROM lots l JOIN categories c ON l.category_id = c.id "
        . "WHERE l.id = " . $id;
    $result_select_lot = mysqli_query($connect, $sql_lot);
    if (mysqli_num_rows($result_select_lot)){
        $lot = mysqli_fetch_array($result_select_lot, MYSQLI_ASSOC);
        $page_content   = include_template('lot.php', ['lot' => $lot, 'is_auth' => isset($_SESSION['user'])]);
    }else{
        $error        = mysqli_error($connect);
        $title        = "Ошибка";
        $page_content = include_template("error.php", ["error" => $error, "title"=>$title]);
    }
}

$historyArr = [];
$name       = "history";

if (isset($_COOKIE[$name])) {
    $historyArr = unserialize($_COOKIE[$name]);
}
array_push($historyArr, $id);
$historyArr = array_unique($historyArr);

setcookie($name, serialize($historyArr), strtotime("+30 days"), "/");

$layout_content = include_template(
    'layout.php',
    [
        'content'     => $page_content,
        'title'       => $lot["name"],
        'categories'  => $categories,
        'is_auth'     => isset($_SESSION['user']),
    ]
);
print($layout_content);



