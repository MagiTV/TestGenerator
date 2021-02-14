<?php
require_once 'db_config/db_handler.php';

session_start();

header("Content-type: application/json");

if ($_SESSION) {
    if ($_SESSION['user']) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode($_POST["data"], true);

            if(!hasStudent($data["fn"]))
            {
                echo json_encode(["success" => false, "errors" => "Невалиден факултетен номер!"]);
            } else {
                $presence = getPresence($data["fn"]);
                $presenceCount = getAllPresenceNumber($data["fn"]);
                echo json_encode(["success" => true, "presence" => $presence, "presenceCount" => $presenceCount]);
            }
        }
    }
}
?>