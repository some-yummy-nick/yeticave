<?php
require_once "functions.php";
require_once "data.php";
$historyLots = [];
if (isset($_COOKIE["history"])) {
    $historyIds = unserialize($_COOKIE["history"]);
    foreach ($historyIds as $historyId) {
        foreach ($lots as $lot) {
            if ($lot["id"] == $historyId) {
                array_push($historyLots, $lot);
            }
        }
    }
}

$page_content = include_template("history.php", ["lots" => $historyLots]);
$layout_content = include_template(
    "layout.php",
    [
        "content" => $page_content,
        "title" => $app_name . " | История",
        "categories" => $categories,
        "is_auth" => isset($_SESSION["user"]),
    ]
);
print($layout_content);
