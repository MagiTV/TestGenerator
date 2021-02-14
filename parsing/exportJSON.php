<?php

class student
{
    public $faculty_number;
    public $student_name;
    public $faculty;
    public $major;
    public $course;
    public $group_number;
}

function exportStudentsJSON()
{
    $students = getStudents();

    $dataToExport = $students->fetchAll(PDO::FETCH_CLASS, "student");
    $exportFile = fopen('exports/students.json', 'w+');

    $encoded = json_encode($dataToExport);
    fwrite($exportFile, $encoded);

    fclose($exportFile);
}

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

function exportQuestionsJSON()
{
    $questions = getQuestions();

    $exportFile = fopen('exports/questions.json', 'w+');
    $dataToExport = $questions->fetchAll(PDO::FETCH_CLASS, "question");

    $encoded = json_encode($dataToExport);
    fwrite($exportFile, $encoded);

    fclose($exportFile);
}

class test
{
    public $id;
    public $owner_fn;
    public $topic;
    public $test_type;
    public $results_sum;
    public $times_taken;
}

function exportTestsJSON()
{
    $tests = getTests();

    $exportFile = fopen('exports/tests.json', 'w+');

    $dataToExport = $tests->fetchAll(PDO::FETCH_CLASS, "test");

    $encoded = json_encode($dataToExport);
    fwrite($exportFile, $encoded);

    fclose($exportFile);
}
