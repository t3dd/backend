var path = require('path');

module.exports = function (grunt) {
	// show elapsed time at the end
	require('time-grunt')(grunt);
	// load all grunt tasks
	require('load-grunt-tasks')(grunt);
	grunt.loadNpmTasks('web-component-tester');

	// configurable paths
	var yeomanConfig = {
		app: 'app',
		dist: 'dist'
	};

	grunt.initConfig({
		yeoman: yeomanConfig,
		watch: {
			options: {
				nospawn: true,
				livereload: {liveCSS: false}
			},
			livereload: {
				options: {
					livereload: true
				},
				files: [
					'<%= yeoman.app %>/*.html',
					'<%= yeoman.app %>/elements/{,*/}*.html',
					'{.tmp,<%= yeoman.app %>}/elements/{,*/}*.css',
					'{.tmp,<%= yeoman.app %>}/styles/{,*/}*.css',
					'{.tmp,<%= yeoman.app %>}/scripts/{,*/}*.js',
					'<%= yeoman.app %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
				]
			},
			js: {
				files: ['<%= yeoman.app %>/scripts/{,*/}*.js'],
				tasks: ['jshint']
			},
			styles: {
				files: [
					'<%= yeoman.app %>/styles/{,*/}*.css',
					'<%= yeoman.app %>/elements/{,*/}*.css'
				],
				tasks: ['copy:styles', 'autoprefixer:server']
			}
		},
		autoprefixer: {
			options: {
				browsers: ['last 2 versions']
			},
			server: {
				files: [
					{
						expand: true,
						cwd: '.tmp',
						src: '**/*.css',
						dest: '.tmp'
					}
				]
			},
			dist: {
				files: [
					{
						expand: true,
						cwd: '<%= yeoman.dist %>',
						src: ['**/*.css', '!bower_components/**/*.css'],
						dest: '<%= yeoman.dist %>'
					}
				]
			}
		},
		express: {
			options: {
				port: 9000,
				hostname: "localhost",
				livereload: true
			},
			livereload: {
				options: {
					server: path.resolve('./server'),
					bases: [path.resolve('./.tmp'), path.resolve(__dirname, yeomanConfig.app)]
				}
			}
		},
		open: {
			server: {
				url: 'http://localhost:<%= express.options.port %>'
			}
		},
		clean: {
			dist: ['.tmp', '<%= yeoman.dist %>/*'],
			server: '.tmp',
			vulcanization: ['<%= yeoman.dist %>/elements/*/', '<%= yeoman.dist %>/elements/elements.html']
		},
		jshint: {
			options: {
				jshintrc: '.jshintrc',
				reporter: require('jshint-stylish')
			},
			all: [
				'<%= yeoman.app %>/scripts/{,*/}*.js',
				'!<%= yeoman.app %>/scripts/vendor/*',
				'test/spec/{,*/}*.js'
			]
		},
		filerev: {
			options: {
				algorithm: 'md5',
				length: 8
			},
			source: {
				files: [{
					src: [
						'<%= yeoman.dist %>/images/**/*.{jpg,jpeg,gif,png,ico,svg}',
						'<%= yeoman.dist %>/fonts/*',
						'<%= yeoman.dist %>/scripts/*',
						'<%= yeoman.dist %>/styles/*',
						'<%= yeoman.dist %>/pages/*',
						'<%= yeoman.dist %>/elements/elements.vulcanized.html'
					]
				}]
			}
		},
		useminPrepare: {
			html: '<%= yeoman.app %>/index.html',
			options: {
				dest: '<%= yeoman.dist %>'
			}
		},
		usemin: {
			html: ['<%= yeoman.dist %>/{,*/}*.html'],
			css: ['<%= yeoman.dist %>/styles/{,*/}*.css'],
			options: {
				dirs: ['<%= yeoman.dist %>'],
				blockReplacements: {
					vulcanized: function (block) {
						return '<link rel="import" href="' + block.dest + '">';
					}
				},
				patterns: {
					html: [
						[
							/<script.+src=['"]([^"']+)["']/gm,
							'Update the HTML to reference our concat/min/revved script files'
						],
						[
							/<link[^\>]+href=['"]([^"']+)["']/gm,
							'Update the HTML with the new css filenames'
						],
						[
							/<img[^\>]*[^\>\S]+src=['"]([^'"\)#]+)(#.+)?["']/gm,
							'Update the HTML with the new img filenames'
						],
						[
							/<video[^\>]+src=['"]([^"']+)["']/gm,
							'Update the HTML with the new video filenames'
						],
						[
							/<video[^\>]+poster=['"]([^"']+)["']/gm,
							'Update the HTML with the new poster filenames'
						],
						[
							/<source[^\>]+src=['"]([^"']+)["']/gm,
							'Update the HTML with the new source filenames'
						],
						[
							/data-main\s*=['"]([^"']+)['"]/gm,
							'Update the HTML with data-main tags',
							function (m) {
								return m.match(/\.js$/) ? m : m + '.js';
							},
							function (m) {
								return m.replace('.js', '');
							}
						],
						[
							/data-(?!main).[^=]+=['"]([^'"]+)['"]/gm,
							'Update the HTML with data-* tags'
						],
						[
							/url\(\s*['"]?([^"'\)]+)["']?\s*\)/gm,
							'Update the HTML with background imgs, case there is some inline style'
						],
						[
							/<a[^\>]+href=['"]([^"']+)["']/gm,
							'Update the HTML with anchors images'
						],
						[
							/<input[^\>]+src=['"]([^"']+)["']/gm,
							'Update the HTML with reference in input'
						],
						[
							/<meta[^\>]+content=['"]([^"']+)["']/gm,
							'Update the HTML with the new img filenames in meta tags'
						],
						[
							/<object[^\>]+data=['"]([^"']+)["']/gm,
							'Update the HTML with the new object filenames'
						],
						[
							/<image[^\>]*[^\>\S]+xlink:href=['"]([^"'#]+)(#.+)?["']/gm,
							'Update the HTML with the new image filenames for svg xlink:href links'
						],
						[
							/<image[^\>]*[^\>\S]+src=['"]([^'"\)#]+)(#.+)?["']/gm,
							'Update the HTML with the new image filenames for src links'
						],
						[
							/<(?:img|source)[^\>]*[^\>\S]+srcset=['"]([^"'\s]+)\s*?(?:\s\d*?[w])?(?:\s\d*?[x])?\s*?["']/gm,
							'Update the HTML with the new image filenames for srcset links'
						],
						[
							/<(?:use|image)[^\>]*[^\>\S]+xlink:href=['"]([^'"\)#]+)(#.+)?["']/gm,
							'Update the HTML within the <use> tag when referencing an external url with svg sprites as in svg4everybody'
						],
						[/<app-route[^\>]+import=['"]([^"']+)["']/gm, 'Update the HTML with the new object filenames'],
						[/<core-image[^\>]+src=['"]([^"']+)["']/gm, 'Update the HTML with the new object filenames']
					]
				}
			}
		},
		vulcanize: {
			default: {
				options: {
					strip: true
				},
				files: {
					'<%= yeoman.dist %>/elements/elements.vulcanized.html': [
						'<%= yeoman.dist %>/elements/elements.html'
					]
				}
			}
		},
		imagemin: {
			dist: {
				files: [
					{
						expand: true,
						cwd: '<%= yeoman.app %>/images',
						src: '{,*/}*.{png,jpg,jpeg,svg}',
						dest: '<%= yeoman.dist %>/images'
					}
				]
			}
		},
		cssmin: {
			main: {
				files: {
					'<%= yeoman.dist %>/styles/main.css': [
						'.tmp/concat/styles/{,*/}*.css'
					]
				}
			},
			elements: {
				files: [
					{
						expand: true,
						cwd: '.tmp/elements',
						src: '{,*/}*.css',
						dest: '<%= yeoman.dist %>/elements'
					}
				]
			}
		},
		minifyHtml: {
			options: {
				removeComments: true,
				spare: true,
				quotes: true,
				empty: true
			},
			app: {
				files: [
					{
						expand: true,
						cwd: '<%= yeoman.dist %>',
						src: '*.html',
						dest: '<%= yeoman.dist %>'
					},
					{
						expand: true,
						cwd: '<%= yeoman.dist %>/pages',
						src: '*.html',
						dest: '<%= yeoman.dist %>/pages'
					},
					{
						expand: true,
						cwd: '<%= yeoman.dist %>/elements',
						src: '*.html',
						dest: '<%= yeoman.dist %>/elements'
					}
				]
			}
		},
		copy: {
			dist: {
				files: [
					{
						expand: true,
						dot: true,
						cwd: '<%= yeoman.app %>',
						dest: '<%= yeoman.dist %>',
						src: [
							'*.{ico,txt}',
							'.htaccess',
							'*.html',
							'elements/**',
							'fonts/**',
							'images/{,*/}*.{webp,gif,png,ico,xml,svg}',
							'pages/**',
							'bower_components/**'
						]
					}
				]
			},
			styles: {
				files: [
					{
						expand: true,
						cwd: '<%= yeoman.app %>',
						dest: '.tmp',
						src: ['{styles,elements}/{,*/}*.css']
					}
				]
			}
		},
		'wct-test': {
			options: {
				root: '<%= yeoman.app %>'
			},
			local: {
				options: {remote: false}
			},
			remote: {
				options: {remote: true}
			}
		},
		// See this tutorial if you'd like to run PageSpeed
		// against localhost: http://www.jamescryer.com/2014/06/12/grunt-pagespeed-and-ngrok-locally-testing/
		pagespeed: {
			options: {
				// By default, we use the PageSpeed Insights
				// free (no API key) tier. You can use a Google
				// Developer API key if you have one. See
				// http://goo.gl/RkN0vE for info
				nokey: true
			},
			// Update `url` below to the public URL for your site
			mobile: {
				options: {
					url: "https://developers.google.com/web/fundamentals/",
					locale: "en_GB",
					strategy: "mobile",
					threshold: 80
				}
			}
		}
	});

	grunt.registerTask('server', function (target) {
		grunt.log.warn('The `server` task has been deprecated. Use `grunt serve` to start a server.');
		grunt.task.run(['serve:' + target]);
	});

	grunt.registerTask('serve', function (target) {
		if (target === 'dist') {
			return grunt.task.run(['build', 'open', 'connect:dist:keepalive']);
		}

		grunt.task.run([
			'clean:server',
			'copy:styles',
			'autoprefixer:server',
			'express:livereload',
			'open',
			'watch'
		]);
	});

	grunt.registerTask('test', ['wct-test:local']);
	grunt.registerTask('test:browser', ['express:test']);
	grunt.registerTask('test:remote', ['wct-test:remote']);

	grunt.registerTask('build', [
		'clean:dist',
		'copy',
		'useminPrepare',
		'imagemin',
		'concat',
		'autoprefixer',
		'uglify',
		'cssmin',
		'vulcanize',
		'clean:vulcanization',
		'filerev',
		'usemin'
	]);

	grunt.registerTask('default', [
		'jshint',
		// 'test'
		'build'
	]);
};
