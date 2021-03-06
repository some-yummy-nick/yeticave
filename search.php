<?php
require_once "functions.php";
require_once "data.php";

$lots = null;
$error = [];
$form = null;
$pagination = null;
$items_count = null;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $form = $_GET;
    $search = $form['q'] ? trim($form['q']) : "";
    $cur_page = isset($_GET['page']) ? intval($_GET["page"]) : 1;
    $offset = ($cur_page - 1) * $page_items;

    if (empty($form["q"])) {
        $title = "Поле поиска не должно быть пустым";
        $page_content = include_template("error.php", ["error" => $error, "title" => $title]);
    } else {
        $dbHelper->executeQuery("CREATE FULLTEXT INDEX lots_ft_search ON lots(name, description)");
        $sql_lots_search = "SELECT l.id, l.name, l.date_start, IFNULL(MAX(b.price), l.price) AS price, l.image, l.date_end, c.name AS category, COUNT(b.price) AS count_bet
        FROM lots l
        JOIN categories c ON l.category_id = c.id
        LEFT JOIN bets b ON b.lot_id = l.id
        WHERE MATCH(l.name, l.description) AGAINST(?)
        GROUP BY l.id LIMIT $page_items OFFSET $offset";
        $dbHelper->executeQuery($sql_lots_search, [$search]);
        if (!$dbHelper->getLastError()) {
            $lots = $dbHelper->getResultAsArray();
        }
        $link = "/search.php?q=$search";
        $sql_count = "SELECT COUNT(*) as cnt FROM lots WHERE MATCH(name, description) AGAINST(?)";
        $dbHelper->executeQuery($sql_count, [$search]);
        if (!$dbHelper->getLastError()) {
            $items_count = $dbHelper->getResultAsArray()[0]['cnt'];
        }
        $pages_count = ceil($items_count / $page_items);
        $pages = range(1, $pages_count);

        $pagination = include_template(
            'pagination.php',
            [
                'pages' => $pages,
                'cur_page' => $cur_page,
                'pages_count' => $pages_count,
                'link' => $link,
            ]
        );

        $page_content = include_template(
            "search.php",
            [
                "lots" => $lots,
                "search" => $search,
                'pagination' => $pagination,
            ]
        );
    }

}


$layout_content = include_template(
    "layout.php",
    [
        "content" => $page_content,
        "title" => $app_name . " | Поиск",
        "categories" => $categories,
        "is_auth" => isset($_SESSION["user"]),
    ]
);
print($layout_content);
