(function () {
	'use strict';

	var BAR_ID = 'cta-bar';
	var PINNED_CLASS = 'is-pinned';
	var ACTIVE_CLASS = 'active';
	var PIN_LOCK_MS = 150;

	function neutralizeGsapPin(bar) {
		var ScrollTrigger = window.ScrollTrigger
			|| (window.gsap && window.gsap.ScrollTrigger)
			|| null;

		if (ScrollTrigger && typeof ScrollTrigger.getAll === 'function') {
			ScrollTrigger.getAll().forEach(function (st) {
				var trigger = st.trigger;
				if (trigger === bar || (trigger && trigger.id === BAR_ID)) {
					st.kill(true);
				}
			});
		}

		// GSAP wraps the pinned element in .pin-spacer — unwrap so our logic owns layout.
		var parent = bar.parentElement;
		if (parent && parent.classList.contains('pin-spacer')) {
			var grandParent = parent.parentNode;
			if (grandParent) {
				grandParent.insertBefore(bar, parent);
				grandParent.removeChild(parent);
			}
		}

		// Clear leftover GSAP inline pin styles.
		bar.style.position = '';
		bar.style.top = '';
		bar.style.left = '';
		bar.style.width = '';
		bar.style.maxWidth = '';
		bar.style.margin = '';
		bar.style.transform = '';
		bar.style.zIndex = '';
	}

	function init() {
		var bar = document.getElementById(BAR_ID);

		if (!bar || bar.dataset.ctaFixInit === '1') {
			return !!bar;
		}

		bar.dataset.ctaFixInit = '1';
		neutralizeGsapPin(bar);

		var placeholder = document.createElement('div');
		placeholder.className = 'cta-bar-placeholder';
		placeholder.setAttribute('aria-hidden', 'true');
		bar.insertAdjacentElement('afterend', placeholder);

		var pinOffset = 0;
		var pinLockedUntil = 0;

		function clearPinnedStyles() {
			bar.classList.remove(PINNED_CLASS, ACTIVE_CLASS);
			placeholder.style.display = 'none';
			placeholder.style.height = '0';
			bar.style.width = '';
		}

		function getBarWidth() {
			var parent = bar.parentElement;
			return parent ? parent.getBoundingClientRect().width : bar.getBoundingClientRect().width;
		}

		function measureOffset() {
			var wasPinned = bar.classList.contains(PINNED_CLASS);

			if (wasPinned) {
				bar.classList.remove(PINNED_CLASS, ACTIVE_CLASS);
				bar.style.width = '';
			}

			placeholder.style.display = 'none';
			placeholder.style.height = '0';
			pinOffset = Math.floor(bar.getBoundingClientRect().top + window.scrollY);

			if (wasPinned) {
				applyPinnedLayout();
			}
		}

		function applyPinnedLayout() {
			bar.classList.add(PINNED_CLASS, ACTIVE_CLASS);
			bar.style.width = getBarWidth() + 'px';

			placeholder.style.display = 'block';
			placeholder.style.height = Math.ceil(bar.getBoundingClientRect().height) + 'px';
		}

		function setPinnedState(shouldPin) {
			if (shouldPin) {
				if (Date.now() < pinLockedUntil) {
					return;
				}

				applyPinnedLayout();

				window.requestAnimationFrame(function () {
					if (bar.classList.contains(PINNED_CLASS)) {
						applyPinnedLayout();
					}
				});

				return;
			}

			clearPinnedStyles();
			pinLockedUntil = Date.now() + PIN_LOCK_MS;
		}

		function updatePin() {
			var scrollY = window.scrollY;
			var isPinned = bar.classList.contains(PINNED_CLASS);

			if (!isPinned && scrollY >= pinOffset) {
				setPinnedState(true);
				return;
			}

			if (isPinned && scrollY < pinOffset) {
				setPinnedState(false);
			}
		}

		var ticking = false;

		function onScroll() {
			if (ticking) {
				return;
			}

			ticking = true;

			window.requestAnimationFrame(function () {
				updatePin();
				ticking = false;
			});
		}

		measureOffset();
		updatePin();

		// GSAP in theme main.js kan later initialiseren — nog even neutraliseren.
		var retries = 0;
		var gsapWatch = window.setInterval(function () {
			neutralizeGsapPin(bar);
			measureOffset();
			updatePin();
			retries += 1;
			if (retries >= 20) {
				window.clearInterval(gsapWatch);
			}
		}, 100);

		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', function () {
			measureOffset();
			updatePin();
		});
		window.addEventListener('orientationchange', function () {
			window.setTimeout(function () {
				measureOffset();
				updatePin();
			}, 100);
		});

		return true;
	}

	function boot(attempts) {
		if (init() || attempts <= 0) {
			return;
		}

		window.setTimeout(function () {
			boot(attempts - 1);
		}, 100);
	}

	if (document.readyState === 'complete') {
		boot(20);
	} else {
		window.addEventListener('load', function () {
			boot(20);
		});
	}
})();
