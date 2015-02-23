(function(document) {
	'use strict';

	//function() {
	//
	//};

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

	template.addEventListener('template-bound', function() {
		var scrollHeader = document.getElementById('scrollHeader'),
			router = document.getElementById('router');

		router.addEventListener('activate-route-end', function(event) {
			// After every route change the header height needs to be remeasured
			template.currentPath = event.detail.path;
			scrollHeader.measureHeaderHeight();
		});
	});

})(wrap(document));
