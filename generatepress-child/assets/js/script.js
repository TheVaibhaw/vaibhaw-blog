'use strict';

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

const debounce = (fn, delay) => {
	let timeoutId;
	return (...args) => {
		clearTimeout(timeoutId);
		timeoutId = setTimeout(() => fn(...args), delay);
	};
};

document.addEventListener('DOMContentLoaded', () => {
	const typedElement = document.getElementById('heroTyped');
	if (typedElement) {
		const words = ['Technology', 'Programming', 'Web Dev', 'Dev Tools', 'Open Source'];
		let wordIndex = 0, charIndex = 0, isDeleting = false, lastTimestamp = 0, typeSpeed = 100;

		const type = (timestamp) => {
			if (!lastTimestamp) lastTimestamp = timestamp;
			if (timestamp - lastTimestamp >= typeSpeed) {
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

		setTimeout(() => requestAnimationFrame(type), 500);
	}

	const animatedElements = document.querySelectorAll('[data-animate]');
	if (animatedElements.length) {
		const animationObserver = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const delay = parseInt(entry.target.dataset.delay, 10) || 0;
					requestAnimationFrame(() => {
						setTimeout(() => entry.target.classList.add('animate-in'), delay);
					});
					animationObserver.unobserve(entry.target);
				}
			});
		}, { root: null, rootMargin: '0px 0px -50px 0px', threshold: 0.1 });

		animatedElements.forEach(el => animationObserver.observe(el));
	}

	const slider = document.getElementById('categoriesSlider');
	if (slider) {
		const prevBtn = document.querySelector('.categories-nav--prev');
		const nextBtn = document.querySelector('.categories-nav--next');
		const scrollAmount = 280;

		const updateNavState = throttle(() => {
			if (prevBtn) prevBtn.disabled = slider.scrollLeft <= 10;
			if (nextBtn) nextBtn.disabled = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10;
		}, 100);

		prevBtn?.addEventListener('click', () => slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
		nextBtn?.addEventListener('click', () => slider.scrollBy({ left: scrollAmount, behavior: 'smooth' }));
		slider.addEventListener('scroll', updateNavState, { passive: true });

		let isDown = false, startX, scrollLeft;
		slider.addEventListener('mousedown', (e) => { isDown = true; slider.style.cursor = 'grabbing'; startX = e.pageX - slider.offsetLeft; scrollLeft = slider.scrollLeft; });
		slider.addEventListener('mouseleave', () => { isDown = false; slider.style.cursor = 'grab'; });
		slider.addEventListener('mouseup', () => { isDown = false; slider.style.cursor = 'grab'; });
		slider.addEventListener('mousemove', (e) => { if (!isDown) return; e.preventDefault(); slider.scrollLeft = scrollLeft - ((e.pageX - slider.offsetLeft) - startX) * 1.5; });
		updateNavState();
	}

	document.querySelectorAll('a[href^="#"]').forEach(anchor => {
		anchor.addEventListener('click', function (e) {
			const href = this.getAttribute('href');
			if (href.length > 1) {
				const target = document.querySelector(href);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			}
		});
	});
});
