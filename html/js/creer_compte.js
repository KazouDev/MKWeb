
let slideIndex = 0;
let slides = document.getElementsByClassName("slide");
let slideInterval;

function showSlides(n) {
    if (n >= slides.length) { slideIndex = 0 }
    if (n < 0) { slideIndex = slides.length - 1 }
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex].style.display = "block";
}

function nextSlide() {
    showSlides(++slideIndex);
}

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function startSlideShow() {
    slideInterval = setInterval(nextSlide, 5000); 
}

function stopSlideShow() {
    clearInterval(slideInterval);
}

document.addEventListener("DOMContentLoaded", function() {
    showSlides(slideIndex);
    startSlideShow();
    
    document.querySelector('.prev').addEventListener('click', function() {
        stopSlideShow();
        plusSlides(-1);
        startSlideShow();
    });

    document.querySelector('.next').addEventListener('click', function() {
        stopSlideShow();
        plusSlides(1);
        startSlideShow();
    });
});

