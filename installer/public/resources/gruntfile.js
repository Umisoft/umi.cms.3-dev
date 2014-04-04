module.exports = function(grunt){
    'use strict';

    //описываем конфигурацию
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'), //подгружаем package.json, чтобы использовать его данные

        watch: {
            scss: {
                files: ['app/scss/**/*.scss', 'app/foundationJs/**/*.js', 'app/components/fileManager/elFinder/**/*.*'],
                tasks: ['sass', 'concat', 'autoprefixer']
            }
        },

        sass: {
            dev: {
                options: {
                    includePaths: ['libs/foundation/scss/']
                },
                files: {
                    'build/css/app.css': 'app/scss/app.scss'
                }
            }
        },

        // Сохраняем svg в css
        grunticon: {
            myIcons: {
                files: [{
                    expand: true,
                    cwd: 'build/svg/icons/',
                    src: ['*.svg'],
                    dest: "build/css"
                }],
                options: {
                    datasvgcss: 'icons.data.svg.css',
                    cssprefix: ".icon-",
                    defaultWidth: "20px",
                    defaultHeight: "20px"
                }
            },

            dockIcons: {
                files: [{
                    expand: true,
                    cwd: 'build/svg/dockIcons/',
                    src: ['*.svg'],
                    dest: "build/css"
                }],
                options: {
                    datasvgcss: 'icons.dock.svg.css',
                    cssprefix: ".dock-icon-"
                }
            }
        },

        concat: {
            options: {
                separator: '\n'
            },
            foundation: {
                src: [
                    'app/foundationJs/foundation.js',
                    'app/foundationJs/components/foundation.offcanvas.js'
                ],
                dest: 'build/js/foundation.js'
            },
            elFinder: {
                options: {
                    separator: ';'
                },
                src: [
                    'app/components/fileManager/elFinder/jquery/jquery-ui-1.10.4.custom.min.js',
                    // Файлы перечислены намеренно, так как необходим определеный порядок соединения файлов
                    'app/components/fileManager/elFinder/js/elFinder.js',
                    'app/components/fileManager/elFinder/js/jquery.elfinder.js',
                    'app/components/fileManager/elFinder/js/elFinder.resources.js',
                    'app/components/fileManager/elFinder/js/elFinder.options.js',
                    'app/components/fileManager/elFinder/js/elFinder.history.js',
                    'app/components/fileManager/elFinder/js/elFinder.command.js',

                    'app/components/fileManager/elFinder/js/ui/overlay.js',
                    'app/components/fileManager/elFinder/js/ui/workzone.js',
                    'app/components/fileManager/elFinder/js/ui/navbar.js',
                    'app/components/fileManager/elFinder/js/ui/dialog.js',
                    'app/components/fileManager/elFinder/js/ui/tree.js',
                    'app/components/fileManager/elFinder/js/ui/cwd.js',
                    'app/components/fileManager/elFinder/js/ui/toolbar.js',
                    'app/components/fileManager/elFinder/js/ui/button.js',
                    'app/components/fileManager/elFinder/js/ui/uploadButton.js',
                    'app/components/fileManager/elFinder/js/ui/viewbutton.js',
                    'app/components/fileManager/elFinder/js/ui/searchbutton.js',
                    'app/components/fileManager/elFinder/js/ui/sortbutton.js',
                    'app/components/fileManager/elFinder/js/ui/panel.js',
                    'app/components/fileManager/elFinder/js/ui/contextmenu.js',
                    'app/components/fileManager/elFinder/js/ui/path.js',
                    'app/components/fileManager/elFinder/js/ui/stat.js',
                    'app/components/fileManager/elFinder/js/ui/places.js',

                    'app/components/fileManager/elFinder/js/commands/*.js',
                    'app/components/fileManager/elFinder/js/i18n/elfinder.ru.js'
                ],
                dest: 'build/js/elFinder.js'
            },
            //Объединяем стили с иконками
            css: {
                src: [
                    'build/css/app.css',
                    'build/css/icons.data.svg.css',
                    'build/css/icons.dock.svg.css'
                ],
                dest: 'build/css/styles.css'
            }
        },

        autoprefixer: {
            options: {
                browsers: ['last 2 version', 'ie 9', 'opera 12']
            },
            dist: {
                src: 'build/css/styles.css'
            }
        },

        csso: {
            compress: {
                files: {
                    'deploy/styles.css': ['build/css/styles.css']
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

    //подгружаем необходимые плагины
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-grunticon');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-csso');
    grunt.loadNpmTasks("grunt-contrib-yuidoc");


    //регистрируем задачу
    grunt.registerTask('default', ['watch']); //задача по умолчанию, просто grunt
    grunt.registerTask('deploy', ['grunticon', 'sass', 'concat', 'autoprefixer', 'csso']);
    grunt.registerTask("docs", ["yuidoc"]);
};