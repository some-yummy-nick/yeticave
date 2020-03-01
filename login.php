<?php
require_once "functions.php";
require_once "data.php";
$success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $form = $_POST;
    $rules = [
        'email' => 'required|valid_email',
        'password' => 'required|min_len,8',
    ];
    $gump = new GUMP('ru');
    $gump->set_field_name("password", "Пароль");
    $gump->validation_rules($rules);
    $validated_data = $gump->run($_POST);
    if (!$validated_data) {
        $errors = $gump->get_errors_array();
    }
    if (!count($errors)) {
        $user = searchUserByEmail($form["email"], $users);

        if ($user) {
            if (password_verify($form["password"], $user["password"])) {
                $_SESSION["user"] = $user;
            } else {
                $errors["password"] = "Неверный пароль";
            }
        } else {
            $errors["email"] = "Такой пользователь не найден";
            $errors["password"] = "Неверный пароль";

        }
    }

    if (count($errors)) {
        $page_content = include_template("login.php", ["form" => $form, "errors" => $errors, "success" => $success]);
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    if (isset($_GET["success"])) {
        $success = intval($_GET["success"]);
    }

    $page_content = include_template("login.php", ["success" => $success]);
}

$layout_content = include_template(
    "layout.php",
    [
        "content" => $page_content,
        "title" => $app_name . " | Войти",
        "categories" => $categories,
        "is_auth" => isset($_SESSION["user"]),
    ]
);
print($layout_content);


