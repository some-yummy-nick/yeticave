<?php
require_once "functions.php";
require_once "data.php";
function searchUserByEmail($email, $users)
{
    $result = null;
    foreach ($users as $user) {
        if ($user["email"] == $email) {
            $result = $user;
            break;
        }
    }

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $required_fields = ["email", "password"];
    $errors          = [];
    $form            = $_POST;
    foreach ($required_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }
    $user = null;
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
        $page_content = include_template('login.php', ['form' => $form, 'errors' => $errors]);
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    $page_content = include_template('login.php', []);
}

$layout_content = include_template(
    "layout.php",
    [
        "content"     => $page_content,
        "title"       => "Войти",
        "categories"  => $categories,
        "is_auth"     => isset($_SESSION['user']),
        "user_name"   => $user_name,
        "user_avatar" => $user_avatar,
    ]
);
print($layout_content);


