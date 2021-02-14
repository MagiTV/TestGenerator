(function() {
    var studentButton = document.getElementById('studentButton');
    studentButton.addEventListener('click', loginStudent);

    var rateButton = document.getElementById('rateTopic');
    rateButton.addEventListener('click', rateTopic);
})();

function loginStudent(event) {
    event.preventDefault();
    window.location = 'choose_test.html';
};

function rateTopic(event) {
    event.preventDefault();
    window.location = 'rate.html';
};