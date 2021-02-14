<?php

require_once 'db_config/db_handler.php';

session_start();

header("Content-type: application/json");

getInput();

function getInput()
{
    //TODO: delete
    $validData = array();
    $errors = array();
    $errorMessage = "";
    $response = [];

    if ($_POST) {
        $data = json_decode($_POST["data"], true);

        $facultyNumber = $data['facultyNumber'];
        if (!$facultyNumber) {
            $errors['facultyNumber'] = 'Факултетният номер е задължителен!';
            $errorMessage = $errorMessage . "** Факултетният номер е задължителен! ** <br/>";
        } elseif (!hasStudent(($facultyNumber))) {
            $errors['facultyNumber'] = 'Несъществуващ факултетен номер!';
            $errorMessage = $errorMessage . "** Несъществуващ факултетен номер! ** <br/>";
        } else {
            testField($facultyNumber);
            $validData['facultyNumber'] = $facultyNumber;
        }

        $topicNumber = $data['topicNumber'];
        if (!$topicNumber) {
            $errors['topicNumber'] = 'Темата е задължителна!';
            $errorMessage = $errorMessage . "** Темата е задължителна! ** <br/>";
        } elseif (!hasTopic(($topicNumber))) {
            $errors['topicNumber'] = 'Несъществуваща тема!';
            $errorMessage = $errorMessage . "** Несъществуваща тема! ** <br/>";
        } else {
            testField($topicNumber);
            $validData['topicNumber'] = $topicNumber;
        }

        $testType = $data['testType'];
        if (!$testType) {
            $errors['type'] = 'Типът е задължителен!';
            $errorMessage = $errorMessage . "** Типът е задължителен! ** <br/>";
        } else {
            testField($testType);
            $validData['type'] = $testType;
        }

        if ($errors) {
            $response = ["success" => false, "errors" => "$errorMessage"];
        } else {
            $response = ["success" => true];
            $_SESSION['fn'] = $facultyNumber;
            $_SESSION['topic'] = $topicNumber;
            $_SESSION['testType'] = $testType;
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
