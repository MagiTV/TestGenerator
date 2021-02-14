(function() {
    generateRatings();
})();

function generateRatings() {

    const settings = {
        method: 'GET',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }
    };

    ajax('ratings.php', settings);
}

function generate() {
    var section = document.getElementById("topicRating")
    for (var ratingsNumber = 0; ratingsNumber < ratings.length; ratingsNumber++) {
        var ratingText = document.createElement("p");
        var text = "<strong>Тема:</strong> " + ratings[ratingsNumber]["topic_name"] + "<br/><strong>Представил:</strong> " + ratings[ratingsNumber]["owner_fn"] + "<br/><strong>Рейтинг:</strong> " + ratings[ratingsNumber]["rating"];
        ratingText.innerHTML = text;
        section.appendChild(ratingText);
    }
}

function ajax(url, settings) {
    fetch(url, settings)
        .then(response => response.json())
        .then(data => loadGet(data))
        .catch(error => console.log(error));
};

function loadGet(data) {
    if (data.success) {
        ratings = data.ratings;
        generate();
    } else {
        console.log("error load get");
    }
}