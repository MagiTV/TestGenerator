<?php

$dbConnection;

function initDB(&$hasErrors)
{
    $dbHost = "localhost";
    $dbName = "referat_questions";
    $dbUser = "root";
    $dbPassword = "";

    global $dbConnection;

    try {
        $dbConnection = new PDO("mysql:dbHost=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPassword);
    } catch (PDOException $e) {
        $hasErrors = true;
        throw new Exception('Connection to database failed: ' . $e->getMessage());
    }
}

function addAdminsToDB(&$hasErrors)
{
    if (hasAdminsInDB()) {
        return;
    }

    if (!saveAdmins()) {
        $hasErrors = true;
        throw new Exception("Error while saving admins data into database");
    }
}

function hasAdminsInDB()
{
    global $dbConnection;

    $selectAdminIds = "SELECT id FROM admins";
    $dbStatement = $dbConnection->prepare($selectAdminIds);
    $dbStatement->execute();
    if ($dbStatement->rowCount() > 0) {
        return true;
    }
    return false;
}

function saveAdmins()
{
    global $dbConnection;
    $insertAdmins = "INSERT INTO admins (adminName, pass)
                    VALUES ( :adminName, :pass)";

    $dbStatement = $dbConnection->prepare($insertAdmins) or die("Failed to prepare DBStatement for execution");
    return $dbStatement->execute(array(
        ':adminName' => "admin",
        ':pass' => password_hash("5678", PASSWORD_DEFAULT)
    ));
}

function clearDB()
{
    clearStudents();
    clearTests();
    clearQuestions();
    clearPresence();
    clearTestsTaken();
    clearTopicRating();
}

function clearStudents()
{
    global $dbConnection;
    $deleteStudents = "DELETE FROM students";
    $query = $dbConnection->prepare($deleteStudents) or die("failed to prepare query for deleting students");

    $query->execute();
}

function clearTests()
{
    global $dbConnection;
    $deleteTests = "DELETE FROM tests";
    $query = $dbConnection->prepare($deleteTests) or die("failed to prepare query for deleting tests");
    $query->execute();
}

function clearQuestions()
{
    global $dbConnection;
    $deleteQuestions = "DELETE FROM questions";
    $query = $dbConnection->prepare($deleteQuestions) or die("failed to prepare query for deleting questions");
    $query->execute();
}

function clearPresence()
{
    global $dbConnection;
    $deletePresence = "DELETE FROM presence";
    $query = $dbConnection->prepare($deletePresence) or die("failed to prepare query for deleting presence");
    $query->execute();
}

function clearTestsTaken()
{
    global $dbConnection;
    $deleteTestsTaken = "DELETE FROM testTaken";
    $query = $dbConnection->prepare($deleteTestsTaken) or die("failed to prepare query for deleting testTaken");
    $query->execute();
}


function clearTopicRating()
{
    global $dbConnection;
    $deleteRating = "DELETE FROM topicRating";
    $query = $dbConnection->prepare($deleteRating) or die("failed to prepare query for deleting topicRating");
    $query->execute();
}


function insertStudent($student)
{
    global $dbConnection;

    $insertStudent = "INSERT INTO students (faculty_number, student_name, faculty, major, course, group_number)
                        VALUES 
                        (:faculty_number, :student_name, :faculty, :major, :course, :group_number)";
    $dbStatement = $dbConnection->prepare($insertStudent) or die("failed to prepare query for inserting student to database");

    return $dbStatement->execute(array(
        ':faculty_number' => isset($student["faculty_number"]) ? $student["faculty_number"] : null,
        ':student_name' => isset($student["student_name"]) ? $student["student_name"] : null,
        ':faculty' => isset($student["faculty"]) ? $student["faculty"] : null,
        ':major' => isset($student["major"]) ? $student["major"] : null,
        ':course' => isset($student["course"]) ? $student["course"] : null,
        ':group_number' => isset($student["group_number"]) ? $student["group_number"] : null,
    ));
}

function insertQuestion($question)
{
    global $dbConnection;

    $insertQuestion = "INSERT INTO questions (question_number, test_id, question_text, correct_answer, wrong_answer_1, 
                                wrong_answer_2, wrong_answer_3, difficulty, response_correct, response_wrong, more_info)
                        VALUES 
                (:question_number, :test_id, :question_text, :correct_answer, :wrong_answer_1, :wrong_answer_2, 
                :wrong_answer_3, :difficulty, :response_correct, :response_wrong, :more_info)";

    $dbStatement = $dbConnection->prepare($insertQuestion) or die("failed to prepare query for inserting question into the database");
    return $dbStatement->execute(array(
        ':question_number' => isset($question["question_number"]) ? $question["question_number"] : null,
        ':test_id' => isset($question["test_id"]) ? $question["test_id"] : null,
        ':question_text' => isset($question["question_text"]) ? $question["question_text"] : null,
        ':correct_answer' => isset($question["correct_answer"]) ? $question["correct_answer"] : null,
        ':wrong_answer_1' => isset($question["wrong_answer_1"]) ? $question["wrong_answer_1"] : null,
        ':wrong_answer_2' => isset($question["wrong_answer_2"]) ? $question["wrong_answer_2"] : null,
        ':wrong_answer_3' => isset($question["wrong_answer_3"]) ? $question["wrong_answer_3"] : null,
        ':difficulty' => isset($question["difficulty"]) ? $question["difficulty"] : null,
        ':response_correct' => isset($question["response_correct"]) ? $question["response_correct"] : null,
        ':response_wrong' => isset($question["response_wrong"]) ? $question["response_wrong"] : null,
        ':more_info' => isset($question["more_info"]) ? $question["more_info"] : null,
    ));
}

