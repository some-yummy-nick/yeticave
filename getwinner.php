<?php
$sql_get_winner = "SELECT l.id, l.name, bets.user_id FROM bets JOIN lots l ON bets.lot_id = l.id WHERE bets.id IN (SELECT MAX(id) FROM bets GROUP BY lot_id) && l.date_end <= NOW() && l.winner_id is NULL";
$dbHelper->executeQuery($sql_get_winner);
if (!$dbHelper->getLastError()) {
    $lot = $dbHelper->getResultAsArray();
    if (!empty($lot)) {
        $winner = null;
        foreach ($lot as $key => $value) {
            $sql = "UPDATE lots SET winner_id = ?  WHERE id = ?";
            $dbHelper->executeQuery($sql, [$value['user_id'], $value['id']]);
            $transport = new Swift_SmtpTransport("smtp.gmail.com", 587, 'tls');
            $transport->setUsername("glebovakristina841@gmail.com");
            $transport->setPassword("aiwae4Ohhu");
            $mailer = new Swift_Mailer($transport);
            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
            $sql_user = "SELECT id, name, email FROM users WHERE id = {$value['user_id']}";
            $dbHelper->executeQuery($sql_user);
            if (!$dbHelper->getLastError()) {
                $winner = $dbHelper->getResultAsArray();
            }
            $message = new Swift_Message();
            $message->setSubject('Ваша ставка победила!');
            $message->setFrom(['glebovakristina841@gmail.com' => 'Yeticave']);
            $message->setTo($winner[0]['email'], $winner[0]['name']);
            $msg_content = include_template(
                'email.php',
                [
                    'winner' => $winner,
                    'value' => $value,
                ]
            );
            $message->setBody($msg_content, 'text/html');
            $mailer->send($message);
        }
    }
}
