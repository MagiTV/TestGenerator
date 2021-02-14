(function() {
    var button = document.getElementById('createTest');
    button.addEventListener('click', createTest);
})();

function createTest(event) {
    event.preventDefault();

    const facultyNumber = document.getElementById('facultyNumber').value;
    const topicNumber = document.getElementById('topicNumber').value;
    const testType = document.getElementById('testType').value;

    const test = {
        facultyNumber,
        topicNumber,
        testType
    };

    const settings = {
        method: 'POST',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: `data=${JSON.stringify(test)}`
    };


    ajax('choose_test.php', settings, 'test.html');
}

function ajax(url, settings, successUrl) {
    fetch(url, settings)
        .then(response => response.json())
        .then(data => load(data, successUrl))
        .catch(error => console.log(error));
};

function load(data, url) {
    if (data.success) {
        window.location = url;
    } else {
        const errors = document.getElementById('errors');
        errors.innerHTML = data.errors;
    }
};