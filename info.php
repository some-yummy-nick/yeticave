<?php
require_once "functions.php";

$browser = get_browser(null, true);
$number  = 13;
echo "number = " . $number;
//удаление переменной
unset($number);

$myFriends = [];

// добавление в конец массива
$myFriends[] = "Winnie Pooh";
// или
array_push($myFriends, "Tiger");
debug($myFriends);

//переделать массив в строку
$myFriendsToString = implode(",", $myFriends);
debug($myFriendsToString);

$gif = [
    'gif'         => '/uploads/preview_gif58d28ce80e3a9.gif',
    'title'       => 'Енотик',
    'likes_count' => 0,
];
// получить все ключи в ассоциативном массиве
debug(array_keys($gif));

$long_text = "Поэт инстинктивно чувствовал преимущества реального устного исполнения тех стихов";
// Обрезать длинный текст
function cut_text($text, $num_letters)
{
    $num = mb_strlen($text);

    if ($num > $num_letters) {
        $text = mb_substr($text, 0, $num_letters);
        $text .= "…";
    }

    return $text;
}

$short_text = cut_text($long_text, 25);

print($short_text);
print_r("<br><br>");
$date = date("d.m.Y");
echo "Текущая дата " . $date;
print_r("<br><br>");
$someDate = "7 March 2019";
//Перевести дату в timestamp
$someDateToTimestamp = strtotime($someDate);
debug($someDateToTimestamp);
//Сколько осталось дней до нового года
$curday = date("z");
// Год високосный или нет
$yds           = date("L") ? 366 : 365;
$days_remining = $yds - $curday;
echo "До Нового года осталось <strong>" . $days_remining . "</strong> дней" . "<br><br>";

//Время до полуночи
$ts              = time();
$tsMidnight      = strtotime("tomorrow");
$secToMidnight   = $tsMidnight - $ts;
$hoursToMidnight = floor($secToMidnight / 3600);
$minToMidnight = floor(($secToMidnight % 3600) / 60);
echo "До полуночи осталось: " . $hoursToMidnight . " часов и " . $minToMidnight . " минут";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Info</title>
</head>
<body>
<h1>Информация о пользователе</h1>
<dl>
    <dt><strong>Ваш IP адрес</strong></dt>
    <dd><em><?= $_SERVER['REMOTE_ADDR'] ?></em></dd>
</dl>
<dl>
    <dt><strong>Операционная система:</strong></dt>
    <dd><em><?= $browser['platform'] ?></em></dd>
</dl>
<dl>
    <dt><strong>Браузер:</strong></dt>
    <dd><em><?= $browser['browser'] ?></em></dd>
</dl>
</body>
</html>
