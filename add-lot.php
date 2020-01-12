<?php
require_once "functions.php";
require_once "data.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $img = $_POST;
    if (isset($_FILES['photo']['name'])) {
        $name = $_FILES['photo']['name'];
        $res  = move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $name);
    }
    if (isset($name)) {
        $img['name'] = $name;
    }
    $page_content = include_template('image.php', ["img" => $img]);
} else {
    $page_content = include_template('add-lot.php');
}

$layout_content = include_template(
    'layout.php',
    [
        'content'     => $page_content,
        'title'       => 'Добавить лот',
        'categories'  => $categories,
        'is_auth'     => $is_auth,
        'user_name'   => $user_name,
        'user_avatar' => $user_avatar,
    ]
);
print($layout_content);
