/**
 * resources/js/app.js
 *
 * Modern UI Animations Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Page Load Experience (Fade-in)
    setTimeout(() => {
        document.body.classList.add('page-loaded');
    }, 50); // Small delay ensures CSS is ready

    // 2. Scroll Reveals (Intersection Observer)
    const revealElements = document.querySelectorAll('.reveal-up, .reveal-down, .reveal-left, .reveal-right, .reveal-zoom');
    
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target); // Trigger only once
                }
            });
        }, {
            root: null,
            rootMargin: '0px 0px -50px 0px', // Trigger slightly before it comes into view
            threshold: 0.1
        });

        revealElements.forEach(el => revealObserver.observe(el));
    } else {
        // Fallback for older browsers
        revealElements.forEach(el => el.classList.add('is-revealed'));
    }

    // 3. Counter Animations
    const counters = document.querySelectorAll('.counter-animate');
    
    if ('IntersectionObserver' in window && counters.length > 0) {
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => counterObserver.observe(counter));
    } else {
        counters.forEach(counter => animateCounter(counter));
    }

    function animateCounter(el) {
        const targetText = el.getAttribute('data-target');
        const target = parseInt(targetText || el.innerText.replace(/[^0-9]/g, ''), 10);
        if (isNaN(target)) return;
        
        const prefix = el.getAttribute('data-prefix') || '';
        const suffix = el.getAttribute('data-suffix') || '';
        
        const duration = 1500; // ms
        const stepTime = Math.abs(Math.floor(duration / target)) || 10;
        let current = 0;
        
        // Fast counting for large numbers
        const increment = target > 100 ? Math.ceil(target / 50) : 1;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                el.innerText = prefix + target.toLocaleString() + suffix;
                clearInterval(timer);
            } else {
                el.innerText = prefix + current.toLocaleString() + suffix;
            }
        }, stepTime);
    }

    // 4. Navbar Scroll Animation
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        }, { passive: true });
    }

    // 5. Parallax Effects (Optimized with requestAnimationFrame)
    // Only apply on non-mobile devices
    if (window.innerWidth > 768) {
        const parallaxElements = document.querySelectorAll('.parallax-bg');
        if (parallaxElements.length > 0) {
            let ticking = false;
            
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        const scrolled = window.scrollY;
                        parallaxElements.forEach(el => {
                            // Simple parallax logic: move background slightly slower than scroll
                            const speed = el.getAttribute('data-parallax-speed') || 0.4;
                            if (el.tagName.toLowerCase() === 'img') {
                                el.style.transform = `translateY(${scrolled * speed}px)`;
                            } else {
                                el.style.backgroundPositionY = `${scrolled * speed}px`;
                            }
                        });
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });
        }
    }

    // 6. Skeleton Loaders
    // Simulate dynamic loading skeleton for cards (companions, search results, dashboards)
    const skeletons = document.querySelectorAll('.skeleton-layer');
    if (skeletons.length > 0) {
        // Remove skeleton layer after a short delay or when everything is loaded
        window.addEventListener('load', () => {
            setTimeout(() => {
                skeletons.forEach(el => {
                    el.classList.add('skeleton-loaded');
                    // Remove the class completely after transition
                    setTimeout(() => {
                        el.classList.remove('skeleton-layer', 'skeleton-loaded');
                    }, 500);
                });
            }, 800); // 800ms artificial delay to show off the premium skeleton effect
        });
    }

    // 7. Advanced Premium Motion (Tilt, Magnetic)
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isMobile = window.innerWidth < 768;
    const isAdmin = document.body.classList.contains('admin-dashboard');

    if (!prefersReducedMotion && !isMobile && !isAdmin) {
        // 7a. 3D Tilt Effect
        const tiltCards = document.querySelectorAll('.tilt-3d-card');
        tiltCards.forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const xPct = (x / rect.width) - 0.5;
                const yPct = (y / rect.height) - 0.5;
                
                // Max tilt is 5deg (0.5 * 10)
                const tiltX = yPct * -10; 
                const tiltY = xPct * 10;
                
                card.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) scale3d(1.02, 1.02, 1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
            });
            
            card.addEventListener('mouseenter', () => {
                card.style.transition = 'transform 0.1s ease';
            });
        });

        // 7b. Magnetic Buttons
        const magneticBtns = document.querySelectorAll('.magnetic-btn');
        magneticBtns.forEach(btn => {
            btn.addEventListener('mousemove', e => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                // Max movement ~15px
                const moveX = (x / rect.width) * 30; 
                const moveY = (y / rect.height) * 30;
                
                btn.style.transform = `translate(${moveX}px, ${moveY}px)`;
            });
            
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translate(0px, 0px)';
                btn.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
            });
            
            btn.addEventListener('mouseenter', () => {
                btn.style.transition = 'none';
            });
        });
    }
});
