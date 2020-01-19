<?php
require_once "functions.php";
require_once "data.php";

$lot = null;

if (isset($_GET['lot_id'])) {
    $lot_id = $_GET['lot_id'];

    foreach ($lots as $item) {
        if ($item['id'] == $lot_id) {
            $lot = $item;
            break;
        }
    }
}

if (!$lot) {
    http_response_code(404);
}
$historyArr = [];
$name       = "history";
if (isset($_COOKIE[$name])) {
    $historyArr = unserialize($_COOKIE[$name]);
}
array_push($historyArr, $lot_id);
$historyArr = array_unique($historyArr);

setcookie($name, serialize($historyArr), strtotime("+30 days"), "/");

$page_content   = include_template('lot.php', ['lot' => $lot, 'is_auth' => isset($_SESSION['user'])]);
$layout_content = include_template(
    'layout.php',
    [
        'content'     => $page_content,
        'title'       => $lot["name"],
        'categories'  => $categories,
        'is_auth'     => isset($_SESSION['user']),
        'user_name'   => $user_name,
        'user_avatar' => $user_avatar,
    ]
);
print($layout_content);



