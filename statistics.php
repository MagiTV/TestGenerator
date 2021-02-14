<?php
require_once 'db_config/db_handler.php';

session_start();

header("Content-type: application/json");

if ($_SESSION) {
    if ($_SESSION['user']) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $topics = getTestsForStatistics(); // topic, avgB, avgD, avgA
            $people = getStudentsTests(); // fn, topic, type, result 

            echo json_encode(["success" => true, "topics" => $topics, "people" => $people]);
        }
    }
}
?>