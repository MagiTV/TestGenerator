<?php

function exportStudentsCSV()
{
    $students = getStudents()->fetchAll();

    $exportFile = fopen('exports/students.csv', 'w+');
    $dataToExport = array();

    $dataToExport[0] = array('faculty_number', 'student_name', 'faculty', 'major', 'course', 'group_number');

    for ($i = 0; $i < count($students); $i++) {
        $dataToExport[$i + 1] = array(
            intval($students[$i]['faculty_number']),
            $students[$i]['student_name'],
            $students[$i]['faculty'],
            $students[$i]['major'],
            $students[$i]['course'],
            intval($students[$i]['group_number'])
        );
    }

    foreach ($dataToExport as $row) {
        fputcsv($exportFile, $row);
    }

    fclose($exportFile);
}

function exportQuestionsCSV()
{
    $questions = getQuestions()->fetchAll();

    $exportFile = fopen('exports/questions.csv', 'w+');
    $dataToExport = array();

    $dataToExport[0] = array(
        'question_number', 'test_id', 'question_text',
        'correct_answer', 'wrong_answer_1', 'wrong_answer_2', 'wrong_answer_3', 'difficulty',
        'response_correct', 'response_wrong', 'more_info'
    );

    for ($i = 0; $i < count($questions); $i++) {
        $dataToExport[$i + 1] = array(
            intval($questions[$i]['question_number']),
            intval($questions[$i]['test_id']),
            $questions[$i]['question_text'],
            $questions[$i]['correct_answer'],
            $questions[$i]['wrong_answer_1'],
            $questions[$i]['wrong_answer_2'],
            $questions[$i]['wrong_answer_3'],
            intval($questions[$i]['difficulty']),
            $questions[$i]['response_correct'],
            $questions[$i]['response_wrong'],
            $questions[$i]['more_info'],
        );
    }

    foreach ($dataToExport as $row) {
        fputcsv($exportFile, $row);
    }

    fclose($exportFile);
}

function exportTestsCSV()
{
    $tests = getTests()->fetchAll();

    $exportFile = fopen('exports/tests.csv', 'w+');
    $dataToExport = array();

    $dataToExport[0] = array('id', 'owner_fn', 'topic', 'test_type', 'average_result');

    for ($i = 0; $i < count($tests); $i++) {
        $avgResult = 0;
        if ($tests[$i]['times_taken'] != 0) {
            $avgResult = intval($tests[$i]['results_sum']) / intval($tests[$i]['times_taken']);
        }

        $dataToExport[$i + 1] = array(
            intval($tests[$i]['id']),
            intval($tests[$i]['owner_fn']),
            $tests[$i]['topic'],
            $tests[$i]['test_type'],
            $avgResult
        );
    }

    foreach ($dataToExport as $row) {
        fputcsv($exportFile, $row);
    }

    fclose($exportFile);
}