function insertTest($test)
{
    global $dbConnection;

    $insertTest = "INSERT INTO tests (id, owner_fn, topic, test_type, results_sum, times_taken)
                VALUES (:id, :owner_fn, :topic, :test_type, :results_sum, :times_taken)";

    $dbStatement = $dbConnection->prepare($insertTest) or die("failed to prepare query for inserting test into database");
    return $dbStatement->execute(array(
        ':id' => isset($test["id"]) ? $test["id"] : null,
        ':owner_fn' => isset($test["owner_fn"]) ? $test["owner_fn"] : null,
        ':topic' => isset($test["topic"]) ? $test["topic"] : null,
        ':test_type' => isset($test["test_type"]) ? $test["test_type"] : null,
        ':results_sum' => isset($test["results_sum"]) ? $test["results_sum"] : null,
        ':times_taken' => isset($test["times_taken"]) ? $test["times_taken"] : null,
    ));
}

function getStudents()
{
    global $dbConnection;

    $selectStudents = "SELECT * FROM students";

    $query = $dbConnection->prepare($selectStudents) or die("failed to prepare query for selecting users from database");

    $query->execute();
    return $query;
}

function getQuestions()
{
    global $dbConnection;

    $selectQuestions = "SELECT * FROM questions";

    $query = $dbConnection->prepare($selectQuestions) or die("failed to prepare query for selecting questions from database");

    $query->execute();
    return $query;
}

function getTests()
{
    global $dbConnection;

    $selectTests = "SELECT * FROM tests";

    $query = $dbConnection->prepare($selectTests) or die("failed to prepare query for selecting tests from database");

    $query->execute();
    return $query;
}


function hasStudent($fn)
{
    global $dbConnection;

    $query = $dbConnection->prepare("SELECT * FROM students WHERE faculty_number = :fn");
    $query->bindParam('fn', $fn, PDO::PARAM_STR);
    $query->execute();


    return $query->fetch(PDO::FETCH_ASSOC);
}

function hasTopic($topic)
{
    global $dbConnection;

    $query = $dbConnection->prepare("SELECT * FROM tests WHERE topic = :topic");
    $query->bindParam('topic', $topic, PDO::PARAM_STR);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);
}

function getQuestionsForTest($test_id)
{
    global $dbConnection;

    $selectQuestions = "SELECT * 
                     FROM questions
                     WHERE test_id = :test_id";

    $query = $dbConnection->prepare($selectQuestions) or die("failed to prepare query for selecting questions from database");
    $query->bindParam('test_id', $test_id, PDO::PARAM_INT);
    $query->execute();

    return $query;
}

