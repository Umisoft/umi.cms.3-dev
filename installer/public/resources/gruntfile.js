module.exports = function(grunt){
    'use strict';

    //описываем конфигурацию
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'), //подгружаем package.json, чтобы использовать его данные

        watch: {
            sass: {
                files: ['resource/admin/app/sass/**/*.scss','resource/admin/app/foundationJs/*.js','resource/admin/app/foundationJs/components/*.js'],
                tasks: ['sass','concat','autoprefixer']
            }
        },

        sass: {
            dev: {
                options: {
                    includePaths: ['resource/admin/libs/foundation/scss/']
                },
                files: {
                    'resource/admin/deploy/app.css': 'resource/admin/app/sass/app.scss'
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['last 2 version', 'ie 9', 'opera 12']
            },
            dist: {
                src: 'resource/admin/deploy/app.css'
            }
        },

		concat: {
			options: {
				separator: ''
			},
			dist: {
				src: ['resource/admin/libs/foundation/js/vendor/*.js','resource/admin/app/foundationJs/foundation.js','resource/admin/app/foundationJs/components/*.js'],
				dest: 'resource/admin/deploy/foundation.js'
			}
		}
//
//		csso: {
//			compress: {
//				files: {
//					'resource/admin/deploy/app.css': ['resource/admin/deploy/app.css']
//				}
//			}
//		},
//
//		uglify: {
//			options: {
//				banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
//			},
//			dist: {
//				files: {
//					'resource/admin/deploy/<%= pkg.name %>.js': ['<%= concat.dist.dest %>']
//				}
//			}
//		},

//      yuidoc: {
//            all: {
//                name: '<%= pkg.name %>',
//                description: '<%= pkg.description %>',
//                version: '<%= pkg.version %>',
//                url: '<%= pkg.homepage %>',
//                options: {
//                    paths: ['resource/admin/app', 'resource/admin/modules'],
//                    outdir: 'docs/frontend'
//                }
//            }
//        }
	});

    //подгружаем необходимые плагины
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-concat');
//    grunt.loadNpmTasks('grunt-contrib-uglify');
//    grunt.loadNpmTasks('grunt-contrib-jshint');
//    grunt.loadNpmTasks('grunt-csso');
//    grunt.loadNpmTasks("grunt-contrib-yuidoc");


	//регистрируем задачу
	grunt.registerTask('default', ['watch']); //задача по умолчанию, просто grunt
//    grunt.registerTask('deploy', ['sass', 'autoprefixer', 'csso', 'concat', 'uglify']);
//    grunt.registerTask("docs", ["yuidoc"]);
};