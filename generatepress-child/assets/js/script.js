'use strict';

/**
 * Performance-optimized homepage interactions
 * Uses requestAnimationFrame and IntersectionObserver for smooth animations
 */

// Utility: Throttle function for scroll events
const throttle = (fn, wait) => {
	let lastTime = 0;
	return (...args) => {
		const now = Date.now();
		if (now - lastTime >= wait) {
			lastTime = now;
			fn(...args);
		}
	};
};

// Utility: Debounce function
const debounce = (fn, delay) => {
	let timeoutId;
	return (...args) => {
		clearTimeout(timeoutId);
		timeoutId = setTimeout(() => fn(...args), delay);
	};
};

document.addEventListener('DOMContentLoaded', () => {

	// Hero typing animation with requestAnimationFrame
	const typedElement = document.getElementById('heroTyped');
	if (typedElement) {
		const words = ['Technology', 'Programming', 'Web Dev', 'Dev Tools', 'Open Source'];
		let wordIndex = 0;
		let charIndex = 0;
		let isDeleting = false;
		let lastTimestamp = 0;
		let typeSpeed = 100;

		const type = (timestamp) => {
			if (!lastTimestamp) lastTimestamp = timestamp;
			const elapsed = timestamp - lastTimestamp;

			if (elapsed >= typeSpeed) {
				lastTimestamp = timestamp;
				const currentWord = words[wordIndex];

				if (isDeleting) {
					typedElement.textContent = currentWord.substring(0, charIndex - 1);
					charIndex--;
					typeSpeed = 40;
				} else {
					typedElement.textContent = currentWord.substring(0, charIndex + 1);
					charIndex++;
					typeSpeed = 100;
				}

				if (!isDeleting && charIndex === currentWord.length) {
					typeSpeed = 2500;
					isDeleting = true;
				} else if (isDeleting && charIndex === 0) {
					isDeleting = false;
					wordIndex = (wordIndex + 1) % words.length;
					typeSpeed = 500;
				}
			}

			requestAnimationFrame(type);
		};

		// Start typing animation after a short delay
		setTimeout(() => requestAnimationFrame(type), 500);
	}

	// Intersection Observer for scroll animations
	const animatedElements = document.querySelectorAll('[data-animate]');
	if (animatedElements.length) {
		const observerOptions = {
			root: null,
			rootMargin: '0px 0px -50px 0px',
			threshold: 0.1
		};

		const animationObserver = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const delay = parseInt(entry.target.dataset.delay, 10) || 0;

					// Use requestAnimationFrame for smoother animations
					requestAnimationFrame(() => {
						setTimeout(() => {
							entry.target.classList.add('animate-in');
						}, delay);
					});

					animationObserver.unobserve(entry.target);
				}
			});
		}, observerOptions);

		animatedElements.forEach(el => animationObserver.observe(el));
	}

	// Categories slider with touch support and smooth scrolling
	const slider = document.getElementById('categoriesSlider');
	if (slider) {
		const prevBtn = document.querySelector('.categories-nav--prev');
		const nextBtn = document.querySelector('.categories-nav--next');
		const scrollAmount = 280;

		const updateNavState = throttle(() => {
			if (prevBtn) prevBtn.disabled = slider.scrollLeft <= 10;
			if (nextBtn) nextBtn.disabled = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10;
		}, 100);

		if (prevBtn) {
			prevBtn.addEventListener('click', () => {
				slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
			});
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', () => {
				slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
			});
		}

		slider.addEventListener('scroll', updateNavState, { passive: true });

		// Touch/drag scrolling support
		let isDown = false;
		let startX;
		let scrollLeft;

		slider.addEventListener('mousedown', (e) => {
			isDown = true;
			slider.style.cursor = 'grabbing';
			startX = e.pageX - slider.offsetLeft;
			scrollLeft = slider.scrollLeft;
		});

		slider.addEventListener('mouseleave', () => {
			isDown = false;
			slider.style.cursor = 'grab';
		});

		slider.addEventListener('mouseup', () => {
			isDown = false;
			slider.style.cursor = 'grab';
		});

		slider.addEventListener('mousemove', (e) => {
			if (!isDown) return;
			e.preventDefault();
			const x = e.pageX - slider.offsetLeft;
			const walk = (x - startX) * 1.5;
			slider.scrollLeft = scrollLeft - walk;
		});

		// Initialize nav state
		updateNavState();
	}

	// Smooth scroll for anchor links
	document.querySelectorAll('a[href^="#"]').forEach(anchor => {
		anchor.addEventListener('click', function (e) {
			const href = this.getAttribute('href');
			if (href.length > 1) {
				const target = document.querySelector(href);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({
						behavior: 'smooth',
						block: 'start'
					});
				}
			}
		});
	});

});