function getTestsForStatistics()
{
    global $dbConnection;

    $selectQuery = "SELECT t1.topic AS topic, 
                        (t1.results_sum / t1.times_taken) AS avgB, 
                        (t2.results_sum / t2.times_taken) AS avgD, 
                        (t3.results_sum / t3.times_taken) AS avgA
                    FROM `tests` AS t1  
                    JOIN `tests` AS t2 
                        ON t1.topic=t2.topic AND t1.test_type='before_presentation' AND t2.test_type='during_presentation'
                    JOIN `tests` AS t3 
                        ON t1.topic=t3.topic AND t3.test_type='after_presentation';";

    $query = $dbConnection->prepare($selectQuery) or die("failed to prepare query for selecting questions from database");
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getStudentsTests()
{
    global $dbConnection;

    $selectQuery = "SELECT tt.taker_fn AS fn, t.topic AS topic, t.test_type as test_type, tt.result as result
                    FROM `testtaken` AS tt
                    JOIN `tests` AS t 
                        ON tt.test_id=t.id";
    $query = $dbConnection->prepare($selectQuery) or die("failed to prepare query for selecting questions from database");

    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function insertTestTaken($taker_fn, $topic, $test_type, $result)
{
    global $dbConnection;
    $test_id = getTestIDByTopicAndType($topic, $test_type);

    $insertTest = "INSERT INTO testTaken (test_id, taker_fn, result)
                    VALUES (:test_id, :taker_fn, :result)";

    $dbStatement = $dbConnection->prepare($insertTest) or die("failed to prepare query for inserting test into database");
    return $dbStatement->execute(array(
        ':test_id' => $test_id,
        ':taker_fn' => $taker_fn,
        ':result' => $result,
    ));
}

function getTestIDByTopicAndType($topic, $test_type)
{
    global $dbConnection;

    $selectTestId = "SELECT id
                    FROM tests
                    WHERE topic = :topic AND test_type = :test_type";

    $query = $dbConnection->prepare($selectTestId) or die("failed to prepare query for selecting testID from database");
    $query->bindParam('topic', $topic, PDO::PARAM_STR);
    $query->bindParam('test_type', $test_type, PDO::PARAM_STR);

    $query->execute();
    $test_id = $query->fetch(PDO::FETCH_ASSOC)["id"];
    return $test_id;
}


function getTestByTopic($topic, $test_type)
{
    global $dbConnection;

    $selectTestId = "SELECT id
                    FROM tests
                    WHERE topic = :topic AND test_type = :test_type";

    $query = $dbConnection->prepare($selectTestId) or die("failed to prepare query for selecting testID from database");
    $query->bindParam('topic', $topic, PDO::PARAM_STR);
    $query->bindParam('test_type', $test_type, PDO::PARAM_STR);

    $query->execute();
    $test_id = $query->fetch(PDO::FETCH_ASSOC)["id"];

    return getQuestionsForTest($test_id);
}

function getTestTopicAndType($test_id)
{
    global $dbConnection;

    $selectTest = "SELECT topic, test_type
                    FROM tests
                    WHERE id = :id";

    $query = $dbConnection->prepare($selectTest) or die("failed to prepare query for selecting testID from database");
    $query->bindParam('id', $test_id, PDO::PARAM_INT);

    $query->execute();
    return $query->fetch();
}

function getTestId($owner_fn, $test_type)
{
    global $dbConnection;

    $selectTestId = "SELECT id
                    FROM tests
                    WHERE owner_fn = :owner_fn AND test_type = :test_type";

    $query = $dbConnection->prepare($selectTestId) or die("failed to prepare query for selecting testID from database");
    $query->bindParam('owner_fn', $owner_fn, PDO::PARAM_STR);
    $query->bindParam('test_type', $test_type, PDO::PARAM_STR);

    $query->execute();
    return $query->fetch()["id"];
}

function getAvgResult($topic, $test_type)
{
    $result = getResultData($topic, $test_type);
    return (int)$result["results_sum"] / (int)$result["times_taken"];
}

function getResultData($topic, $test_type)
{
    global $dbConnection;

    $selectAverageTestResult = "SELECT results_sum, times_taken
                                FROM tests
                                WHERE topic = :topic AND test_type = :test_type";

    $query = $dbConnection->prepare($selectAverageTestResult) or die("failed to prepare query for selecting average test result from database");
    $query->bindParam('topic', $topic, PDO::PARAM_STR);
    $query->bindParam('test_type', $test_type, PDO::PARAM_STR);

    $query->execute();
    return $query->fetch();
}

function updateTestResults($topic, $test_type, $result)
{
    global $dbConnection;

    $currentResult = getResultData($topic, $test_type); // OLD: results_sum + times_taken

    $updateTestResult = "UPDATE tests 
                         SET results_sum=:results_sum, times_taken = :times_taken 
                         WHERE topic = :topic AND test_type = :test_type";
    $query = $dbConnection->prepare($updateTestResult) or die("failed to prepare query for updating test results");

    $resultsSum = (int)$currentResult["results_sum"] + $result;
    $query->bindParam('results_sum', $resultsSum, PDO::PARAM_STR);
    $timesTaken = (int)$currentResult["times_taken"] + 1;
    $query->bindParam('times_taken', $timesTaken, PDO::PARAM_STR);
    $query->bindParam('topic', $topic, PDO::PARAM_STR);
    $query->bindParam('test_type', $test_type, PDO::PARAM_STR);

    $query->execute();
}

function updatePresence($fn, $topic)
{
    global $dbConnection;

    $update = "UPDATE presence 
                         SET present='yes'
                         WHERE topic = :topic AND fn = :fn";
    $query = $dbConnection->prepare($update) or die("failed to prepare query for updating presence");

    $query->bindParam('topic', $topic, PDO::PARAM_STR);
    $query->bindParam('fn', $fn, PDO::PARAM_STR);

    $query->execute();
}

function checkValidLogin($username, $password)
{
    global $dbConnection;

    $query = $dbConnection->prepare("SELECT * FROM admins WHERE adminName = :adminName");
    $query->bindParam('adminName', $username, PDO::PARAM_STR);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result && password_verify($password, $result['pass']);
}

function isDataBaseConfigured()
{
    global $conn;

    $sql = 'SELECT * FROM tasks';
    $stmt = $conn->prepare($sql) or die("failed!");
    $stmt->execute();

    $dir = "exports";

    $allTasks = $stmt->fetchAll();
    if (count($allTasks) > 0 && glob("$dir/*.csv")) {
        return true;
    }
    return false;
}

function rateTopic($owner_fn, $topic, $topic_name, $rating)
{

    global $dbConnection;

    $rate = "INSERT INTO topicrating (owner_fn, topic, topic_name, rating)
                    VALUES (:owner_fn, :topic, :topic_name, :rating)";

    $dbStatement = $dbConnection->prepare($rate) or die("Failed to prepare DBStatement for rating referat presentation");
    return $dbStatement->execute(array(
        ':owner_fn' => $owner_fn,
        ':topic' => $topic,
        ':topic_name' => $topic_name,
        ':rating' => $rating
    ));
}

function rateTopicByNumber($topic, $rating)
{
    global $dbConnection;

    $selectRating = "SELECT rating
                    FROM topicrating
                    WHERE topic = :topic";

    $query = $dbConnection->prepare($selectRating) or die("failed to prepare query for selecting rating from database");
    $query->bindParam('topic', $topic, PDO::PARAM_INT);

    $query->execute();
    $currentRating = $query->fetch(PDO::FETCH_ASSOC)["rating"];

    $rate = "UPDATE topicrating 
                SET rating=:rating
                WHERE topic=:topic;";

    $dbStatement = $dbConnection->prepare($rate) or die("Failed to prepare DBStatement for rating referat presentation");
    return $dbStatement->execute(array(
        ':rating' => $currentRating == false ? $rating : $currentRating + $rating,
        ':topic' => $topic
    ));
}

function getRating()
{
    global $dbConnection;

    $selectTests = "SELECT * FROM topicrating";

    $query = $dbConnection->prepare($selectTests) or die("failed to prepare query for selecting topicratings from database");

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function initPresenceDB()
{
    global $dbConnection;

    $fns = "SELECT faculty_number FROM students";
    $queryFn = $dbConnection->prepare($fns) or die("failed to prepare query for selecting faculty numbers from sudents");
    $queryFn->execute();
    $fnsArr = $queryFn->fetchAll(PDO::FETCH_ASSOC);

    $topics = "SELECT DISTINCT topic FROM tests";
    $queryTopic = $dbConnection->prepare($topics) or die("failed to prepare query for selecting topics from tests");
    $queryTopic->execute();
    $topicsArr = $queryTopic->fetchAll(PDO::FETCH_ASSOC);

    foreach ($fnsArr as $fn) {
        foreach ($topicsArr as $topic) {
            $insertPresence = "  INSERT INTO presence (fn, topic, present)
                                    VALUES 
                                    (:fn, :topic, :present)";
            $dbStatement = $dbConnection->prepare($insertPresence) or die("failed to prepare query for inserting student to database");

            $topicOwner = "SELECT DISTINCT topic FROM tests WHERE owner_fn = :owner_fn";
            $queryOwner = $dbConnection->prepare($topicOwner) or die("failed to prepare query for selecting topics from tests");
            $queryOwner->bindParam('owner_fn', $fn["faculty_number"], PDO::PARAM_STR);
            $queryOwner->execute();
            $ownerArr = $queryOwner->fetchAll(PDO::FETCH_ASSOC);

            $present = (strcmp($ownerArr[0]['topic'], $topic["topic"]) === 0) ? "yes" : "no";

            $dbStatement->execute(array(
                ':fn' => $fn["faculty_number"],
                ':topic' => $topic["topic"],
                ':present' => $present,
            ));
        }
    }
}

function getPresence($fn)
{
    global $dbConnection;

    $select = "SELECT topic FROM presence
                    WHERE present='yes' AND fn = :fn";

    $query = $dbConnection->prepare($select) or die("failed to prepare query for selecting presence from database");

    $query->bindParam('fn', $fn, PDO::PARAM_STR);

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPresenceNumber($fn)
{
    global $dbConnection;

    $select = "SELECT count(*) AS count FROM `presence` WHERE fn=:fn";

    $query = $dbConnection->prepare($select) or die("failed to prepare query for selecting all presence number from database");

    $query->bindParam('fn', $fn, PDO::PARAM_STR);

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
