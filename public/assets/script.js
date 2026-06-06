const carousel = document.getElementById('packageCarousel');
const slides = carousel ? carousel.querySelectorAll('.carousel-slide') : [];
const dots = document.querySelectorAll('.dot');
const carouselContainer = document.querySelector('.carousel-container');
let currentSlide = 0;
let autoplayTimer = null;

function showSlide(index) {
    if (!carousel || slides.length === 0) return;
    currentSlide = (index + slides.length) % slides.length;
    carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
    dots.forEach((dot, i) => dot.classList.toggle('active', i === currentSlide));
}

function startAutoplay() {
    stopAutoplay();
    autoplayTimer = window.setInterval(() => showSlide(currentSlide + 1), 4500);
}

function stopAutoplay() {
    if (autoplayTimer) {
        clearInterval(autoplayTimer);
        autoplayTimer = null;
    }
}

// Dots
dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
        showSlide(i);
        startAutoplay();
    });
});

// ── Drag Souris + Swipe Tactile ───────────────────────────────────────────────
if (carousel && slides.length > 0) {
    showSlide(0);
    startAutoplay();

    const SWIPE_THRESHOLD = 50;
    let dragStartX = 0;
    let dragStartY = 0;
    let isDragging = false;

    // --- SOURIS ---
    carouselContainer.addEventListener('mousedown', (e) => {
        // Ignorer les clics sur les boutons/liens
        if (e.target.closest('a, button')) return;
        isDragging = true;
        dragStartX = e.clientX;
        stopAutoplay();
        // Désactiver la transition pour le drag fluide
        carousel.style.transition = 'none';
        e.preventDefault();
    });

    // Attacher mousemove et mouseup sur document pour ne pas perdre le drag
    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const diff = e.clientX - dragStartX;
        carousel.style.transform = `translateX(calc(-${currentSlide * 100}% + ${diff}px))`;
    });

    document.addEventListener('mouseup', (e) => {
        if (!isDragging) return;
        isDragging = false;
        // Réactiver la transition pour l'animation snap
        carousel.style.transition = 'transform 0.55s cubic-bezier(0.22, 1, 0.36, 1)';
        const diff = e.clientX - dragStartX;
        if (Math.abs(diff) > SWIPE_THRESHOLD) {
            showSlide(diff < 0 ? currentSlide + 1 : currentSlide - 1);
        } else {
            showSlide(currentSlide); // snap back
        }
        startAutoplay();
    });

    // Empêcher le drag natif des images
    carouselContainer.addEventListener('dragstart', (e) => e.preventDefault());

    // --- TACTILE ---
    carouselContainer.addEventListener('touchstart', (e) => {
        dragStartX = e.touches[0].clientX;
        dragStartY = e.touches[0].clientY;
        stopAutoplay();
        carousel.style.transition = 'none';
    }, { passive: true });

    carouselContainer.addEventListener('touchmove', (e) => {
        const diffX = e.touches[0].clientX - dragStartX;
        const diffY = e.touches[0].clientY - dragStartY;
        // Si le mouvement est principalement horizontal → on prend le contrôle
        if (Math.abs(diffX) > Math.abs(diffY)) {
            e.preventDefault(); // empêche le scroll de la page
            carousel.style.transform = `translateX(calc(-${currentSlide * 100}% + ${diffX}px))`;
        }
    }, { passive: false });

    carouselContainer.addEventListener('touchend', (e) => {
        carousel.style.transition = 'transform 0.55s cubic-bezier(0.22, 1, 0.36, 1)';
        const diff = e.changedTouches[0].clientX - dragStartX;
        if (Math.abs(diff) > SWIPE_THRESHOLD) {
            showSlide(diff < 0 ? currentSlide + 1 : currentSlide - 1);
        } else {
            showSlide(currentSlide);
        }
        startAutoplay();
    }, { passive: true });
}

// ── Lead capture : pré-remplissage du formulaire ──────────────────────────────
document.querySelectorAll('[data-course]').forEach((link) => {
    link.addEventListener('click', () => {
        const course = link.getAttribute('data-course');
        const select = document.getElementById('courseSelect');
        if (select && course) select.value = course;
    });
});

