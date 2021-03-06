(function(document) {
	'use strict';

	function initGlobalFilters() {
	}

	function smoothStep(start, end, point) {
		if(point <= start) {
			return 0
		}
		if(point >= end) {
			return 1
		}
		var x = (point - start) / (end - start);
		return x * x * (3 - 2 * x)
	}

	var template = document.getElementById('t'),
		firstRequest = true;

	template.register = function() {
		this.$.router.go('/register');
	};

	template.login = function() {
		this.$.baseLogin.login();
		this.$.userLogin.opened = false;
	};

	template.logout = function() {
		this.$.baseLogin.logout();
		this.$.userLogin.opened = false;
	};

	template.requireLogin = function(pathOrCallback) {
		var loggedIn = false,
			listener = (function() {
			if (!loggedIn) {
				if (this.currentPath !== pathOrCallback) {
					this.$.router.go(this.currentPath);
				} else {
					this.$.router.go('/');
				}
			}
			this.$.loginInterceptor.removeEventListener('core-overlay-close-completed', listener);
		}).bind(this);

		this.$.loginInterceptor.opened = true;
		this.$.loginInterceptor.addEventListener('core-overlay-close-completed', listener);

		this.observeOnce('globals.user', (function() {
			loggedIn = true;
			this.$.loginInterceptor.removeEventListener('core-overlay-close-completed', listener);
			this.async(function() {
				this.$.loginInterceptor.opened = false;
				if (typeof pathOrCallback === 'string') {
					this.$.router.go(pathOrCallback);
				} else {
					pathOrCallback();
				}
			});
		}).bind(this));
	};

	template.scrollToTop = function(event) {
		event.preventDefault();
		event.stopPropagation();
		var scrollContainer = document.getElementById('scrollHeader').shadowRoot.getElementById('mainContainer');
		var startTime = performance.now();
		var endTime = startTime + 250;
		var startTop = scrollContainer.scrollTop;
		var destY = startTop * -1;
		if(destY === 0) {
			return
		}
		var callback = function(timestamp) {
			if(timestamp < endTime) {
				requestAnimationFrame(callback)
			}
			var point = smoothStep(startTime, endTime, timestamp);
			var scrollTop = Math.round(startTop + destY * point);
			scrollContainer.scrollTop = scrollTop;
		};
		callback(startTime)
	};

	template.addEventListener('template-bound', function() {
		initGlobalFilters();
		CoreStyle.g.paperInput.focusedColor = '#FF8700';
		this.globals.assetRootPath = this.hasAttribute('assetrootpath') ? this.getAttribute('assetrootpath').replace('/.', '') : '';
		this.globals.scrollTarget = this.$.scrollHeader.shadowRoot.getElementById('mainContainer');
		this.$.router.addEventListener('activate-route-start', (function(event) {
			this.$.drawerPanel.closeDrawer();
		}).bind(this));
		this.$.router.addEventListener('activate-route-end', (function(event) {
			_paq.push(['setCustomUrl', event.detail.path]);
			_paq.push(['trackPageView']);
			if (firstRequest) {
				this.interceptRouting(event);
			}
			template.globals.currentPath = template.currentPath = event.detail.path;
			this.$.scrollHeader.shadowRoot.getElementById('mainContainer').scrollTop = 0;
			firstRequest = false;
		}).bind(this));
		this.$.router.addEventListener('activate-route-start', this.interceptRouting.bind(this));
	});

	template.changePage = function(event) {
		if (!event.detail.isSelected) return;
		this.$.router.go(this.currentPath);
	};

	template.isLoginRequired = function(route) {
		return !!route.hasAttribute('forceLogin');
	};

	template.interceptRouting = function(event) {
		if (!this.isLoginRequired(event.detail.route)) return;
		event.preventDefault();
		if (!this.globals.userStatusKnown) {
			this.observeOnce('globals.userStatusKnown', function(newValue) {
				if (newValue) this.async(function() { this.interceptRouting(event); }.bind(this));
			});
			return;
		}
		this.requireLogin(event.detail.path);
	};

	template.observeOnce = function(attributeName, listener) {
		var observer = new PathObserver(this, attributeName);
		observer.open(function(newValue, oldValue) {
			listener.call(this, newValue, oldValue);
			observer.close();
		}.bind(this));
	};

})(wrap(document));
