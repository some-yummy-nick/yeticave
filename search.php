<?php
require_once "functions.php";
require_once "data.php";

$lots   = null;
$error = [];
$form   = null;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $form            = $_GET;
    $search = $form['q'] ? trim($form['q']) : "";

    if (empty($form["q"])) {
        $title = "Поле поиска не должно быть пустым";
        $page_content = include_template("error.php", ["error" => $error, "title"=>$title]);
    }
    else{
        mysqli_query($connect, 'CREATE FULLTEXT INDEX lots_ft_search ON lots(name, description)');
        $sql_lots = "SELECT l.id, l.name, l.date_start, IFNULL(MAX(b.price), l.price) AS price, l.image, l.date_end, c.name AS category, COUNT(b.price) AS count_bet
        FROM lots l
        JOIN categories c ON l.category_id = c.id
        LEFT JOIN bets b ON b.lot_id = l.id
        WHERE MATCH(l.name, l.description) AGAINST(?)
        GROUP BY l.id";
        $stmt_lots = db_get_prepare_stmt($connect, $sql_lots, [$search]);
        mysqli_stmt_execute($stmt_lots);
        $result_lots = mysqli_stmt_get_result($stmt_lots);
        $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

        $page_content = include_template(
            "search.php",
            [
                "lots" => $lots,
                "search"=>$search
            ]
        );
    }
}



$layout_content = include_template(
    "layout.php",
    [
        "content"    => $page_content,
        "title"      => $app_name . " | Поиск",
        "categories" => $categories,
        "is_auth"    => isset($_SESSION["user"]),
    ]
);
print($layout_content);