// ── Animations au scroll (reveal) ────────────────────────────────────────────
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ── Category Sliders (blocs de formations) ────────────────────
function initCategorySliders() {
    // N'activer que sur mobile
    if (window.innerWidth > 768) return;

    document.querySelectorAll('.category-slider-container').forEach(container => {
        const track = container.querySelector('.category-slider-track');
        const dotsContainer = container.querySelector('.category-slider-dots');
        const cards = Array.from(track.querySelectorAll('.training-card'));

        if (!cards.length) return;

        let currentIndex = 0;
        const total = cards.length;
        const GAP = 12; // doit correspondre au gap CSS

        // ── Générer les dots ──────────────────────────────────
        dotsContainer.innerHTML = '';
        cards.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.className = 'cat-dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('aria-label', 'Slide ' + (i + 1));
            dot.addEventListener('click', () => goTo(i));
            dotsContainer.appendChild(dot);
        });

        // ── Aller à un slide ──────────────────────────────────
        function goTo(index) {
            currentIndex = Math.max(0, Math.min(index, total - 1));
            const cardWidth = cards[0].offsetWidth + GAP;
            track.style.transition = 'transform 0.45s cubic-bezier(0.22, 1, 0.36, 1)';
            track.style.transform = 'translateX(-' + (currentIndex * cardWidth) + 'px)';
            dotsContainer.querySelectorAll('.cat-dot').forEach((d, i) => {
                d.classList.toggle('active', i === currentIndex);
            });
        }

        // ── Touch (swipe) ─────────────────────────────────────
        let startX = 0, startY = 0, isDragging = false, moved = false;

        track.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isDragging = false;
            moved = false;
            track.style.transition = 'none';
        }, { passive: true });

        track.addEventListener('touchmove', e => {
            const dx = e.touches[0].clientX - startX;
            const dy = e.touches[0].clientY - startY;

            // Décider si le geste est horizontal ou vertical
            if (!moved) {
                if (Math.abs(dx) > Math.abs(dy) + 3) {
                    isDragging = true;
                } else if (Math.abs(dy) > Math.abs(dx) + 3) {
                    isDragging = false;
                }
                moved = true;
            }

            if (isDragging) {
                e.preventDefault();
                const cardWidth = cards[0].offsetWidth + GAP;
                track.style.transform = 'translateX(' + (-(currentIndex * cardWidth) + dx) + 'px)';
            }
        }, { passive: false });

        track.addEventListener('touchend', e => {
            const dx = e.changedTouches[0].clientX - startX;
            if (isDragging) {
                if (dx < -50) goTo(currentIndex + 1);
                else if (dx > 50) goTo(currentIndex - 1);
                else goTo(currentIndex);
            }
            isDragging = false;
        }, { passive: true });

        // ── Drag souris ───────────────────────────────────────
        let mouseStartX = 0, mouseDragging = false;

        track.addEventListener('mousedown', e => {
            if (e.target.closest('a, button')) return;
            mouseDragging = true;
            mouseStartX = e.clientX;
            track.style.transition = 'none';
            e.preventDefault();
        });

        document.addEventListener('mousemove', e => {
            if (!mouseDragging) return;
            const dx = e.clientX - mouseStartX;
            const cardWidth = cards[0].offsetWidth + GAP;
            track.style.transform = 'translateX(' + (-(currentIndex * cardWidth) + dx) + 'px)';
        });

        document.addEventListener('mouseup', e => {
            if (!mouseDragging) return;
            const dx = e.clientX - mouseStartX;
            if (dx < -50) goTo(currentIndex + 1);
            else if (dx > 50) goTo(currentIndex - 1);
            else goTo(currentIndex);
            mouseDragging = false;
        });

        track.addEventListener('dragstart', e => e.preventDefault());

        // Initialiser au premier slide
        goTo(0);
    });
}

// Lancer au chargement
initCategorySliders();

// Relancer si la fenêtre est redimensionnée (passage mobile ↔ desktop)
window.addEventListener('resize', () => {
    document.querySelectorAll('.category-slider-track').forEach(track => {
        track.style.transform = '';
        track.style.transition = '';
    });
    initCategorySliders();
});

// ── Dynamic Month Filtering on Homepage ─────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const cards = document.querySelectorAll('.training-card');
    const groups = document.querySelectorAll('.training-group');

    if (filterTabs.length > 0 && cards.length > 0) {
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Toggle active tab class
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const selectedMonth = this.getAttribute('data-month');

                // Filter cards
                cards.forEach(card => {
                    const cardMonth = card.getAttribute('data-month');
                    if (selectedMonth === 'all' || cardMonth === selectedMonth) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Adjust group headers
                groups.forEach(group => {
                    const totalGroupCards = group.querySelectorAll('.training-card');
                    let hasVisible = false;
                    
                    totalGroupCards.forEach(c => {
                        if (c.style.display !== 'none') {
                            hasVisible = true;
                        }
                    });

                    if (hasVisible) {
                        group.style.display = '';
                    } else {
                        group.style.display = 'none';
                    }
                });
                
                // Re-initialize category sliders for mobile if visible
                initCategorySliders();
            });
        });
    }
});

// Dynamic Hamburger Menu Creation and Behavior
document.addEventListener('DOMContentLoaded', function() {
    const headerBar = document.querySelector('.header-bar');
    const nav = document.querySelector('.nav');

    if (headerBar && nav) {
        // Create hamburger button
        const menuToggle = document.createElement('button');
        menuToggle.className = 'menu-toggle';
        menuToggle.id = 'menuToggle';
        menuToggle.setAttribute('aria-label', 'Menu');
        
        menuToggle.innerHTML = `
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        `;
        
        // Add ID to nav if missing
        nav.id = 'siteNav';
        
        // Insert toggle before nav
        headerBar.insertBefore(menuToggle, nav);
        
        // Toggle action
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            menuToggle.classList.toggle('active');
            nav.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!nav.contains(e.target) && !menuToggle.contains(e.target)) {
                menuToggle.classList.remove('active');
                nav.classList.remove('active');
            }
        });
        
        // Close menu when clicking a link
        nav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menuToggle.classList.remove('active');
                nav.classList.remove('active');
            });
        });
    }
});

