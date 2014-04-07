module.exports = function(grunt){
    'use strict';

    //описываем конфигурацию
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'), //подгружаем package.json, чтобы использовать его данные

        watch: {
            scss: {
                files: ['scss/**/*.scss', 'partials/fileManager/elFinder/**/*.*'],
                tasks: ['sass', 'concat', 'autoprefixer']
            }
        },

        sass: {
            dev: {
                options: {
                    includePaths: ['libs/foundation/scss/']
                },
                files: {
                    'build/css/app.css': 'scss/app.scss'
                }
            }
        },

        //Чистим полностью папку deploy для уверенности, что не останется старых лишних файлов и проект будет свеж
        clean: {
            deploy: {
                src: ["deploy/*"]
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

        //Копируем растровые изображения
        copy: {
            png: {
                expand: true,
                cwd: 'build/img',
                src: ['**'],
                dest: 'deploy/img/'
            },

            svg: {
                expand: true,
                cwd: 'build/svg',
                src: ['animation/**', 'elements/**'],
                dest: 'deploy/svg/'
            }
        },

        concat: {
            options: {
                separator: '\n'
            },
            elFinder: {
                options: {
                    separator: ';'
                },
                src: [
                    'partials/fileManager/elFinder/jquery/jquery-ui-1.10.4.custom.min.js',
                    // Файлы перечислены в необходимом порядке соединения
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


    /*
    *
    * grunt-contrib-clean
    * grunt-contrib-copy
    *
    * */

    //подгружаем необходимые плагины
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-grunticon');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-csso');
    grunt.loadNpmTasks("grunt-contrib-yuidoc");
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');


    //регистрируем задачу
    grunt.registerTask('default', ['watch']); //задача по умолчанию, просто grunt
    grunt.registerTask('deploy', ['clean', 'copy', 'grunticon', 'sass', 'concat', 'autoprefixer', 'csso']);
    grunt.registerTask("docs", ["yuidoc"]);
};