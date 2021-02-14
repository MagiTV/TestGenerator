<?php
require_once 'db_config/db_handler.php';
require_once 'parsing/exportCSV.php';
require_once 'parsing/exportXML.php';
require_once 'parsing/exportJSON.php';

session_start();

if ($_SESSION) {
    if ($_SESSION['user']) {
        exportStudentsCSV();
        exportStudentsXML();
        exportStudentsJSON();
        exportQuestionsCSV();
        exportQuestionsXML();
        exportQuestionsJSON();
        exportTestsCSV();
        exportTestsXML();
        exportTestsJSON();
    }
}

header('Location: configuration.html');