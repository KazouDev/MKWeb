var rating = parseFloat(document.querySelector('.coups-coeur__note h1').textContent);

var stars = document.querySelectorAll('.coups-coeur__note i');
console.log(rating);

for (var i = 0; i < stars.length; i++) {
    var starId = parseInt(stars[i].id.replace('star', ''));

    if (starId <= rating) {
        stars[i].style.color = '#FFD33C';
    } else {
        stars[i].style.color = '#878787';
    }
}
