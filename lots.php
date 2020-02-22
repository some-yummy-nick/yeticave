<?php
require_once "functions.php";
require_once "data.php";

$lots     = null;
$category = null;
$pagination = null;

if (!$connect) {
    $error        = mysqli_connect_error();
    $title        = "Ошибка";
    $page_content = include_template("error.php", ["error" => $error]);
} else {
    $category_id                     = intval($_GET["category_id"]);
    $cur_page               = isset($_GET['page']) ? intval($_GET["page"]) : 1;
    $page_items             = 1;
    $offset                 = ($cur_page - 1) * $page_items;
    $sql_lots               = "SELECT l.id, l.name, l.date_start, IFNULL(MAX(b.price), l.price) AS price, l.image, l.date_end, c.name AS category, COUNT(b.price) AS count_bet"
        . " FROM lots l"
        . " JOIN categories c ON l.category_id = c.id"
        . " LEFT JOIN bets b ON b.lot_id = l.id"
        . " WHERE c.id = $category_id && l.date_end >= NOW()"
        . " GROUP BY l.id"
        . " ORDER BY l.date_start DESC"
        . " LIMIT $page_items OFFSET $offset";
    $sql_category           = "SELECT c.name FROM categories c WHERE c.id = " . $category_id;

//    $result_select_lots     = mysqli_query($connect, $sql_lots);
    $result_select_category = mysqli_query($connect, $sql_category);

//    if (mysqli_num_rows($result_select_lots)) {
//        $lots = mysqli_fetch_all($result_select_lots, MYSQLI_ASSOC);
//    }
    $lots = cache_get_data($connect, $sql_lots,[],"lots");

    if (mysqli_num_rows($result_select_category)) {
        $category = mysqli_fetch_array($result_select_category, MYSQLI_ASSOC);
    }
    $link = "/lots.php?category_id=$category_id";

    $sql_count = "SELECT COUNT(*) as cnt FROM lots WHERE category_id = ?";
    $stmt_count = db_get_prepare_stmt($connect, $sql_count, [$category_id]);
    mysqli_stmt_execute($stmt_count);
    $result_count = mysqli_stmt_get_result($stmt_count);
    $items_count = mysqli_fetch_assoc($result_count)['cnt'];
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    $pagination = include_template('pagination.php', [
        'pages' => $pages,
        'category_id' => $category_id,
        'cur_page' => $cur_page,
        'pages_count' => $pages_count,
        'link' => $link,
    ]);
}

$page_content = include_template(
    "lots.php",
    [
        "lots"     => $lots,
        "category" => $category,
        'pagination' => $pagination,
    ]
);

$layout_content = include_template(
    "layout.php",
    [
        "content"    => $page_content,
        "title"      => $app_name . " | " . "Лоты",
        "categories" => $categories,
        "is_auth"    => isset($_SESSION["user"]),
    ]
);
print($layout_content);
