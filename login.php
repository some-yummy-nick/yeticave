<?php
require_once "functions.php";
require_once "data.php";
$success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $required_fields = ["email", "password"];
    $errors          = [];
    $form            = $_POST;
    foreach ($required_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        } else {
            if ($field = "email") {
                if (filter_var($form[$field], FILTER_VALIDATE_EMAIL) === false) {
                    $errors[$field] = "Формат почтового ящика неправильный";
                }
            }
        }
    }
    if (!count($errors)) {
        $user = searchUserByEmail($form['email'], $users);

        if ($user) {
            if (password_verify($form['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['email']    = 'Такой пользователь не найден';
            $errors['password'] = 'Неверный пароль';

        }
    }

    if (count($errors)) {
        $page_content = include_template('login.php', ['form' => $form, 'errors' => $errors, "success" => $success]);
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    if (isset($_GET['success'])) {
        $success = intval($_GET['success']);
    }

    $page_content = include_template('login.php', ["success" => $success]);
}

$layout_content = include_template(
    "layout.php",
    [
        "content"    => $page_content,
        "title"      => "Войти",
        "categories" => $categories,
        "is_auth"    => isset($_SESSION['user']),
    ]
);
print($layout_content);


