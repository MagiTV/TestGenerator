<?php
require_once 'db_connection.php';

$hasErrors = false;

@initDB($hasErrors);

if ($hasErrors) {
    echo "Failed to configure the system!<br>";
    header('Location: configuration.html');
}
