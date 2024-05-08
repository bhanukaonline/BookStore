// JavaScript for slider functionality
let slideIndex = 1;
showSlides(slideIndex);

function nextSlide() {
    showSlides(slideIndex += 1);
}

function prevSlide() {
    showSlides(slideIndex -= 1);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("slide");
    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex - 1].style.display = "block";

    // Set interval to automatically show slides every 3 seconds (adjust as needed)
    clearInterval(autoSlide); // Clear any existing interval
    autoSlide = setInterval(nextSlide, 1000);
}

// Set interval initially to start automatic sliding
let autoSlide = setInterval(nextSlide, 1000);
