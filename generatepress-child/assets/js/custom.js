/**
 * Custom JavaScript for Tech Blog
 * Hero Banner Animations & Interactions
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        const typingElement = document.querySelector('.typing-text');
        if (typingElement) {
            const words = [
                'Technology',
                'Artificial Intelligence',
                'Web Development',
                'Machine Learning',
                'Cloud Computing',
                'Cybersecurity',
                'Data Science'
            ];

            let wordIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            let typingSpeed = 100;

            function typeEffect() {
                const currentWord = words[wordIndex];

                if (isDeleting) {
                    typingElement.textContent = currentWord.substring(0, charIndex - 1);
                    charIndex--;
                    typingSpeed = 50;
                } else {
                    typingElement.textContent = currentWord.substring(0, charIndex + 1);
                    charIndex++;
                    typingSpeed = 100;
                }

                if (!isDeleting && charIndex === currentWord.length) {
                    isDeleting = true;
                    typingSpeed = 2000;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    typingSpeed = 500;
                }
                setTimeout(typeEffect, typingSpeed);
            }
            setTimeout(typeEffect, 1000);
        }

        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number[data-count]');

            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            updateCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });

                observer.observe(counter);
            });
        }

        animateCounters();

        const shapes = document.querySelectorAll('.shape');

        if (shapes.length > 0) {
            let ticking = false;

            window.addEventListener('mousemove', function (e) {
                if (!ticking) {
                    window.requestAnimationFrame(function () {
                        const mouseX = e.clientX / window.innerWidth;
                        const mouseY = e.clientY / window.innerHeight;

                        shapes.forEach((shape, index) => {
                            const speed = (index + 1) * 10;
                            const x = (mouseX - 0.5) * speed;
                            const y = (mouseY - 0.5) * speed;
                            shape.style.transform = `translate(${x}px, ${y}px)`;
                        });

                        ticking = false;
                    });

                    ticking = true;
                }
            });
        }

        $('a[href^="#"]').on('click', function (e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 800, 'swing');
            }
        });

        function revealOnScroll() {
            const reveals = document.querySelectorAll('.hero-stats .stat-item, .hero-tags .tag');

            reveals.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = `all 0.5s ease ${index * 0.1}s`;
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            reveals.forEach(element => observer.observe(element));
        }

        revealOnScroll();

        const floatingIcons = document.querySelectorAll('.icon-float');

        floatingIcons.forEach(icon => {
            const randomDelay = Math.random() * 5;
            icon.style.animationDelay = `-${randomDelay}s`;
        });

        const codeLines = document.querySelectorAll('.window-content pre code > *');

        if (codeLines.length > 0) {
            codeLines.forEach((line, index) => {
                line.style.opacity = '0';
                line.style.animation = `fadeIn 0.5s ease forwards ${index * 0.3}s`;
            });
        }

        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateX(-10px); }
                to { opacity: 1; transform: translateX(0); }
            }
        `;
        document.head.appendChild(style);

        let lastScroll = 0;
        const header = document.querySelector('.site-header');

        if (header) {
            window.addEventListener('scroll', function () {
                const currentScroll = window.pageYOffset;

                if (currentScroll > 100) {
                    header.style.background = 'rgba(10, 10, 15, 0.95)';
                    header.style.backdropFilter = 'blur(10px)';
                    header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.3)';
                } else {
                    header.style.background = '';
                    header.style.backdropFilter = '';
                    header.style.boxShadow = '';
                }

                lastScroll = currentScroll;
            });
        }

        document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });

        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            @keyframes ripple {
                to { transform: scale(4); opacity: 0; }
            }
        `;
        document.head.appendChild(rippleStyle);

    });

})(jQuery);