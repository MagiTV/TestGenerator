<?php

function exportStudentsXML()
{
    $students = getStudents()->fetchAll();

    $xml = new SimpleXMLElement('<students/>');

    foreach ($students as $s) {
        $student = $xml->addChild('student');
        $student->addChild('faculty_number', $s["faculty_number"]);
        $student->addChild('student_name', $s["student_name"]);
        $student->addChild('faculty', $s["faculty"]);
        $student->addChild('major', $s["major"]);
        $student->addChild('course', $s["course"]);
        $student->addChild('group_number', $s["group_number"]);
    }

    $dom = new DOMDocument('1,0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    $f = fopen('exports/students.xml', 'w+');
    //fwrite($f, utf8_decode($dom->saveXML()));
    fwrite($f, $dom->saveXML());
    fclose($f);
}

function exportQuestionsXML()
{
    $questions = getQuestions()->fetchAll();

    $xml = new SimpleXMLElement('<questions/>');

    foreach ($questions as $s) {
        $question = $xml->addChild('question');
        $question->addChild('question_number', $s["question_number"]);
        $question->addChild('test_id', $s["test_id"]);
        $question->addChild('question_text', $s["question_text"]);
        $question->addChild('correct_answer', $s["correct_answer"]);
        $question->addChild('wrong_answer_1', $s["wrong_answer_1"]);
        $question->addChild('wrong_answer_2', $s["wrong_answer_2"]);
        $question->addChild('wrong_answer_3', $s["wrong_answer_3"]);
        $question->addChild('difficulty', $s["difficulty"]);
        $question->addChild('response_correct', $s["response_correct"]);
        $question->addChild('response_wrong', $s["response_wrong"]);
        $question->addChild('more_info', $s["more_info"]);
    }

    $dom = new DOMDocument('1,0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    $f = fopen('exports/questions.xml', 'w+');
    fwrite($f, $dom->saveXML());
    fclose($f);
}

function exportTestsXML()
{
    $tests = getTests()->fetchAll();

    $xml = new SimpleXMLElement('<students/>');

    foreach ($tests as $s) {
        $average = $s["times_taken"] == 0 ? 0 : $s["results_sum"] / $s["times_taken"];

        $test = $xml->addChild('test');
        $test->addChild('id', $s["id"]);
        $test->addChild('owner_fn', $s["owner_fn"]);
        $test->addChild('topic', $s["topic"]);
        $test->addChild('test_type', $s["test_type"]);
        $test->addChild('average', $average);
    }

    $dom = new DOMDocument('1,0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    $f = fopen('exports/tests.xml', 'w+');
    fwrite($f, $dom->saveXML());
    fclose($f);
}
