module.exports = function(grunt){
    'use strict';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            scss: {
                files: ['styles/**/*.scss'],
                tasks: ['sass', 'autoprefixer']
            },

            js: {
                files: ['application/**/*.*', 'partials/**/*.*'],
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
            },
            production: {
                options: {
                    baseUrl: './production',
                    stubModules: ['text'],
                    mainConfigFile: "main.js",
                    name: 'main',
                    inlineText: true,
                    optimize: 'none',
                    out: 'development/js/app.js',
                    findNestedDependencies: true,
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
                    paths: {
                        text:       'vendor/text',
                        jquery:     'vendor/jquery.min',
                        jqueryUI:   'vendor/jquery-ui.min',
                        Modernizr:  'vendor/modernizr',
                        Handlebars: 'vendor/handlebars.min',
                        Ember:      'vendor/ember.min',
                        DS:         'vendor/ember-data.min',
                        iscroll:    'vendor/iscroll-probe-5.1.1',
                        ckEditor:   'vendor/ckeditor',
                        timepicker: 'vendor/jquery-ui-timepicker-addon',
                        moment:     'vendor/moment-with-langs.min',
                        elFinder:   'vendor/elFinder'
                    }
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
                    cwd: 'images/svgMinify/dock/',
                    src: ['*.svg'],
                    dest: "development/css"
                }],

                options: {
                    datasvgcss: 'icons.dock.svg.css',
                    cssprefix: ".umi-dock-module-"
                }
            }
        },

        copy: {
            png: {
                expand: true,
                cwd: 'development/img',
                src: ['**'],
                dest: 'production/img'
            },

            svg: {
                expand: true,
                cwd: 'development/svg',
                src: ['animation/**', 'elements/**'],
                dest: 'production/svg/'
            },

            styles: {
                expand: true,
                cwd: 'development/css',
                src: ['styles.css'],
                dest: 'production/css'
            },

            js: {
                expand: true,
                cwd: 'development/js',
                src: ['app.js'],
                dest: 'production/js'
            },

            vendorProduction: {
                expand: true,
                flatten: true,
                cwd: './',
                src: [
                    'vendor/requirejs/require.js',
                    'vendor/requirejs-text/text.js',
                    'vendor/jquery/dist/jquery.min.js',
                    'vendor/jquery-ui/jquery-ui.min.js',
                    'vendor/modernizr/modernizr.js',
                    'vendor/handlebars/handlebars.min.js',
                    'vendor/ember/ember.min.js',
                    'vendor/ember-data/ember-data.min.js',
                    'vendor/ckeditor/ckeditor.js',
                    'vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.js',
                    'vendor/momentjs/min/moment-with-langs.min.js',
                    'vendorExtend/iscroll-probe-5.1.1.js',
                    'vendorExtend/elFinder.js'
                ],
                dest: 'production/libs'
            },

            vendorDevelopment: {
                expand: true,
                cwd: './',
                src: [
                    'vendor/requirejs/require.js',
                    'vendor/requirejs-text/text.js',
                    'vendor/jquery/dist/jquery.js',
                    'vendor/jquery-ui/jquery-ui.min.js',
                    'vendor/modernizr/modernizr.js',
                    'vendor/handlebars/handlebars.js',
                    'vendor/ember/ember.js',
                    'vendor/ember-data/ember-data.js',
                    'vendorExtend/iscroll-probe-5.1.1.js',
                    'vendor/ckeditor/ckeditor.js',
                    'vendor/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.js',
                    'vendor/momentjs/min/moment-with-langs.min.js',
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
                    'production/js/app.js': ['production/js/app.js'],
                    'production/libs/elFinder.js': ['production/libs/elFinder.js'],
                    'production/libs/iscroll-probe-5.1.1.js': ['production/libs/iscroll-probe-5.1.1.js'],
                    'production/libs/jquery-ui-timepicker-addon.js': ['production/libs/jquery-ui-timepicker-addon.js'],
                    'production/libs/modernizr.js': ['production/libs/modernizr.js'],
                    'production/libs/require.js': ['production/libs/require.js'],
                    'production/libs/text.js': ['production/libs/text.js']
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
    grunt.registerTask('development', ['copy:vendorDevelopment', 'copy:imagesDevelopment', 'svgmin', 'grunticon', 'sass', 'autoprefixer', 'concat:development','emberTemplates', 'requirejs:development']);
    grunt.registerTask('production', ['clean', 'sass', 'autoprefixer', 'csso', 'copy:png', 'copy:js', 'copy:vendorProduction', 'emberTemplates', 'requirejs:production', 'uglify']);
    grunt.registerTask('docs', ['yuidoc']);
};