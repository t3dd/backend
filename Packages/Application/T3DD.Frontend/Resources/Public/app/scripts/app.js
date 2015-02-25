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
		this.globals.scrollTarget = this.$.scrollHeader.shadowRoot.getElementById('mainContainer');
		this.$.router.addEventListener('activate-route-end', function(event) {
			template.currentPath = event.detail.path;
		});
	});

})(wrap(document));
