(function() {
    generateStatistics();
})();

function generateStatistics() {
    const settings = {
        method: 'GET',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }
    };
    ajaxGet('statistics.php', settings);
}

function ajaxGet(url, settings) {
    fetch(url, settings)
        .then(response => response.json())
        .then(data => loadGet(data))
        .catch(error => console.log(error));
};

function loadGet(data) {
    if (data.success) {
        topics = data.topics;
        generateByTopic();
        people = data.people;
        generateByFN();
    } else {
        console.log("error load get");
    }
}

function generateByTopic() {
    var bigSection = document.getElementById("byTopic");

    for (var topicsNumber = 0; topicsNumber < topics.length; topicsNumber++) {
        var section = document.createElement("SECTION");
        var topicId = "topic" + topicsNumber;
        section.setAttribute("id", topicId);

        bigSection.appendChild(section);

        var topicText = document.createElement("H1");
        var text = document.createTextNode("Тема: " + topics[topicsNumber]["topic"]);
        topicText.appendChild(text);
        section.appendChild(topicText)

        var testTypesText = ['Преди презентация: ', 'По време на презентация: ', 'След презентация: '];
        var results = [topics[topicsNumber]["avgB"], topics[topicsNumber]["avgD"], topics[topicsNumber]["avgA"]];

        for (var i = 0; i < 3; i++) {
            if (results[i] != null) {
                var testTypeText = document.createElement("H3");
                var text = document.createTextNode(testTypesText[i]);
                testTypeText.appendChild(text);
                section.appendChild(testTypeText);

                var result = document.createElement('p');
                result.setAttribute('class', 'avgResult');
                result.innerHTML = "Среден резултат: " + Math.floor(results[i]) + "%";
                section.appendChild(result);
            }
        }
    }
}

function generateByFN() {
    var bigSection = document.getElementById("byFN");

    for (var test = 0; test < people.length; test++) {
        var fn = people[test]['fn'];
        var topic = people[test]['topic'];
        var testType = people[test]['test_type'];
        var result = people[test]['result'];

        var sectionFN = document.getElementById("" + fn);
        if (!sectionFN) {
            sectionFN = document.createElement("SECTION");
            sectionFN.setAttribute("id", "" + fn);
            bigSection.appendChild(sectionFN);

            var fnText = document.createElement("H1");
            var text = document.createTextNode("ФН: " + fn);
            fnText.appendChild(text);
            sectionFN.appendChild(fnText);
        }
        console.log(sectionFN);

        var sectionTopic = document.getElementById("" + fn + "_" + topic);
        if (!sectionTopic) {
            sectionTopic = document.createElement("SECTION");
            sectionTopic.setAttribute("id", "" + fn + "_" + topic);
            sectionFN.appendChild(sectionTopic);

            var topicText = document.createElement("H3");
            var text = document.createTextNode("Тема: " + topic);
            topicText.appendChild(text);
            sectionTopic.appendChild(topicText);
        }

        var testWhen = document.createElement("p");
        var text = "";

        switch (testType) {
            case 'before_presentation':
                text = document.createTextNode("Преди презентация: " + result + "%");
                break;
            case 'during_presentation':
                text = document.createTextNode("По време на презентация: " + result + "%");
                break;
            case 'after_presentation':
                text = document.createTextNode("След презентация: " + result + "%");
                break;
        }
        testWhen.appendChild(text);
        sectionTopic.appendChild(testWhen);
    }
}

function statisticsByTopic() {
    var bigSection = document.getElementById("byTopic");
    bigSection.hidden = !bigSection.hidden;
}

function statisticsByFN() {
    var bigSection = document.getElementById("byFN");
    bigSection.hidden = !bigSection.hidden;
}