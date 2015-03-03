/*jslint node: true */
/*global require, module, __dirname */
module.exports = function (grunt) {

	'use strict';

	// show elapsed time at the end
	require('time-grunt')(grunt);

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		hub: {
			options: {
				concurrent: 5
			},
			all: {
				src: ['Packages/*/T3DD.*/Resources/Public/Gruntfile.js']
			}
		}
	});

	grunt.loadNpmTasks('grunt-hub');
	grunt.registerTask('serve', ['hub:all:serve']);
	grunt.registerTask('build', ['hub:all:build']);
	grunt.registerTask('default', 'hub:all:default');

};
