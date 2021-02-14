<?php
require_once 'db_config/db_handler.php';
require_once 'parsing/importCSV.php';
require_once 'parsing/importXML.php';
require_once 'parsing/importJSON.php';

$hasErrors = false;

session_start();

if ($_SESSION) {        
    
    if ($_SESSION['user']) {
        clearDB();

        if (is_uploaded_file($_FILES['studentsCSV']['tmp_name'])) {
            @importStudents($hasErrors);
        } elseif (is_uploaded_file($_FILES['studentsXML']['tmp_name'])) {
            @importStudentsXML($hasErrors);
        } elseif (is_uploaded_file($_FILES['studentsJSON']['tmp_name'])) {
            @importStudentsJSON($hasErrors);
        }

        if (is_uploaded_file($_FILES['referatTableCSV']['tmp_name'])) {
            @importTests($hasErrors);
        } elseif (is_uploaded_file($_FILES['referatTableXML']['tmp_name'])) {
            @importTestsXML($hasErrors);
        } elseif (is_uploaded_file($_FILES['referatTableJSON']['tmp_name'])) {
            @importTestsJSON($hasErrors);
        }

        if (is_uploaded_file($_FILES['questionsCSV']['tmp_name'])) {
            @importQuestions($hasErrors);
        } elseif (is_uploaded_file($_FILES['questionsXML']['tmp_name'])) {
            @importQuestionsXML($hasErrors);
        } elseif (is_uploaded_file($_FILES['questionsJSON']['tmp_name'])) {
            @importQuestionsJSON($hasErrors);
        }
        @initPresenceDB();
    }
}

header('Location: configuration.html');