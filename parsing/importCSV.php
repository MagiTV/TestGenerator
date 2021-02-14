<?php

function importStudents(&$hasErrors)
{
    $name = $_FILES['studentsCSV']['name'];
    $tempName = $_FILES['studentsCSV']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.csv');

    $students = file('uploads/' . $name);
    $firstLine = true;
    foreach ($students as $student) {
        if ($firstLine) {
            $firstLine = false;
        } else {
            $csv_student = str_getcsv($student);
            $assoc_student = convertStudentToAssociativeArray($csv_student);
            if (!insertStudent($assoc_student)) {
                $hasErrors = true;
                throw new Exception("Error while saving students.");
            }
        }
    }
}

function saveFile($name, $tempName, &$hasErrors, $fileExtension)
{
    $location = 'uploads/';
    if (isset($name) and !empty($name)) {
        if (move_uploaded_file($tempName, $location . $name)) {
            echo 'File ' . $name . ' uploaded successfully.<br>';
        }
    } else {
        $hasErrors = true;
        echo 'You should select a valid ' . $fileExtension . ' file to upload!<br>';
    }
}

function convertStudentToAssociativeArray($csv_student)
{
    $associative_student["faculty_number"] = $csv_student[0];
    $associative_student["student_name"] = $csv_student[1];
    $associative_student["faculty"] = $csv_student[2];
    $associative_student["major"] = $csv_student[3];
    $associative_student["course"] = $csv_student[4];
    $associative_student["group_number"] = $csv_student[5];
    return $associative_student;
}

function importTests(&$hasErrors)
{
    $name = $_FILES['referatTableCSV']['name'];
    $tempName = $_FILES['referatTableCSV']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.csv');

    $referats = file('uploads/' . $name);
    $firstLine = true;
    foreach ($referats as $referat) {
        if ($firstLine) {
            $firstLine = false;
        } else {
            $csv_referat = str_getcsv($referat);
            $assoc_referat = convertReferatToAssociativeArray($csv_referat);
            foreach ($assoc_referat as $test) {
                if (!insertTest($test)) {
                    $hasErrors = true;
                    throw new Exception("Error while saving test.");
                }
            }
            rateTopic($csv_referat[1], $csv_referat[3], $csv_referat[4], 0);
        }
    }
}

function convertReferatToAssociativeArray($csv_referat)
{
    $test_before["owner_fn"] = $csv_referat[1]; // TODO ASK TO ADD IN FILE
    $test_before["topic"] = $csv_referat[3];

    $test_before["test_type"] = "before_presentation";
    $test_before["results_sum"] = "0";
    $test_before["times_taken"] = "0";

    $test_during["owner_fn"] = $csv_referat[1];
    $test_during["topic"] = $csv_referat[3];

    $test_during["test_type"] = "during_presentation";
    $test_during["results_sum"] = "0";
    $test_during["times_taken"] = "0";

    $test_after["owner_fn"] = $csv_referat[1];
    $test_after["topic"] = $csv_referat[3];

    $test_after["test_type"] = "after_presentation";
    $test_after["results_sum"] = "0";
    $test_after["times_taken"] = "0";

    return array($test_before, $test_during, $test_after);
}

function importQuestions(&$hasErrors)
{
    $name = $_FILES['questionsCSV']['name'];
    $tempName = $_FILES['questionsCSV']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.csv');

    $questions = file('uploads/' . $name);
    $firstLine = true;
    foreach ($questions as $question) {
        if ($firstLine) {
            $firstLine = false;
        } else {
            $csv_question = str_getcsv($question);
            $assoc_question = convertQuestionToAssociativeArray($csv_question);
            if (!insertQuestion($assoc_question)) {
                $hasErrors = true;
                throw new Exception("Error while adding questions.");
            }
        }
    }
}

function convertQuestionToAssociativeArray($csv_question)
{
    $answers[0] = $csv_question[5];
    $answers[1] = $csv_question[6];
    $answers[2] = $csv_question[7];
    $answers[3] = $csv_question[8];

    $correctAnswerIndex = (int)$csv_question[9] - 1;
    $correctAnswer = $answers[$correctAnswerIndex];

    array_splice($answers, $correctAnswerIndex, 1);

    $assoc_question["question_number"] = $csv_question[2];

    if ($csv_question[14] == 1) {
        $type = "before_presentation";
    }
    if ($csv_question[14] == 2) {
        $type = "during_presentation";
    }
    if ($csv_question[14] == 3) {
        $type = "after_presentation";
    }

    $assoc_question["test_id"] = getTestId($csv_question[1], $type);
    $assoc_question["question_text"] = $csv_question[4];
    $assoc_question["correct_answer"] = $correctAnswer;
    $assoc_question["wrong_answer_1"] = $answers[0];
    $assoc_question["wrong_answer_2"] = $answers[1];
    $assoc_question["wrong_answer_3"] = $answers[2];
    $assoc_question["difficulty"] = $csv_question[10];
    $assoc_question["response_correct"] = $csv_question[11];
    $assoc_question["response_wrong"] = $csv_question[12];
    $assoc_question["more_info"] = $csv_question[13];

    return $assoc_question;
}
