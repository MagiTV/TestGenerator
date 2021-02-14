(function() {
    generateTest();

    var button = document.getElementById('submitTest');
    button.addEventListener('click', submitTest);

})();

var questions;

function generateTest() {

    const settings = {
        method: 'GET',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }
    };

    ajaxGet('test.php', settings);
}

function ajaxGet(url, settings) {
    fetch(url, settings)
        .then(response => response.json())
        .then(data => loadGet(data))
        .catch(error => console.log(error));
};

function loadGet(data) {
    if (data.success) {
        questions = data.questions[0];
        topic = data.topic;
        generateQuestions();
    } else {
        console.log("error load get");
        console.log(data);
    }
};

function generateQuestions() {
    var topicTest = document.createElement("H1");
    var text = document.createTextNode("Tест за тема: " + topic);
    topicTest.appendChild(text);

    testSection = document.getElementById("myTest");
    testSection.appendChild(topicTest);

    for (var questionsNumber = 0; questionsNumber < questions.length; questionsNumber++) {
        var section = document.createElement("SECTION");
        var questionId = "question" + questionsNumber;
        section.setAttribute("id", questionId);
        testSection.appendChild(section);

        var questionText = document.createElement("H3");
        var text = document.createTextNode(questions[questionsNumber].question_text);
        questionText.appendChild(text);
        document.getElementById(questionId).appendChild(questionText);

        // Shuffle the answers
        var allAnswers = [questions[questionsNumber].correct_answer, questions[questionsNumber].wrong_answer_1, questions[questionsNumber].wrong_answer_2, questions[questionsNumber].wrong_answer_3];
        shuffle(allAnswers);

        // Create radio buttons
        for (var radioNumber = 0; radioNumber < 4; radioNumber++) {
            var answer = document.createElement("SECTION");
            section.appendChild(answer);

            var radio = document.createElement('input');
            radio.setAttribute('type', 'radio');
            radioId = "" + questionsNumber + radioNumber;
            radio.setAttribute('id', radioId);
            radio.setAttribute('name', "" + questionsNumber);
            radio.setAttribute('value', allAnswers[radioNumber]);
            answer.appendChild(radio)

            var label = document.createElement('label');
            label.setAttribute('for', radioId)
            label.innerHTML = allAnswers[radioNumber];
            answer.appendChild(label);
        }
    }
};

function shuffle(array) {
    var currentIndex = array.length,
        temporaryValue, randomIndex;

    while (0 !== currentIndex) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }
    return array;
}

function checkAnswers() {
    var correctAnswers = 0;

    for (var questionsNumber = 0; questionsNumber < questions.length; questionsNumber++) {
        var answers = document.getElementsByName("" + questionsNumber);
        var correctAnswer = questions[questionsNumber].correct_answer;
        var section = document.getElementById("question" + questionsNumber);

        for (var answersNumber = 0; answersNumber < answers.length; answersNumber++) {
            if (answers[answersNumber].checked) {
                if (answers[answersNumber].value == correctAnswer) {
                    var message = document.createElement('p');
                    message.setAttribute('class', 'correct');
                    message.innerHTML = questions[questionsNumber].response_correct;
                    section.appendChild(message)

                    correctAnswers++;
                } else {
                    var message = document.createElement('p');
                    message.setAttribute('class', 'wrong');
                    message.innerHTML = questions[questionsNumber].response_wrong;
                    section.appendChild(message);

                    var more_info = document.createElement('p');
                    more_info.setAttribute('class', 'more');
                    more_info.innerHTML = "За повече информация виж: " + questions[questionsNumber].more_info;
                    section.appendChild(more_info);
                }
            }
        }
    }
    return correctAnswers;
}

function submitTest(event) {
    event.preventDefault();

    correctAnswers = checkAnswers();

    if (questions.length != 0) {
        var submitButton = document.getElementById("submitTest");
        submitButton.remove();

        var messageResult = document.createElement('p');
        messageResult.setAttribute('class', 'result');
        messageResult.innerHTML = "Твоят резултат е " + Math.floor(100 * correctAnswers / questions.length) + "%";
        testSection = document.getElementById("myTest");
        testSection.appendChild(messageResult);

        const result = {
            correctAnswers: correctAnswers,
            questionsNumber: questions.length,
        };

        const settings = {
            method: 'POST',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: `data=${JSON.stringify(result)}`
        };

        ajaxPost('test.php', settings);
    }
};

function ajaxPost(url, settings) {
    fetch(url, settings)
        .then(response => response.json())
        .then(data => loadPost(data))
        .catch(error => console.log(error));
};

function loadPost(data) {
    if (!data.success) {
        console.log("error in loadPost");
    }
};