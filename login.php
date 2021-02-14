<?php

require_once 'db_config/db_handler.php';

header("Content-type: application/json");

addAdminsToDB($hasErrors);

session_start();

$response = [];
$hasErrors = false;

if ($_POST) {
    $data = json_decode($_POST["data"], true);

    $username = $data['username'];
    if (!$username) {
        $hasErrors = true;
    }

    $password = $data['password'];
    if (!$password) {
        $hasErrors = true;
    }

    if (!$hasErrors && checkValidLogin($username, $password)) {
        $response = ["success" => true];
        $_SESSION['user'] = $username;
    } else {
        $response = ["success" => false, "errors" => "Невалидно потребителско име или парола!"];
    }

    echo json_encode($response);
}
