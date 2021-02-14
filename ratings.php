<?php
require_once 'db_config/db_handler.php';

header("Content-type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $ratings = getRating();
    echo json_encode(["success" => true, "ratings" => $ratings]);
}
