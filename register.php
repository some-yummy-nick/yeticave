<?php
require_once "functions.php";
require_once "data.php";
$errors = [];
$form = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form = $_POST;
    $rules = [
        'email' => 'required|valid_email',
        'password' => 'required|min_len,8',
        'name' => 'required|alpha_numeric',
    ];
    $gump = new GUMP('ru');
    $gump->set_field_name("name", "Имя");
    $gump->set_field_name("password", "Пароль");
    $gump->validation_rules($rules);
    $validated_data = $gump->run($_POST);
    if (!$validated_data) {
        $errors = $gump->get_errors_array();
    }

//    $required_fields = ["email", "password", "name", "contacts"];

//    foreach ($required_fields as $field) {
//        if (empty($form[$field])) {
//            $errors[$field] = "Это поле надо заполнить";
//        } else {
//            if ($field = "email") {
//                if (filter_var($form[$field], FILTER_VALIDATE_EMAIL) === false) {
//                    $errors[$field] = "Формат почтового ящика неправильный";
//                }
//                if (searchUserByEmail(filter_var($form[$field]), $users)) {
//                    $errors[$field] = "Такой почтовый ящик уже используется";
//                }
//            }
//        }
//    }
    if (!empty($_FILES["user-photo"]["name"])) {
        $tmp_name = $_FILES["user-photo"]["tmp_name"];
        $path = $_FILES["user-photo"]["name"];
        $photo = $_POST;
        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($fInfo, $tmp_name);
        $file_size = $_FILES["user-photo"]["size"];
        if ($file_type !== "image/jpeg") {
            $errors["user-photo"] = "Загрузите картинку в формате jpg или png";
        } else if ($file_size > 200000) {
            $errors["user-photo"] = "Максимальный размер файла: 200Кб";
        } else {
            move_uploaded_file($tmp_name, "uploads/" . $path);
            $form["user-photo"] = $path;
        }
    }

    if (!count($errors)) {
        $sql_user = "INSERT INTO users (`email`, `password`, `name`, `contacts`, `date`, `avatar`) VALUES (?, ?, ?, ?,NOW(),?)";
        $password_cash = password_hash($form["password"], PASSWORD_DEFAULT);
        $avatar = "img/user.jpg";
        if (isset($form["user-photo"])) {
            $avatar = "uploads/" . $form["user-photo"];
        }
        $stmt_user = db_get_prepare_stmt(
            $connect,
            $sql_user,
            [$form["email"], $password_cash, $form["name"], $form["contacts"], $avatar]
        );
        $res = mysqli_stmt_execute($stmt_user);
    }

    if (count($errors)) {
        $page_content = include_template("register.php", ["form" => $form, "errors" => $errors]);
    } else {
        header("Location: /login.php?success=1");
        exit();
    }
} else {
    $page_content = include_template("register.php", []);
}

$layout_content = include_template(
    "layout.php",
    [
        "content" => $page_content,
        "title" => $app_name . " | Регистрация",
        "categories" => $categories,
        "is_auth" => isset($_SESSION["user"]),
    ]
);
print($layout_content);


