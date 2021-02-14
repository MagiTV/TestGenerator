(function() {
    var button = document.getElementById('presenceSubmit');
    button.addEventListener('click', generatePresence);
})();

function generatePresence(event) {
    event.preventDefault();

    const errors = document.getElementById('errors');
    errors.innerHTML = "";

    fn = document.getElementById("fn").value;

    const data = {
        fn: fn,
    }


    const settings = {
        method: 'POST',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: `data=${JSON.stringify(data)}`
    };

    ajax('presence.php', settings);
}


function generate() {
    var bigSection = document.getElementById("present");

    var fnText = document.createElement("H1");
    var text = document.createTextNode("ФН " + fn + ": ");
    fnText.appendChild(text);

    var presenceText = document.createElement("p");
    var text = "Присъствал на " + presence.length + "/" + presenceCount + " презентации: [";

    for (var i = 0; i < presence.length; i++) {
        text += presence[i]['topic'];
        if (i != presence.length - 1) {
            text += ', ';
        } else {
            text += ']';
        }
        presenceText.innerHTML = text;
        bigSection.prepend(presenceText);
    }
    bigSection.prepend(fnText);

}

function ajax(url, settings) {
    fetch(url, settings)
        .then(response => response.json())
        .then(data => load(data))
        .catch(error => console.log(error));
};

function load(data) {
    if (data.success) {
        presence = data.presence;
        presenceCount = data.presenceCount[0]["count"];
        generate(presence);
    } else {
        const errors = document.getElementById('errors');
        errors.innerHTML = data.errors;
    }
}