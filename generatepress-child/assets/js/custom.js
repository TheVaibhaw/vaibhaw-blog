/**
 * GeneratePress Child Theme - Custom JavaScript
 *
 * @package    GeneratePress_Child
 * @author     Your Name
 * @copyright  2026 Your Name
 * @license    GPL-2.0-or-later
 * @version    1.0.0
 */

(function () {
    'use strict';

    /**
     * Main theme module using revealing module pattern.
     *
     * @since 1.0.0
     */
    const GPChild = (function () {
        /**
         * Configuration object.
         *
         * @type {Object}
         */
        const config = {
            selectors: {
                body: 'body.gp-child-theme',
                backToTop: '.gp-child-back-to-top',
                smoothScroll: 'a[href^="#"]:not([href="#"])',
                lazyImages: 'img[data-src]',
                animateOnScroll: '[data-animate]',
            },
            classes: {
                visible: 'is-visible',
                active: 'is-active',
                loading: 'is-loading',
                loaded: 'is-loaded',
                touch: 'touch',
                noTouch: 'no-touch',
            },
            breakpoints: {
                sm: 576,
                md: 768,
                lg: 992,
                xl: 1200,
            },
            animationThreshold: 0.15,
            scrollOffset: 100,
        };

        /**
         * Cache DOM elements.
         *
         * @type {Object}
         */
        const elements = {};

        /**
         * State management.
         *
         * @type {Object}
         */
        const state = {
            isInitialized: false,
            isMobile: false,
            isTouch: false,
            scrollPosition: 0,
        };

        /**
         * Initialize the module.
         *
         * @since 1.0.0
         * @return {void}
         */
        function init() {
            if (state.isInitialized) {
                return;
            }

            cacheElements();
            detectFeatures();
            bindEvents();
            initModules();

            state.isInitialized = true;

            // Dispatch custom event for other scripts.
            document.dispatchEvent(new CustomEvent('gpChildReady', {
                detail: { gpChild: GPChild },
            }));
        }

        /**
         * Cache DOM elements for better performance.
         *
         * @since 1.0.0
         * @return {void}
         */
        function cacheElements() {
            elements.body = document.querySelector(config.selectors.body);
            elements.backToTop = document.querySelector(config.selectors.backToTop);
            elements.smoothScrollLinks = document.querySelectorAll(config.selectors.smoothScroll);
            elements.lazyImages = document.querySelectorAll(config.selectors.lazyImages);
            elements.animateElements = document.querySelectorAll(config.selectors.animateOnScroll);
        }

        /**
         * Detect device features and capabilities.
         *
         * @since 1.0.0
         * @return {void}
         */
        function detectFeatures() {
            // Detect touch support.
            state.isTouch = 'ontouchstart' in window ||
                navigator.maxTouchPoints > 0 ||
                navigator.msMaxTouchPoints > 0;

            // Update body class for touch/no-touch.
            if (elements.body) {
                elements.body.classList.remove(config.classes.noTouch);
                elements.body.classList.toggle(config.classes.touch, state.isTouch);
                elements.body.classList.toggle(config.classes.noTouch, !state.isTouch);
            }

            // Detect mobile viewport.
            state.isMobile = window.innerWidth < config.breakpoints.md;
        }

        /**
         * Bind event listeners.
         *
         * @since 1.0.0
         * @return {void}
         */
        function bindEvents() {
            // Throttled scroll handler.
            window.addEventListener('scroll', throttle(handleScroll, 100), { passive: true });

            // Debounced resize handler.
            window.addEventListener('resize', debounce(handleResize, 150));

            // Smooth scroll links.
            elements.smoothScrollLinks.forEach(function (link) {
                link.addEventListener('click', handleSmoothScroll);
            });

            // Back to top button.
            if (elements.backToTop) {
                elements.backToTop.addEventListener('click', scrollToTop);
            }
        }

        /**
         * Initialize sub-modules.
         *
         * @since 1.0.0
         * @return {void}
         */
        function initModules() {
            initLazyLoading();
            initScrollAnimations();
            initAccessibility();
        }

        /**
         * Handle scroll events.
         *
         * @since 1.0.0
         * @return {void}
         */
        function handleScroll() {
            state.scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

            // Toggle back to top button visibility.
            if (elements.backToTop) {
                const isVisible = state.scrollPosition > config.scrollOffset;
                elements.backToTop.classList.toggle(config.classes.visible, isVisible);
                elements.backToTop.setAttribute('aria-hidden', !isVisible);
            }

            // Trigger custom scroll event.
            document.dispatchEvent(new CustomEvent('gpChildScroll', {
                detail: { scrollPosition: state.scrollPosition },
            }));
        }

        /**
         * Handle resize events.
         *
         * @since 1.0.0
         * @return {void}
         */
        function handleResize() {
            const wasMobile = state.isMobile;
            state.isMobile = window.innerWidth < config.breakpoints.md;

            // Only dispatch if breakpoint changed.
            if (wasMobile !== state.isMobile) {
                document.dispatchEvent(new CustomEvent('gpChildBreakpointChange', {
                    detail: {
                        isMobile: state.isMobile,
                        width: window.innerWidth,
                    },
                }));
            }
        }

        /**
         * Handle smooth scroll click.
         *
         * @since 1.0.0
         * @param {Event} event Click event.
         * @return {void}
         */
        function handleSmoothScroll(event) {
            const href = event.currentTarget.getAttribute('href');
            const target = document.querySelector(href);

            if (target) {
                event.preventDefault();

                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth',
                });

                // Set focus for accessibility.
                target.setAttribute('tabindex', '-1');
                target.focus({ preventScroll: true });
            }
        }

        /**
         * Scroll to top of page.
         *
         * @since 1.0.0
         * @param {Event} event Click event.
         * @return {void}
         */
        function scrollToTop(event) {
            if (event) {
                event.preventDefault();
            }

            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        }

        /**
         * Initialize lazy loading for images.
         *
         * @since 1.0.0
         * @return {void}
         */
        function initLazyLoading() {
            if (!elements.lazyImages.length) {
                return;
            }

            // Use native lazy loading if supported.
            if ('loading' in HTMLImageElement.prototype) {
                elements.lazyImages.forEach(function (img) {
                    img.src = img.dataset.src;
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                    }
                    img.removeAttribute('data-src');
                    img.removeAttribute('data-srcset');
                });
                return;
            }

            // Fallback to Intersection Observer.
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            if (img.dataset.srcset) {
                                img.srcset = img.dataset.srcset;
                            }
                            img.classList.add(config.classes.loaded);
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01,
                });

                elements.lazyImages.forEach(function (img) {
                    imageObserver.observe(img);
                });
            }
        }

        /**
         * Initialize scroll-triggered animations.
         *
         * @since 1.0.0
         * @return {void}
         */
        function initScrollAnimations() {
            if (!elements.animateElements.length) {
                return;
            }

            // Respect reduced motion preference.
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                elements.animateElements.forEach(function (el) {
                    el.classList.add(config.classes.visible);
                });
                return;
            }

            if ('IntersectionObserver' in window) {
                const animationObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            const el = entry.target;
                            const delay = el.dataset.animateDelay || 0;

                            setTimeout(function () {
                                el.classList.add(config.classes.visible);
                            }, delay);
                        }
                    });
                }, {
                    threshold: config.animationThreshold,
                });

                elements.animateElements.forEach(function (el) {
                    animationObserver.observe(el);
                });
            }
        }

        /**
         * Initialize accessibility enhancements.
         *
         * @since 1.0.0
         * @return {void}
         */
        function initAccessibility() {
            // Skip link functionality.
            const skipLink = document.querySelector('.skip-link');
            if (skipLink) {
                skipLink.addEventListener('click', function (e) {
                    const target = document.querySelector(skipLink.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.setAttribute('tabindex', '-1');
                        target.focus();
                    }
                });
            }

            // Handle keyboard navigation for interactive elements.
            document.addEventListener('keydown', function (e) {
                // Escape key handler.
                if (e.key === 'Escape') {
                    document.dispatchEvent(new CustomEvent('gpChildEscapePressed'));
                }
            });
        }

        /**
         * Throttle function execution.
         *
         * @since 1.0.0
         * @param {Function} func     Function to throttle.
         * @param {number}   limit    Time limit in milliseconds.
         * @return {Function} Throttled function.
         */
        function throttle(func, limit) {
            let inThrottle;
            return function (...args) {
                if (!inThrottle) {
                    func.apply(this, args);
                    inThrottle = true;
                    setTimeout(function () {
                        inThrottle = false;
                    }, limit);
                }
            };
        }

        /**
         * Debounce function execution.
         *
         * @since 1.0.0
         * @param {Function} func  Function to debounce.
         * @param {number}   wait  Wait time in milliseconds.
         * @return {Function} Debounced function.
         */
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        /**
         * Public API.
         */
        return {
            init: init,
            getState: function () {
                return Object.assign({}, state);
            },
            getConfig: function () {
                return Object.assign({}, config);
            },
            scrollToTop: scrollToTop,
            throttle: throttle,
            debounce: debounce,
        };
    })();

    /**
     * AJAX Handler Module.
     *
     * @since 1.0.0
     */
    const GPChildAjax = (function () {
        /**
         * Make an AJAX request.
         *
         * @since 1.0.0
         * @param {Object} options Request options.
         * @return {Promise} Promise resolving to response data.
         */
        function request(options) {
            const defaults = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            };

            const settings = Object.assign({}, defaults, options);

            // Use localized data if available.
            if (typeof gpChildData !== 'undefined' && settings.data) {
                if (typeof settings.data === 'object') {
                    settings.data.nonce = gpChildData.nonce;
                }
            }

            return fetch(getAjaxUrl(), {
                method: settings.method,
                headers: settings.headers,
                body: buildFormData(settings.data),
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .catch(function (error) {
                    console.error('AJAX Error:', error);
                    throw error;
                });
        }

        /**
         * Get AJAX URL.
         *
         * @since 1.0.0
         * @return {string} AJAX URL.
         */
        function getAjaxUrl() {
            if (typeof gpChildData !== 'undefined' && gpChildData.ajaxUrl) {
                return gpChildData.ajaxUrl;
            }
            return '/wp-admin/admin-ajax.php';
        }

        /**
         * Build form data from object.
         *
         * @since 1.0.0
         * @param {Object} data Data object.
         * @return {FormData} Form data.
         */
        function buildFormData(data) {
            const formData = new FormData();

            if (data && typeof data === 'object') {
                Object.keys(data).forEach(function (key) {
                    formData.append(key, data[key]);
                });
            }

            return formData;
        }

        return {
            request: request,
        };
    })();

    /**
     * Utility Functions Module.
     *
     * @since 1.0.0
     */
    const GPChildUtils = (function () {
        /**
         * Get localized string.
         *
         * @since 1.0.0
         * @param {string} key     Translation key.
         * @param {string} fallback Fallback string.
         * @return {string} Translated string.
         */
        function getString(key, fallback) {
            if (typeof gpChildData !== 'undefined' &&
                gpChildData.i18n &&
                gpChildData.i18n[key]) {
                return gpChildData.i18n[key];
            }
            return fallback || key;
        }

        /**
         * Check if element is in viewport.
         *
         * @since 1.0.0
         * @param {Element} element  DOM element.
         * @param {number}  offset   Offset in pixels.
         * @return {boolean} True if element is in viewport.
         */
        function isInViewport(element, offset) {
            offset = offset || 0;
            const rect = element.getBoundingClientRect();

            return (
                rect.top >= 0 - offset &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) + offset &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        /**
         * Generate unique ID.
         *
         * @since 1.0.0
         * @param {string} prefix ID prefix.
         * @return {string} Unique ID.
         */
        function uniqueId(prefix) {
            prefix = prefix || 'gp-child-';
            return prefix + Math.random().toString(36).substring(2, 11);
        }

        /**
         * Parse JSON safely.
         *
         * @since 1.0.0
         * @param {string} str      JSON string.
         * @param {*}      fallback Fallback value.
         * @return {*} Parsed JSON or fallback.
         */
        function parseJSON(str, fallback) {
            try {
                return JSON.parse(str);
            } catch (e) {
                return fallback !== undefined ? fallback : null;
            }
        }

        return {
            getString: getString,
            isInViewport: isInViewport,
            uniqueId: uniqueId,
            parseJSON: parseJSON,
        };
    })();

    // Initialize when DOM is ready.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', GPChild.init);
    } else {
        GPChild.init();
    }

    // Expose modules globally for extensibility.
    window.GPChild = GPChild;
    window.GPChildAjax = GPChildAjax;
    window.GPChildUtils = GPChildUtils;
})();
