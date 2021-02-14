<?php

function importStudentsXML(&$hasErrors)
{
    $name = $_FILES['studentsXML']['name'];
    $tempName = $_FILES['studentsXML']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.xml');

    $students = simplexml_load_file('uploads/' . $name) or die("Error: Cannot create object");

    foreach ($students->student as $item) {
        $assoc_student = convertStudentToAssociativeArrayXML($item);
        if (!insertStudent($assoc_student)) {
            $hasErrors = true;
            throw new Exception("Error while saving students.");
        }
    }
}


function convertStudentToAssociativeArrayXML($xml_student)
{
    $associative_student["faculty_number"] = (string)$xml_student->faculty_number;
    $associative_student["student_name"] = (string)$xml_student->student_name;
    $associative_student["faculty"] = (string)$xml_student->faculty;
    $associative_student["major"] = (string)$xml_student->major;
    $associative_student["course"] = (string)$xml_student->course;
    $associative_student["group_number"] = (string)$xml_student->group_number;
    return $associative_student;
}

function importTestsXML(&$hasErrors)
{
    $name = $_FILES['referatTableXML']['name'];
    $tempName = $_FILES['referatTableXML']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.xml');

    $referats = simplexml_load_file('uploads/' . $name) or die("Error: Cannot create object");

    foreach ($referats->referat as $item) {
        $tests = convertReferatToAssociativeArrayXML($item);

        foreach ($tests as $test) {
            if (!insertTest($test)) {
                $hasErrors = true;
                throw new Exception("Error while creating tests for referats.");
            }
        }
        rateTopic((string)$item->faculty_number, (string)$item->topic, (string)$item->topic_name, 0);
    }
}

function convertReferatToAssociativeArrayXML($xml_referat)
{
    $test_before["owner_fn"] = (string)$xml_referat->faculty_number;
    $test_before["topic"] = (string)$xml_referat->topic;

    $test_before["test_type"] = "before_presentation";
    $test_before["results_sum"] = "0";
    $test_before["times_taken"] = "0";

    $test_during["owner_fn"] = (string)$xml_referat->faculty_number;
    $test_during["topic"] = (string)$xml_referat->topic;

    $test_during["test_type"] = "during_presentation";
    $test_during["results_sum"] = "0";
    $test_during["times_taken"] = "0";

    $test_after["owner_fn"] = (string)$xml_referat->faculty_number;
    $test_after["topic"] = (string)$xml_referat->topic;

    $test_after["test_type"] = "after_presentation";
    $test_after["results_sum"] = "0";
    $test_after["times_taken"] = "0";

    return array($test_before, $test_during, $test_after);
}

function importQuestionsXML(&$hasErrors)
{
    $name = $_FILES['questionsXML']['name'];
    $tempName = $_FILES['questionsXML']['tmp_name'];
    saveFile($name, $tempName, $hasErrors, '.xml');

    $xml = simplexml_load_file('uploads/' . $name) or die("Error: Cannot create object");
    foreach ($xml->question as $item) {
        $assoc_question = convertQuestionToAssociativeArrayXML($item);
        if (!insertQuestion($assoc_question)) {
            $hasErrors = true;
            throw new Exception("Error while adding questions.");
        }
    }
}

function convertQuestionToAssociativeArrayXML($xml_question)
{
    $answers[0] = (string)$xml_question->answer_1;
    $answers[1] = (string)$xml_question->answer_2;
    $answers[2] = (string)$xml_question->answer_3;
    $answers[3] = (string)$xml_question->answer_4;

    $correctAnswerIndex = (int)$xml_question->correct_answer - 1;
    $correctAnswer = $answers[$correctAnswerIndex];

    array_splice($answers, $correctAnswerIndex, 1);

    $assoc_question["question_number"] = (string)$xml_question->question_number;
    $assoc_question["test_id"] = getTestId((string)$xml_question->faculty_number, (string)$xml_question->question_type);
    $assoc_question["question_text"] = (string)$xml_question->question_text;
    $assoc_question["correct_answer"] = $correctAnswer;
    $assoc_question["wrong_answer_1"] = $answers[0];
    $assoc_question["wrong_answer_2"] = $answers[1];
    $assoc_question["wrong_answer_3"] = $answers[2];
    $assoc_question["difficulty"] = (string)$xml_question->difficulty;
    $assoc_question["response_correct"] = (string)$xml_question->response_correct;
    $assoc_question["response_wrong"] = (string)$xml_question->response_wrong;
    $assoc_question["more_info"] = (string)$xml_question->more_info;

    return $assoc_question;
}
