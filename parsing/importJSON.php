<?php

function importStudentsJSON(&$hasErrors)
{
    $name = $_FILES['studentsJSON']['name'];
    $tempName = $_FILES['studentsJSON']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.json');

    $string = file_get_contents('uploads/' . $name) or die("Error: Cannot create object");
    $students = json_decode($string, true);

    foreach ($students["students"] as $student) {
        if (!insertStudent($student)) {
            $hasErrors = true;
            throw new Exception("Error while saving students.");
        }
    }
}

function importTestsJSON(&$hasErrors)
{
    $name = $_FILES['referatTableJSON']['name'];
    $tempName = $_FILES['referatTableJSON']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.json');


    $string = file_get_contents('uploads/' . $name) or die("Error: Cannot create object");
    $referats = json_decode($string, true);

    foreach ($referats["referats"] as $referat) {
        $tests = convertReferatToAssociativeArrayJSON($referat);
        foreach ($tests as $test) {
            if (!insertTest($test)) {
                $hasErrors = true;
                throw new Exception("Error while creating tests for referats.");
            }
        }
        rateTopic($referat["faculty_number"], $referat["topic"], $referat["topic_name"], 0);
    }
}

function convertReferatToAssociativeArrayJSON($referat)
{
    $test_before["owner_fn"] = $referat["faculty_number"];
    $test_before["topic"] = $referat["topic"];

    $test_before["test_type"] = "before_presentation";
    $test_before["results_sum"] = "0";
    $test_before["times_taken"] = "0";

    $test_during["owner_fn"] = $referat["faculty_number"];
    $test_during["topic"] = $referat["topic"];

    $test_during["test_type"] = "during_presentation";
    $test_during["results_sum"] = "0";
    $test_during["times_taken"] = "0";

    $test_after["owner_fn"] = $referat["faculty_number"];
    $test_after["topic"] = $referat["topic"];

    $test_after["test_type"] = "after_presentation";
    $test_after["results_sum"] = "0";
    $test_after["times_taken"] = "0";

    return array($test_before, $test_during, $test_after);
}

function importQuestionsJSON(&$hasErrors)
{
    $name = $_FILES['questionsJSON']['name'];
    $tempName = $_FILES['questionsJSON']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.json');

    $string = file_get_contents('uploads/' . $name) or die("Error: Cannot create object");
    $questions = json_decode($string, true);

    foreach ($questions["questions"] as $item) {
        $assoc_question = convertQuestionToAssociativeArrayJSON($item);
        if (!insertQuestion($assoc_question)) {
            $hasErrors = true;
            throw new Exception("Error while adding questions.");
        }
    }
}

function convertQuestionToAssociativeArrayJSON($question)
{
    $answers[0] = $question["answer_1"];
    $answers[1] = $question["answer_2"];
    $answers[2] = $question["answer_3"];
    $answers[3] = $question["answer_4"];

    $correctAnswerIndex = $question["correct_answer"] - 1;
    $correctAnswer = $answers[$correctAnswerIndex];

    array_splice($answers, $correctAnswerIndex, 1);

    $assoc_question["question_number"] = $question["question_number"];
    $assoc_question["test_id"] = getTestId($question["faculty_number"], $question["question_type"]);
    $assoc_question["question_text"] = $question["question_text"];
    $assoc_question["correct_answer"] = $correctAnswer;
    $assoc_question["wrong_answer_1"] = $answers[0];
    $assoc_question["wrong_answer_2"] = $answers[1];
    $assoc_question["wrong_answer_3"] = $answers[2];
    $assoc_question["difficulty"] = $question["difficulty"];
    $assoc_question["response_correct"] = $question["response_correct"];
    $assoc_question["response_wrong"] = $question["response_wrong"];
    $assoc_question["more_info"] = $question["more_info"];

    return $assoc_question;
}
