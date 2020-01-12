<?php

$is_auth     = (bool) rand(0, 1);
$user_name   = 'Константин';
$user_avatar = 'img/user.jpg';
$categories  = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];

$lots        = [
    [
        "id"       => 0,
        "name"     => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "price"    => "10999",
        "url"      => "img/lot-1.jpg",
    ],
    [
        "id"       => 1,
        "name"     => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "price"    => "159999",
        "url"      => "img/lot-2.jpg",
    ],
    [
        "id"       => 2,
        "name"     => "Крепления Union Contact Pro 2015 года размер L/Xl",
        "category" => "Крепления",
        "price"    => "8000",
        "url"      => "img/lot-3.jpg",
    ],
    [
        "id"       => 3,
        "name"     => "Ботинки для сноубода DC Multiny Charocal",
        "category" => "Ботинки",
        "price"    => "10999",
        "url"      => "img/lot-4.jpg",
    ],
    [
        "id"       => 4,
        "name"     => "Куртка для сноубода DC Multiny Charocal",
        "category" => "Одежда",
        "price"    => "7500",
        "url"      => "img/lot-5.jpg",
    ],
    [
        "id"       => 5,
        "name"     => "Маска Oakley Canopy",
        "category" => "Разное",
        "price"    => "5400",
        "url"      => "img/lot-6.jpg",
    ],
];
