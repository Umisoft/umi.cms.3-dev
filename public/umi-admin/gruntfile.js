module.exports = function(grunt){
    'use strict';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            scss: {
                files: ['styles/**/*.scss'],
                tasks: ['sass', 'autoprefixer', 'concat:development']
            },

            js: {
                files: ['application/**/*.*', 'partials/**/*.*', 'auth/**/*.*'],
                tasks: ['emberTemplates', 'requirejs:development']
            }
        },

        sass: {
            dev: {
                options: {
                    includePaths: ['vendor/foundation/scss/']
                },

                files: {
                    'development/css/styles.css': 'styles/main.scss'
                }
            }
        },

        emberTemplates:  {
            compile: {
                options: {
                    amd: 'Ember',
                    concatenate: true,
                    preprocess: function(source) {
                        return source.replace(/\s+/g, ' ');
                    },
                    templateRegistration: function(name, contents){
                        name = name.split('/');
                        name = name[name.length - 1];
                        name = name.replace(/\./g, '\/');
                        return 'Ember.TEMPLATES["UMI/' + name + '"] = ' + contents + ';';
                    }
                },
                files: {
                    "application/templates.compile.js": ['application/**/*.hbs', 'partials/**/*.hbs']
                }
            }
        },

        handlebars: {
            compile: {
                options: {
                    amd: 'Handlebars',
                    namespace: "UMI.Auth.TEMPLATES"
                },
                files: {
                    "auth/templates.compile.js": "auth/**/*.hbs"
                }
            }
        },

        requirejs: {
            development: {
                options: {
                    baseUrl: './',
                    stubModules: ['text'],
                    mainConfigFile: "main.js",
                    name: 'main',
                    out: 'development/main.js',
                    inlineText: true,
                    optimize: 'none',
                    exclude: [
                        'Modernizr',
                        'jquery',
                        'jqueryUI',
                        'Handlebars',
                        'Ember',
                        'DS',
                        'iscroll',
                        'ckEditor',
                        'timepicker',
                        'moment',
                        'elFinder'
                    ],

                    findNestedDependencies: true
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['last 2 version', 'Firefox >= 28', 'ie 9', 'opera 12']
            },

            dist: {
                src: 'development/css/styles.css'
            }
        },

        csso: {
            compress: {
                options: {
                    restructure: false
                },
                files: {
                    'production/css/styles.css': ['development/css/styles.css']
                }
            }
        },

        clean: {
            production: {
                src: ["production/*"]
            }
        },

        svgmin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'images/svg',
                    src: ['**/*.svg'],
                    dest: 'images/svgMinify'
                }]
            }
        },

        grunticon: {
            dockIcons: {
                files: [{
                    expand: true,
                    cwd: 'images/svg/dock/',
                    src: ['*.svg'],
                    dest: "development/css"
                }],

                options: {
                    datasvgcss: 'icons.dock.svg.css',
                    cssprefix: ".umi-dock-module-",
                    defaultWidth: '60px',
                    defaultHeight: '60px'
                }
            }
        },

        copy: {
            imagesProduction: {
                expand: true,
                cwd: 'development/images',
                src: ['**/*', '!dock_png/**', '!svg/**'],
                dest: 'production/images'
            },

            js: {
                expand: true,
                cwd: 'development',
                src: ['main.js'],
                dest: 'production'
            },

            vendorProduction: {
                expand: true,
                cwd: './',
                src: [
                    'vendor/requirejs/require.js',
                    'vendor/requirejs-text/text.js',
                    'vendor/jquery/dist/jquery.min.js',
                    'vendor/jquery/dist/jquery.min.map',
                    'vendor/jquery-ui/jquery-ui.min.js',
                    'vendor/modernizr/modernizr.js',
                    'vendor/handlebars/handlebars.min.js',
                    'vendor/ember/ember.min.js',
                    'vendor/ember-data/ember-data.min.js',
                    'vendor/ckeditor/**',
                    'vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.js',
                    'vendor/momentjs/min/moment-with-langs.min.js',
                    'vendorExtend/iscroll-probe-5.1.1.js',
                    'vendorExtend/elFinder.js'
                ],
                dest: 'production',
                rename: function(dest, src) {
                    if(/\.js$/.test(src)){
                        src = src.replace(/\.min/g, '');
                    }
                    return dest + '/' + src;
                }
            },

            vendorDevelopment: {
                expand: true,
                cwd: './',
                src: [
                    'vendor/requirejs/require.js',
                    'vendor/requirejs-text/text.js',
                    'vendor/jquery/dist/jquery.js',
                    'vendor/jquery-ui/jquery-ui.js',
                    'vendor/modernizr/modernizr.js',
                    'vendor/handlebars/handlebars.js',
                    'vendor/ember/ember.js',
                    'vendor/ember-data/ember-data.js',
                    'vendorExtend/iscroll-probe-5.1.1.js',
                    'vendor/ckeditor/**',
                    'vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.js',
                    'vendor/momentjs/min/moment-with-langs.js',
                    'vendorExtend/elFinder.js'
                ],
                dest: 'development'
            },

            imagesDevelopment: {
                expand: true,
                cwd: 'images',
                src: ['**'],
                dest: 'development/images'
            }
        },

        concat: {
            elFinder: {
                options: {
                    separator: ';'
                },

                src: [
                    'partials/fileManager/elFinder/js/elFinder.js',
                    'partials/fileManager/elFinder/js/jquery.elfinder.js',
                    'partials/fileManager/elFinder/js/elFinder.resources.js',
                    'partials/fileManager/elFinder/js/elFinder.options.js',
                    'partials/fileManager/elFinder/js/elFinder.history.js',
                    'partials/fileManager/elFinder/js/elFinder.command.js',

                    'partials/fileManager/elFinder/js/ui/overlay.js',
                    'partials/fileManager/elFinder/js/ui/workzone.js',
                    'partials/fileManager/elFinder/js/ui/navbar.js',
                    'partials/fileManager/elFinder/js/ui/dialog.js',
                    'partials/fileManager/elFinder/js/ui/tree.js',
                    'partials/fileManager/elFinder/js/ui/cwd.js',
                    'partials/fileManager/elFinder/js/ui/toolbar.js',
                    'partials/fileManager/elFinder/js/ui/button.js',
                    'partials/fileManager/elFinder/js/ui/uploadButton.js',
                    'partials/fileManager/elFinder/js/ui/viewbutton.js',
                    'partials/fileManager/elFinder/js/ui/searchbutton.js',
                    'partials/fileManager/elFinder/js/ui/sortbutton.js',
                    'partials/fileManager/elFinder/js/ui/panel.js',
                    'partials/fileManager/elFinder/js/ui/contextmenu.js',
                    'partials/fileManager/elFinder/js/ui/path.js',
                    'partials/fileManager/elFinder/js/ui/stat.js',
                    'partials/fileManager/elFinder/js/ui/places.js',

                    'partials/fileManager/elFinder/js/commands/*.js',
                    'partials/fileManager/elFinder/js/i18n/elfinder.ru.js'
                ],
                dest: 'libsStatic/elFinder.js'
            },

            development: {
                options: {
                    separator: '\n'
                },

                src: [
                    'development/css/styles.css',
                    'development/css/icons.dock.svg.css'
                ],

                dest: 'development/css/styles.css'

            }
        },

        uglify: {
            app: {
                files: {
                    'production/main.js': ['production/main.js'],
                    'production/vendorExtend/elFinder.js': ['production/vendorExtend/elFinder.js'],
                    'production/vendorExtend/iscroll-probe-5.1.1.js': ['production/vendorExtend/iscroll-probe-5.1.1.js'],
                    'production/vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.js': ['production/vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.js'],
                    'production/vendor/modernizr/modernizr.js': ['production/vendor/modernizr/modernizr.js'],
                    'production/vendor/requirejs/require.js': ['production/vendor/requirejs/require.js'],
                    'production/vendor/requirejs-text/text.js': ['production/vendor/requirejs-text/text.js']
                }
            }
        },

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

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-svgmin');
    grunt.loadNpmTasks('grunt-grunticon');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-csso');
    grunt.loadNpmTasks("grunt-contrib-yuidoc");
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-requirejs');
    grunt.loadNpmTasks('grunt-ember-templates');
    grunt.loadNpmTasks('grunt-contrib-handlebars');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['watch']);
    grunt.registerTask('dev', ['copy:vendorDevelopment', 'copy:imagesDevelopment', 'sass', 'autoprefixer', 'concat:development','emberTemplates', 'requirejs:development']);
    grunt.registerTask('pro', ['clean', 'csso', 'copy:imagesProduction', 'copy:js', 'copy:vendorProduction', 'uglify']);
    grunt.registerTask('docs', ['yuidoc']);
    grunt.registerTask('svg', ['svgmin', 'grunticon']);
};