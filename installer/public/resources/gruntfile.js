module.exports = function(grunt){
    'use strict';

    //описываем конфигурацию
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'), //подгружаем package.json, чтобы использовать его данные

        watch: {
            sass: {
                files: ['app/sass/**/*.scss', 'app/foundationJs/**/*.js'],
                tasks: ['sass', 'concat', 'autoprefixer']
            }
        },

        sass: {
            dev: {
                options: {
                    includePaths: ['libs/foundation/scss/']
                },
                files: {
                    'deploy/app.css': 'app/sass/app.scss'
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['last 2 version', 'ie 9', 'opera 12']
            },
            dist: {
                src: 'deploy/app.css'
            }
        },

        concat: {
            options: {
                separator: ''
            },
            dist: {
                src: [
                    'app/foundationJs/foundation.js',
                    'app/foundationJs/components/*.js'
                ],
                dest: 'deploy/foundation.js'
            }
        },
        //
        //		csso: {
        //			compress: {
        //				files: {
        //					'deploy/app.css': ['deploy/app.css']
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
        //					'deploy/<%= pkg.name %>.js': ['<%= concat.dist.dest %>']
        //				}
        //			}
        //		},

        yuidoc: {
            compile: {
                name: '<%= pkg.name %>',
                description: '<%= pkg.description %>',
                version: '<%= pkg.version %>',
                url: '<%= pkg.homepage %>',
                options: {
                    paths: 'app',
                    outdir: 'docs/frontend'
                }
            }
      }
    });

    //подгружаем необходимые плагины
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-concat');
    //    grunt.loadNpmTasks('grunt-contrib-uglify');
    //    grunt.loadNpmTasks('grunt-contrib-jshint');
    //    grunt.loadNpmTasks('grunt-csso');
    grunt.loadNpmTasks("grunt-contrib-yuidoc");


    //регистрируем задачу
    grunt.registerTask('default', ['watch']); //задача по умолчанию, просто grunt
    //    grunt.registerTask('deploy', ['sass', 'autoprefixer', 'csso', 'concat', 'uglify']);
    grunt.registerTask("docs", ["yuidoc"]);
};