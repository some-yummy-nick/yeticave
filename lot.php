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
$page_content   = include_template('lot.php', ['lot' => $lot]);
$layout_content = include_template(
    'layout.php',
    [
        'content'     => $page_content,
        'title'       => $lot["name"],
        'categories'  => $categories,
        'is_auth'     => $is_auth,
        'user_name'   => $user_name,
        'user_avatar' => $user_avatar,
    ]
);
print($layout_content);



