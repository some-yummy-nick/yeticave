<?php
session_start();
require_once 'vendor/autoload.php';
require_once "functions.php";
require_once "Database.php";

$app_name   = "Yeticave";
$categories = [];
$lots       = [];
$users      = [];
$time_to_close = 28800;
$page_items = 2;
$host     = "localhost";
$user     = "root";
$password = "";
$database = "yeticave";
$dbHelper = new Database($host,$user,$password, $database);
$connect  = mysqli_connect($host, $user, $password, $database);

//$sql = "INSERT INTO users SET email = 'dev@gmail.com',password = 'secret'";
//
//$result = mysqli_query($connect, $sql);
//
//if (!$result) {
//    $error = mysqli_error($connect);
//    print("Ошибка MySQL: " . $error . "</br>");
//} else {
//    $last_id = mysqli_insert_id($connect);
//    print $last_id . "</br>";
//}
//if ($connect) {

// Получить только первое значение
//    $sql_alone = "SELECT id, email FROM users";
//    $res_alone = mysqli_query($connect, $sql_alone);
//    $row_alone = mysqli_fetch_assoc($res_alone);
//    print("user: " . $row_alone['email'] . "</br>");

// Получение кол-ва записей
//    $sql_number    = "SELECT id, name FROM categories";
//    $result_number = mysqli_query($connect, $sql_number);
//
//    $records_count = mysqli_num_rows($result_number);
//    print(" кол-во записей в таблице: " . $records_count . "</br>");
//}
if ($dbHelper->getLastError()) {
    $error = $dbHelper->getLastError();
    $title = "Ошибка соединения с базой";
    http_response_code(404);
    $page_content = include_template("error.php", ["title" => $title, "error" => $error]);
} else {
    mysqli_query($connect, "START TRANSACTION");
    $sql_select_categories    = "SELECT * FROM categories";
    $sql_select_lots          = "SELECT l.name, l.id, l.image, l.price, l.date_end, c.name 'category' FROM lots l JOIN categories c ON l.category_id = c.id ORDER BY l.date_start DESC";
    $sql_select_users         = "SELECT * FROM users";
    if ($sql_select_categories && $sql_select_lots && $sql_select_users) {
        mysqli_query($connect, "COMMIT");
        $result_select_categories = mysqli_query($connect, $sql_select_categories);
        $result_select_lots       = mysqli_query($connect, $sql_select_lots);
        $result_select_users      = mysqli_query($connect, $sql_select_users);
        if ($result_select_categories) {
            $categories = mysqli_fetch_all($result_select_categories, MYSQLI_ASSOC);
        }
        if ($result_select_lots) {
            $lots = mysqli_fetch_all($result_select_lots, MYSQLI_ASSOC);
        }
        if ($result_select_users) {
            $users = mysqli_fetch_all($result_select_users, MYSQLI_ASSOC);
        }
    }
    $sql_get_winner = "SELECT l.id, l.name, bets.user_id FROM bets JOIN lots l ON bets.lot_id = l.id WHERE bets.id IN (SELECT MAX(id) FROM bets GROUP BY lot_id) && l.date_end <= NOW() && l.winner_id is NULL";
    $result_get_winner = mysqli_query($connect, $sql_get_winner);
    if ($result_get_winner) {
        $lot = mysqli_fetch_all($result_get_winner, MYSQLI_ASSOC);
        if (!empty($lot)) {
            foreach ($lot as $key => $value) {
                $sql  = "UPDATE lots SET winner_id = ?  WHERE id = ?";
                $stmt = db_get_prepare_stmt($connect, $sql, [$value['user_id'], $value['id']]);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result) {
                    $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
                }
                $transport = new Swift_SmtpTransport("smtp.gmail.com", 587,'tls');
                $transport->setUsername("glebovakristina841@gmail.com");
                $transport->setPassword("aiwae4Ohhu");
                $mailer = new Swift_Mailer($transport);
                $logger = new Swift_Plugins_Loggers_ArrayLogger();
                $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
                $sql_user = "SELECT id, name, email FROM users WHERE id = {$value['user_id']}";
                $result_select_users      = mysqli_query($connect, $sql_user);
                $winner = mysqli_fetch_all($result_select_users, MYSQLI_ASSOC);
                $message = new Swift_Message();
                $message->setSubject('Ваша ставка победила!');
                $message->setFrom(['glebovakristina841@gmail.com' => 'Yeticave']);
                $message->setTo($winner[0]['email'], $winner[0]['name']);
                $msg_content = include_template('email.php', [
                    'winner' => $winner,
                    'value' => $value,
                ]);
                $message->setBody($msg_content, 'text/html');
                $mailer->send($message);
            }
        }
    }

    else {
        mysqli_query($connect, "ROLLBACK");
    }

}

// ставки пользователей, которыми надо заполнить таблицу
//$bets = [
//    ["name" => "Иван", "price" => 11500, "ts" => strtotime("-" . rand(1, 50) . " minute")],
//    ["name" => "Константин", "price" => 11000, "ts" => strtotime("-" . rand(1, 18) . " hour")],
//    ["name" => "Евгений", "price" => 10500, "ts" => strtotime("-" . rand(25, 50) . " hour")],
//    ["name" => "Семён", "price" => 10000, "ts" => strtotime("last week")],
//];
//
//$lots = [
//    [
//        "id"       => 0,
//        "name"     => "2014 Rossignol District Snowboard",
//        "category" => "Доски и лыжи",
//        "price"    => "10999",
//        "url"      => "img/lot-1.jpg",
//    ],
//    [
//        "id"       => 1,
//        "name"     => "DC Ply Mens 2016/2017 Snowboard",
//        "category" => "Доски и лыжи",
//        "price"    => "159999",
//        "url"      => "img/lot-2.jpg",
//    ],
//    [
//        "id"       => 2,
//        "name"     => "Крепления Union Contact Pro 2015 года размер L/Xl",
//        "category" => "Крепления",
//        "price"    => "8000",
//        "url"      => "img/lot-3.jpg",
//    ],
//    [
//        "id"       => 3,
//        "name"     => "Ботинки для сноубода DC Multiny Charocal",
//        "category" => "Ботинки",
//        "price"    => "10999",
//        "url"      => "img/lot-4.jpg",
//    ],
//    [
//        "id"       => 4,
//        "name"     => "Куртка для сноубода DC Multiny Charocal",
//        "category" => "Одежда",
//        "price"    => "7500",
//        "url"      => "img/lot-5.jpg",
//    ],
//    [
//        "id"       => 5,
//        "name"     => "Маска Oakley Canopy",
//        "category" => "Разное",
//        "price"    => "5400",
//        "url"      => "img/lot-6.jpg",
//    ],
//];
