'use strict';

document.addEventListener('DOMContentLoaded', () => {

	const typedElement = document.getElementById('heroTyped');
	if (typedElement) {
		const words = ['Technology', 'Programming', 'Web Dev', 'Dev Tools', 'Open Source'];
		let wordIndex = 0;
		let charIndex = 0;
		let isDeleting = false;
		let typeSpeed = 100;

		const type = () => {
			const currentWord = words[wordIndex];

			if (isDeleting) {
				typedElement.textContent = currentWord.substring(0, charIndex - 1);
				charIndex--;
				typeSpeed = 50;
			} else {
				typedElement.textContent = currentWord.substring(0, charIndex + 1);
				charIndex++;
				typeSpeed = 120;
			}

			if (!isDeleting && charIndex === currentWord.length) {
				typeSpeed = 2000;
				isDeleting = true;
			} else if (isDeleting && charIndex === 0) {
				isDeleting = false;
				wordIndex = (wordIndex + 1) % words.length;
				typeSpeed = 400;
			}

			setTimeout(type, typeSpeed);
		};

		type();
	}

	const animatedElements = document.querySelectorAll('[data-animate]');
	if (animatedElements.length) {
		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const delay = parseInt(entry.target.dataset.delay, 10) || 0;
					setTimeout(() => {
						entry.target.classList.add('animate-in');
					}, delay);
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.15 });

		animatedElements.forEach(el => observer.observe(el));
	}

	const slider = document.getElementById('categoriesSlider');
	if (slider) {
		const prevBtn = document.querySelector('.categories-nav--prev');
		const nextBtn = document.querySelector('.categories-nav--next');
		const scrollAmount = 260;

		const updateNavState = () => {
			if (prevBtn) prevBtn.disabled = slider.scrollLeft <= 0;
			if (nextBtn) nextBtn.disabled = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 1;
		};

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
		updateNavState();
	}

});
