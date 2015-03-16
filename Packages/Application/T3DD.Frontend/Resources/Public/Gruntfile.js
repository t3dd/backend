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

	(function() {
		// So hacky, wow, ugly
		var path = require('path');
		var FileProcessor = require('grunt-usemin/lib/fileprocessor');
		var File = require('grunt-usemin/lib/file');
		FileProcessor.prototype.process = function (filename, assetSearchPath) {
		  //console.log('processing file %s', filename, assetSearchPath);

		  if (typeof filename === 'string') {
		    this.file = new File(filename);
		  } else {
		    // filename is an object and should conform to lib/file.js API
		    this.file = filename;
		  }

		  if (assetSearchPath && assetSearchPath.length !== 0) {
		    this.file.searchPath = this.file.searchPath.concat(assetSearchPath);
		  }

		  var content = this.replaceWithRevved(this.replaceBlocks(this.file), this.file.searchPath);

		  return content;
		};
		var RevvedFinder = require('grunt-usemin/lib/revvedfinder');
		RevvedFinder.prototype.find = function find(ofile, searchDirs) {
			var file = ofile;
			var searchPaths = searchDirs;
			var absolute;
			var prefix;
			var rewriteToAbsolute;

			if(typeof searchDirs === 'string') {
				searchPaths = [searchDirs];
			}

			//console.log('Looking for revved version of %s in ', ofile, searchPaths);

			//do not touch external files or the root
			// FIXME: Should get only relative files
			if(ofile.match(/:\/\//) || ofile === '') {
				return ofile;
			}

			if(file[0] === '/') {
				// We need to remember this is an absolute file, but transform it
				// to a relative one
				absolute = true;
				file = file.replace(/^(\/+)/, function(match, header) {
					prefix = header;
					return '';
				});
			} else {
				rewriteToAbsolute = true;
			}

			var filepaths = this.getRevvedCandidates(file, searchPaths);
//console.log(file, searchPaths, rewriteToAbsolute, filepaths);
			var filepath = filepaths[0];
			//console.log('filepath is now ', filepath);

			// not a file in temp, skip it
			if(!filepath) {
				return ofile;
			}

			if(absolute) {
				filepath = prefix + filepath;
			} else if (rewriteToAbsolute) {
				filepath = path.resolve('/_Resources/Static/Packages/T3DD.Frontend/', searchPaths[0], filepath);
			}

			//console.log('Let\'s return %s', filepath);
			return filepath;
		};
	})();

	grunt.initConfig({
		yeoman: yeomanConfig,
		clean: {
			dist: ['<%= yeoman.dist %>/*']
		},
		autoprefixer: {
			options: {
				browsers: ['last 2 versions']
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
		useminPrepare: {
			html: [
				'<%= yeoman.app %>/{,*/}*.html',
				'<%= yeoman.app %>/elements/elements.*.html',
				'<%= yeoman.app %>/pages/*.html'
			],
			options: {
				dest: '<%= yeoman.dist %>'
			}
		},
		usemin: {
			html: [
				'<%= yeoman.dist %>/{,*/}*.html',
				'<%= yeoman.dist %>/elements/elements.*.html',
				'<%= yeoman.dist %>/pages/*.html'
			],
			css: ['<%= yeoman.dist %>/styles/{,*/}*.css'],
			options: {
				dirs: ['<%= yeoman.dist %>'],
				assetsDirs: ['<%= yeoman.dist %>'],
				blockReplacements: {
					css: function(block) {
						var hasShadowShim = block.raw.some(function(item) {
							return item.indexOf('shim-shadowdom') !== -1;
						});
						return '<link rel="stylesheet" href="' + block.dest + '"' + (hasShadowShim ? ' shim-shadowdom' : '') + '>';
					},
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
						[
							/<app-route[^\>]+import=['"](?:\{\{[^\}]+\}\})?([^"']+)["']/gm,
							'Update the HTML with the new app-route filenames'],
						[
							/<core-image[^\>]+src=['"]([^"']+)["']/gm,
							'Update the HTML with the new core-image filenames'
						],
						[
							/<polymer-element[^\>]+assetpath=['"]([^"']+)["']/gm,
							'Update the polymer-elments with the new asset path'
						],
						[
							/<google-map-marker[^\>]+icon=['"]([^"']+)["']/gm,
							'Update the google-map-marker with the new icon path'
						]
					]
				}
			}
		},
		vulcanize: {
			default: {
				options: {
					strip: true,
					inline: true,
					csp: false
				},
				files: {
					'<%= yeoman.dist %>/elements/elements.vulcanized.html': [
						'<%= yeoman.app %>/elements/elements.html'
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
							'elements/',
							'fonts/**',
							'images/{,*/}*.{webp,gif,png,ico,xml,svg}',
							'pages/**',
							'styles/polymer-layout.css',
							'!bower_components/**'
						]
					}
				]
			}
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
						'<%= yeoman.dist %>/scripts/*',
						'<%= yeoman.dist %>/styles/*',
						'<%= yeoman.dist %>/pages/*',
						'<%= yeoman.dist %>/elements/elements.vulcanized.html'
					]
				}]
			}
		}
	});

	grunt.registerTask('debug', [
		]);

	grunt.registerTask('build', [
		'clean:dist',
		'copy',
		'vulcanize',
		'useminPrepare',
		'imagemin',
		'concat',
		'autoprefixer',
		'uglify',
		'cssmin',
		'filerev',
		'usemin',
		'minifyHtml'
	]);

	grunt.registerTask('default', [
		'jshint',
		// 'test'
		'build'
	]);
};
