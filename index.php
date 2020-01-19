<?php
require_once "functions.php";
require_once "data.php";

$page_content   = include_template('index.php', ['lots' => $lots]);
$layout_content = include_template(
    'layout.php',
    [
        'content'     => $page_content,
        'title'       => 'Главная',
        'categories'  => $categories,
        'user_name'   => $user_name,
        'user_avatar' => $user_avatar,
    ]
);
print($layout_content);
