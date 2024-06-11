import './bootstrap';

window.addEventListener('scroll', function() {
    var categoryContainer = document.getElementById('categoryContainer');
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // Adjust the scroll position to trigger the fix
    if (scrollTop > 1050) {
        categoryContainer.classList.add('fixed', 'top-9', 'bg-primary', 'right-0', 'z-50', 'w-full', 'px-6');
    } else {
        categoryContainer.classList.remove('fixed', 'top-0', 'left-0', 'right-0', 'z-50', 'px-6');
    }
});

var swiper = new Swiper(".mySwiper", {
    cssMode: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    pagination: {
        el: ".swiper-pagination",
    },
    mousewheel: true,
    keyboard: true,
    spaceBetween: 20,

    centeredSlides: true,

    slidesPerView: 1.2,
});