<?php
require_once "functions.php";
require_once "data.php";
$errors = [];
$form   = null;
$lot    = null;
$bets   = null;

if (!$connect) {
    $error        = mysqli_connect_error();
    $title        = "Ошибка";
    $page_content = include_template("error.php", ["error" => $error]);
} else {
    $id                 = intval($_GET["lot_id"]);
    $sql_lot            = "SELECT l.name, l.id, l.image, l.date_end, l.price, l.step, c.name 'category' FROM lots l JOIN categories c ON l.category_id = c.id "
        . "WHERE l.id = " . $id;
    $result_select_lot  = mysqli_query($connect, $sql_lot);
    $sql_bet            = "SELECT b.price FROM bets b JOIN lots l ON b.lot_id = l.id " . "WHERE l.id = " . $id . " ORDER BY b.date DESC";
    $result_select_bet  = mysqli_query($connect, $sql_bet);
    $sql_bets           = "SELECT u.name, b.price, b.date FROM bets b JOIN users u ON b.user_id = u.id" . " WHERE b.lot_id = " . $id . " ORDER BY b.date DESC" . " LIMIT 10";
    $result_select_bets = mysqli_query($connect, $sql_bets);

    if (mysqli_num_rows($result_select_lot)) {
        $lot  = mysqli_fetch_array($result_select_lot, MYSQLI_ASSOC);
        $bets = mysqli_fetch_all($result_select_bets, MYSQLI_ASSOC);
        if (mysqli_num_rows($result_select_bet)) {
            $bet          = mysqli_fetch_array($result_select_bet, MYSQLI_ASSOC);
            $lot["price"] = $bet["price"];
        }
        $page_content = include_template(
            "lot.php",
            ["lot" => $lot, "bets" => $bets, "errors" => $errors, "is_auth" => isset($_SESSION["user"])]
        );
    } else {
        $error        = mysqli_error($connect);
        $title        = "Ошибка";
        $page_content = include_template("error.php", ["error" => $error, "title" => $title]);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $form = $_POST;
        if (empty($form["cost"])) {
            $errors["cost"] = "Поле не заполнено";
        } else if (!isInteger($form["cost"])) {
            $errors["cost"] = "Ставка должна быть целым числом";
        } else if ($form["cost"] <= 0) {
            $errors["cost"] = "Ставка должна быть больше 0";
        } else if ($form["cost"] < ($lot["price"] + $lot["step"])) {
            $errors["cost"] = "Ставка должна быть больше, чем текущая цена лота + минимальный шаг";
        }
        if (count($errors)) {
            $page_content = include_template(
                "lot.php",
                ["lot" => $lot, "bets" => $bets, "errors" => $errors, "is_auth" => isset($_SESSION["user"])]
            );
        } else {
            $sql_bet = "INSERT INTO bets (`date`, `price`, `lot_id`, `user_id`) VALUES (NOW(),?, ?, ?)";

            $stmt_bet = db_get_prepare_stmt(
                $connect,
                $sql_bet,
                [$form["cost"], $lot["id"], $_SESSION["user"]["id"]]
            );
            $res_bet  = mysqli_stmt_execute($stmt_bet);

            if ($res_bet) {
                $bet_id = mysqli_insert_id($connect);
            }

            $user_id         = intvaL($_SESSION["user"]["id"]);
            $lots_id         = intval($lot["id"]);
            $bets_id         = intval($bet_id);
            $sql_user_update = "UPDATE users SET `lots_id`= $lots_id, `bets_id`= $bets_id WHERE users.id=" . $_SESSION["user"]["id"];
            $result          = mysqli_query($connect, $sql_user_update);

            header("Location: lot.php?lot_id=" . $lot["id"]);
        }
    }
}


$historyArr = [];
$name       = "history";

if (isset($_COOKIE[$name])) {
    $historyArr = unserialize($_COOKIE[$name]);
}
array_push($historyArr, $id);
$historyArr = array_unique($historyArr);

setcookie($name, serialize($historyArr), strtotime("+30 days"), "/");

$layout_content = include_template(
    "layout.php",
    [
        "content"    => $page_content,
        "title"      => $app_name . " | " . $lot["name"],
        "categories" => $categories,
        "is_auth"    => isset($_SESSION["user"]),
    ]
);
print($layout_content);



