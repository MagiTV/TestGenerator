(function () {
    var button = document.getElementById('login');
    button.addEventListener('click', login);
})();

function login(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const admin = {
        username,
        password
    };

    const settings = {
        method: 'POST',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: `data=${JSON.stringify(admin)}`
    };

    ajax('login.php', settings, 'configuration.html');
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