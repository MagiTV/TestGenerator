<?php
require_once 'db_config/db_handler.php';

session_start();

header("Content-type: application/json");

class question
{
    public $question_number;
    public $test_id;
    public $question_text;
    public $correct_answer;
    public $wrong_answer_1;
    public $wrong_answer_2;
    public $wrong_answer_3;
    public $difficulty;
    public $response_correct;
    public $response_wrong;
    public $more_info;
}

getQuery();

function getQuery()
{
    if ($_SESSION) {
        if ($_SESSION['fn'] && $_SESSION['topic'] && $_SESSION['testType']) {

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $questions = getTestByTopic($_SESSION['topic'], $_SESSION['testType']);
                $dataToExport = $questions->fetchAll(PDO::FETCH_CLASS, "question");

                echo json_encode(["success" => true, "questions" => [$dataToExport], "topic" => $_SESSION['topic']]);
            }
 
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode($_POST["data"], true);

                $correctAnswers = $data['correctAnswers'];
                $questionsNumber = $data['questionsNumber'];

                $result = 100 * $correctAnswers / $questionsNumber;
                updateTestResults($_SESSION['topic'], $_SESSION['testType'], $result);

                insertTestTaken($_SESSION['fn'], $_SESSION['topic'], $_SESSION['testType'], ceil($result));
                
                if ($_SESSION['testType'] === 'during_presentation'){
                    updatePresence($_SESSION['fn'],$_SESSION['topic']);
                }

                session_unset();
                session_destroy();

                echo json_encode(["success" => true]);
            }
        }
    }
}

