<?php
session_save_path("../session");
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (($_SESSION['user'] ?? '') === '') {
    header('Location: /');
}

    require_once 'connect.php';

    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));

    $check_user = mysqli_query($conn, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");
    if (mysqli_num_rows($check_user) > 0) {

        $user = mysqli_fetch_assoc($check_user);

        $_SESSION['user'] = [
            "id" => $user['id'],
            "full_name" => $user['full_name'],
            "avatar" => $user['avatar'],
            "email" => $user['email']
        ];

        header('Location: ../selpage.php');

    } else {
        $_SESSION['message'] = 'Не верный логин или пароль';
        header('Location: ../index.php');
    }
    ?>
