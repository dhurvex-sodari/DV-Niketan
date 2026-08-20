// assets/js/main.js - DV Niketan Frontend Dynamic Interactions

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navMenu = document.querySelector('.nav-menu');
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('show');
            const icon = mobileToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-list');
                icon.classList.toggle('bi-x-lg');
            }
        });
    }

    // 2. Hero Slider Functionality
    const heroSlides = document.querySelectorAll('.hero-slide');
    const prevBtn = document.querySelector('.hero-prev');
    const nextBtn = document.querySelector('.hero-next');
    let currentSlide = 0;
    let slideInterval = null;

    function showSlide(index) {
        if (!heroSlides.length) return;
        heroSlides.forEach(slide => slide.classList.remove('active'));
        currentSlide = (index + heroSlides.length) % heroSlides.length;
        heroSlides[currentSlide].classList.add('active');
    }

    if (heroSlides.length > 1) {
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                showSlide(currentSlide + 1);
                resetInterval();
            });
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                showSlide(currentSlide - 1);
                resetInterval();
            });
        }
        function startInterval() {
            slideInterval = setInterval(() => showSlide(currentSlide + 1), 6000);
        }
        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }
        startInterval();
    }

    // 3. Stats Counter Animation
    const statNumbers = document.querySelectorAll('.stat-number');
    if (statNumbers.length) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const text = el.innerText.trim();
                    const match = text.match(/(\d+)/);
                    if (match) {
                        const target = parseInt(match[1], 10);
                        const suffix = text.replace(match[1], '');
                        let count = 0;
                        const step = Math.max(1, Math.floor(target / 40));
                        const timer = setInterval(() => {
                            count += step;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                            el.innerText = count + suffix;
                        }, 30);
                    }
                    obs.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(num => observer.observe(num));
    }

    // 4. Gallery Lightbox
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxClose = document.querySelector('.lightbox-close');

    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', () => {
            const img = item.querySelector('img');
            const caption = item.getAttribute('data-caption') || '';
            if (lightboxModal && lightboxImg) {
                lightboxImg.src = img.src;
                if (lightboxCaption) lightboxCaption.innerText = caption;
                lightboxModal.classList.add('active');
            }
        });
    });

    if (lightboxClose && lightboxModal) {
        lightboxClose.addEventListener('click', () => {
            lightboxModal.classList.remove('active');
        });
        lightboxModal.addEventListener('click', (e) => {
            if (e.target === lightboxModal) {
                lightboxModal.classList.remove('active');
            }
        });
    }
});
