(function() {
    var button = document.getElementById('rateReferat');
    button.addEventListener('click', ratePresentation);
})();

function ratePresentation(event) {
    event.preventDefault();

    const topicNumber = document.getElementById('topicNumber').value;
    const rating = document.getElementById('rating').value;

    const rate = {
        topicNumber,
        rating
    };

    const settings = {
        method: 'POST',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: `data=${JSON.stringify(rate)}`
    };

    ajax('rate.php', settings, 'rate.html');
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