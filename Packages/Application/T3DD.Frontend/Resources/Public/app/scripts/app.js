(function(document) {
	'use strict';

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

	var template = document.getElementById('t');

	template.register = function() {
		document.querySelector('app-router').go('/register');
	};

	template.login = function() {
		document.getElementById('baseLogin').login();
		document.getElementById('userLogin').opened = false;
	};

	template.logout = function() {
		document.getElementById('baseLogin').logout();
		document.getElementById('userLogin').opened = false;
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
		this.globals.scrollTarget = this.$.scrollHeader.shadowRoot.getElementById('mainContainer');
		this.$.router.addEventListener('activate-route-end', function(event) {
			template.currentPath = event.detail.path;
		});
	});

})(wrap(document));
