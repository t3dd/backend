(function(document) {
	'use strict';

	var template = document.getElementById('t');

	template.register = function() {
		document.querySelector('app-router').go('/register');
	};

	template.login = function() {
		document.getElementById('baseLogin').login();
		document.getElementById('user-login').opened = false;
	};
	template.logout = function() {
		document.getElementById('baseLogin').logout();
		document.getElementById('user-login').opened = false;
	};
	template.onLogin = function() {
		this.globals.currentUser = this.user;
	};
	template.onLoginError = function(err) {
		console.log('An error occurred.');
	};

})(wrap(document));
