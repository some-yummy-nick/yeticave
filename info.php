<?php
$browser = get_browser(null, true);
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
    <dd><em><?print_r($_SERVER['REMOTE_ADDR']) ?></em></dd>
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
