<?php
session_start();

if ($_SESSION) {
    session_unset();
    session_destroy();
}

header('Location: login.html');
exit();
