<?php

require_once 'db_config/db_handler.php';

// session_start();

//казва, че работим с json
header("Content-type: application/json");

rate();

function rate()
{
    //TODO: delete
    $validData = array();
    $errors = array();
    $errorMessage = "";
    $response = [];

    if ($_POST) {
        $data = json_decode($_POST["data"], true);

        $topicNumber = $data['topicNumber'];
        if (!$topicNumber) {
            $errors['topicNumber'] = 'Темата е задължителна!';
            $errorMessage = $errorMessage . "** Темата е задължителна! **";
        } elseif (!hasTopic(($topicNumber))) {
            $errors['topicNumber'] = 'Несъществуваща тема!';
            $errorMessage = $errorMessage . "** Несъществуваща тема! **";
        } else {
            testField($topicNumber);
            $validData['topicNumber'] = $topicNumber;
        }

        $rating = $data['rating'];

        if ($errors) {
            $response = ["success" => false, "errors" => "$errorMessage"];
        } else {
            rateTopicByNumber($topicNumber, $rating);
            $response = ["success" => true, "message" => "Your rating has been submitted."];
        }

        echo json_encode($response);
    }
}

function testField($field)
{
    $field = trim($field);
    $field = htmlspecialchars($field);
    $field = stripslashes($field);
    return $field;
}
