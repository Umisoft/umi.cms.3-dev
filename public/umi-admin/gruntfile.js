module.exports = function(grunt) {
    'use strict';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            scss: {
                files: ['styles/**/*.scss'],
                tasks: ['sass:dev', 'autoprefixer', 'concat:development']
            },

            js: {
                files: ['application/**/*.*', 'partials/**/*.*', 'auth/**/*.*', 'library/**/*.*'],
                tasks: ['emberTemplates:admin', 'requirejs:development']
            },

            eip: {
                files: ['module/eip/**/*.*'],
                tasks: ['eip']
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
            },
            eip: {
                files: {
                    'development/module/eip/main.css': 'module/eip/main.scss'
                }
            }
        },

        emberTemplates: {
            admin: {
                options: {
                    amd: 'Ember',
                    concatenate: true,
                    preprocess: function(source) {
                        return source.replace(/\s+/g, ' ');
                    },
                    templateRegistration: function(name, contents) {
                        name = name.split('/');
                        name = name[name.length - 1];
                        name = name.replace(/\./g, '\/');
                        return 'Ember.TEMPLATES["UMI/' + name + '"] = ' + contents + ';';
                    }
                },
                files: {
                    "application/templates.compile.js": ['application/**/*.hbs', 'partials/**/*.hbs']
                }
            },
            eip: {
                options: {
                    amd: false,
                    concatenate: true,
                    preprocess: function(source) {
                        return source.replace(/\s+/g, ' ');
                    },
                    templateRegistration: function(name, contents) {
                        name = name.split('/');
                        name = name[name.length - 1];
                        name = name.replace(/\./g, '\/');
                        return 'Ember.TEMPLATES["UMI/' + name + '"] = ' + contents + ';';
                    }
                },
                files: {
                    'development/module/eip/templates.compile.js': ['module/eip/**/*.hbs']
                }
            }
        },

        handlebars: {
            compile: {
                options: {
                    amd: 'Handlebars',
                    namespace: 'UMI.Auth.TEMPLATES'
                },
                files: {
                    'auth/templates.compile.js': 'auth/**/*.hbs'
                }
            }
        },

        requirejs: {
            eip: {
                options: {
                    baseUrl: './',
                    namespace: 'EIP',
                    mainConfigFile: 'module/eip/common.js',
                    out: 'development/module/eip/main.js',
                    name: 'module/eip/common',
                    inlineText: true,
                    optimize: 'none',
                    findNestedDependencies: true,
                    exclude: [
                        'jquery', 'Handlebars', 'Ember', 'templates'
                    ]
                }
            },
            development: {
                options: {
                    baseUrl: './',
                    stubModules: ['text'],
                    mainConfigFile: 'main.js',
                    name: 'main',
                    out: 'development/main.js',
                    inlineText: true,
                    optimize: 'none',
                    exclude: [
                        'Modernizr', 'jquery', 'jqueryUI', 'Handlebars', 'Ember', 'DS', 'iscroll', 'ckEditor',
                        'timepicker', 'moment', 'elFinder', 'Foundation', 'FastClick', 'datepickerI18n',
                        'timepickerI18n'
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
                src: ['production/*']
            }
        },

        svgmin: {
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'images/svg',
                        src: ['**/*.svg'],
                        dest: 'images/svgMinify'
                    }
                ]
            }
        },

        grunticon: {
            dockIcons: {
                files: [
                    {
                        expand: true,
                        cwd: 'images/svg/dock/',
                        src: ['*.svg'],
                        dest: 'development/css'
                    }
                ],

                options: {
                    datasvgcss: 'icons.dock.svg.css',
                    cssprefix: '.umi-dock-module-',
                    defaultWidth: '60px',
                    defaultHeight: '60px'
                }
            }
        },

        copy: {
            vendorDevelopment: {
                expand: true,
                cwd: './',
                src: [
                    'vendor/requirejs/require.js', 'vendor/modernizr/modernizr.custom.js', 'vendor/requirejs-text/text.js',
                    'vendor/jquery/dist/jquery.js', 'vendor/jquery-ui/jquery-ui.js', 'vendor/handlebars/handlebars.js',
                    'vendor/ember/ember.js', 'vendor/ember-data/ember-data.js', 'vendor/fastclick/lib/fastclick.js',
                    'vendor/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.js',
                    'vendor/jqueryui-timepicker-addon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js',
                    'vendor/iscroll/build/iscroll-probe.js', 'vendor/momentjs/min/moment-with-langs.js', 'library/**'
                ],
                dest: 'development'
            },

            imagesDevelopment: {
                expand: true,
                cwd: 'images',
                src: ['**'],
                dest: 'development/images'
            },

            imagesProduction: {
                expand: true,
                cwd: 'development/images',
                src: ['**/*', '!dock_png/**', '!svg/**', 'svg/elements/**'],
                dest: 'production/images'
            },

            jsProduction: {
                expand: true,
                cwd: 'development',
                src: ['main.js'],
                dest: 'production'
            },

            vendorProduction: {
                expand: true,
                cwd: 'development',
                src: [
                    'vendor/**',
                    'library/**'
                ],
                dest: 'production'
            }
        },

        wrap: {
            'eip-requirejs': {
                src: 'vendor/requirejs/require.js',
                dest: 'development/module/eip',
                options: {
                    wrapper: ['var EIP = {}; (function () {\n', '\n this.define = define; this.require = require; this.requirejs = requirejs;}.call(EIP));']
                }
            },
            'eip-jquery': {
                src: 'vendor/jquery/dist/jquery.js',
                dest: 'development/module/eip',
                options: {
                    wrapper: ['EIP.define("jquery", [], function () { var define; var _jQuery;', 'return _jQuery;\n});']
                }
            },
            'eip-handlebars': {
                src: 'vendor/handlebars/handlebars.js',
                dest: 'development/module/eip',
                options: {
                    wrapper: ['EIP.define("Handlebars", [], function () {', 'window.Handlebars = Handlebars;\nreturn Handlebars;\n});']
                }
            },
            'eip-ember': {
                src: 'vendor/ember/ember.js',
                dest: 'development/module/eip',
                options: {
                    wrapper: ['EIP.define("Ember", ["Handlebars"], function (Handlebars) {', '\nreturn Ember;});']
                }
            },
            'eip-hbs': {
                src: 'development/module/eip/templates.compile.js',
                dest: 'development/module/eip/templates.compile.js',
                options: {
                    wrapper: ['EIP.define("templates", ["Ember"], function (Ember) {', '});']
                }
            }
        },

        'string-replace': {
            'eip-requirejs': {
                files: {
                    'development/module/eip/vendor/requirejs/require.js': 'development/module/eip/vendor/requirejs/require.js'
                },
                options: {
                    replacements: [{
                        pattern: 'var requirejs, require, define;',
                        replacement: 'var requirejs, require = {skipDataMain: true}, define;'
                    }]
                }
            },

            'eip-jquery': {
                files: {
                    'development/module/eip/vendor/jquery/dist/jquery.js': 'development/module/eip/vendor/jquery/dist/jquery.js'
                },
                options: {
                    replacements: [{
                        pattern: 'factory( global );',
                        replacement: '_jQuery = factory( global, true );'
                    }]
                }
            }
        },

        concat: {
            eip: {
                options: {
                    separator: '\n'
                },

                src: [
                    'development/module/eip/vendor/requirejs/require.js',
                    'development/module/eip/vendor/**/*.js',
                    'development/module/eip/templates.compile.js',
                    'development/module/eip/main.js'
                ],

                dest: 'development/module/eip/main.js'
            },
            development: {
                options: {
                    separator: '\n'
                },

                src: [
                    'development/css/styles.css', 'development/css/icons.dock.svg.css'
                ],

                dest: 'development/css/styles.css'
            },

            foundation: {
                options: {
                    separator: '\n'
                },

                src: ['library/foundation/foundation.core.js', 'library/foundation/foundation.dropdown.extend.js'],

                dest: 'library/foundation/foundation.js'
            },

            jqueryUiI18n: {
                options: {
                    separator: '\n'
                },

                src: ['vendor/jquery-ui/ui/i18n/datepicker-en-GB.js', 'vendor/jquery-ui/ui/i18n/datepicker-ru.js'],

                dest: 'library/jquery-ui/datepicker-i18n.js'
            },

            modernizr: {
                options: {
                    separator: '\n'
                },

                src: ['vendor/modernizr/modernizr.js', 'vendor/modernizr/feature-detects/css-calc.js'],

                dest: 'library/modernizr/modernizr.custom.js'
            }
        },

        uglify: {
            productionApp: {
                options: {
                    mangle: {
                        except: ['UMI']
                    },
                    sourceMap: true,
                    compress: {
                        drop_console: true
                    }
                },
                files: [
                    {
                        'production/main.js': ['production/main.js']
                    }
                ]
            },
            productionVendor: {
                files: [
                    {
                        expand: true,
                        cwd: 'production/vendor',
                        src: '**/*.js',
                        dest: 'production/vendor'
                    },
                    {
                        expand: true,
                        cwd: 'production/library',
                        src: '**/*.js',
                        dest: 'production/library'
                    }
                ]
            },
            eip: {
                files: [
                    {
                        'production/module/eip/main.js': 'development/module/eip/main.js'
                    }
                ]
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
    grunt.loadNpmTasks('grunt-contrib-yuidoc');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-requirejs');
    grunt.loadNpmTasks('grunt-ember-templates');
    grunt.loadNpmTasks('grunt-contrib-handlebars');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-wrap');
    grunt.loadNpmTasks('grunt-string-replace');

    grunt.registerTask('default', ['watch']);

    grunt.registerTask('svg', ['grunticon']);

    grunt.registerTask('foundation', ['concat:foundation']);

    grunt.registerTask('modernizr', ['concat:modernizr']);

    grunt.registerTask('dev', [
        'copy:vendorDevelopment', 'copy:imagesDevelopment', 'sass:dev', 'autoprefixer', 'concat:development',
        'emberTemplates:admin', 'requirejs:development'
    ]);

    grunt.registerTask('pro', ['clean', 'csso', 'copy:imagesProduction', 'copy:jsProduction', 'copy:vendorProduction',
        'uglify']);

    grunt.registerTask('docs', ['yuidoc']);

    grunt.registerTask('eip', ['emberTemplates:eip', 'requirejs:eip', 'wrap', 'string-replace', 'concat:eip', 'sass:eip']);
};