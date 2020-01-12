<?php
require_once "functions.php";
$is_auth = (bool) rand(0, 1);
$user_name   = 'Константин';
$user_avatar = 'img/user.jpg';
$categories  = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$lots        = [
    [
        "name"     => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "price"    => "10999",
        "url"      => "img/lot-1.jpg",
    ],
    [
        "name"     => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "price"    => "159999",
        "url"      => "img/lot-2.jpg",
    ],
    [
        "name"     => "Крепления Union Contact Pro 2015 года размер L/Xl",
        "category" => "Крепления",
        "price"    => "8000",
        "url"      => "img/lot-3.jpg",
    ],
    [
        "name"     => "Ботинки для сноубода DC Multiny Charocal",
        "category" => "Ботинки",
        "price"    => "10999",
        "url"      => "img/lot-4.jpg",
    ],
    [
        "name"     => "Куртка для сноубода DC Multiny Charocal",
        "category" => "Одежда",
        "price"    => "7500",
        "url"      => "img/lot-5.jpg",
    ],
    [
        "name"     => "Маска Oakley Canopy",
        "category" => "Разное",
        "price"    => "5400",
        "url"      => "img/lot-6.jpg",
    ],
];

$page_content = include_template('index.php', ['lots' => $lots]);
$layout_content = include_template('layout.php', ['content' => $page_content,
                                                  'title' => 'Главная',
                                                  'categories' => $categories,
                                                  'is_auth' => $is_auth,
                                                  'user_name' => $user_name,
                                                  'user_avatar'=>$user_avatar]);
print($layout_content);
