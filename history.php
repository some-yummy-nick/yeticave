<?php
require_once "functions.php";
require_once "data.php";
$historyLots=array();
$historyIds = unserialize($_COOKIE['history'] );
foreach ($historyIds as $historyId) {

    foreach ($lots as $lot){
        if($lot["id"]==$historyId){
            array_push($historyLots, $lot);
        }
    }
}

$page_content   = include_template('history.php', ['lots' => $historyLots]);
$layout_content = include_template(
    'layout.php',
    [
        'content'     => $page_content,
        'title'       => 'История',
        'categories'  => $categories,
        'is_auth'     => $is_auth,
        'user_name'   => $user_name,
        'user_avatar' => $user_avatar,
    ]
);
print($layout_content);
