module.exports = function(grunt){
    'use strict';

    //описываем конфигурацию
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'), //подгружаем package.json, чтобы использовать его данные

        watch: {
            sass: {
                files: ['app/sass/**/*.scss', 'app/foundationJs/**/*.js', 'app/components/fileManager/elFinder/**/*.*'],
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
                separator: '\n'
            },
            foundation: {
                src: [
                    'app/foundationJs/foundation.js',
                    'app/foundationJs/components/foundation.offcanvas.js'
                ],
                dest: 'deploy/foundation.js'
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
                dest: 'deploy/elFinder.js'
            },
            //Объединяем стили с иконками
            css: {
                src: [
                    'deploy/app.css',
                    'deploy/icons.data.svg.css',
                    'deploy/icons.dock.svg.css',
                    'app/components/fileManager/elFinder/**/*.css'
                ],
                dest: 'deploy/app.css'
            }
        },

        csso: {
            compress: {
                files: {
                    'deploy/app.css': ['deploy/app.css']
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
        },
        // Сохраняем svg в css
        grunticon: {
            myIcons: {
                files: [{
                    expand: true,
                    cwd: 'deploy/img/svg',
                    src: ['*.svg'],
                    dest: "deploy"
                }],
                options: {
                    //                      datasvgcss: "icons.css",
                    cssprefix: ".icon-",
                    defaultWidth: "20px",
                    defaultHeight: "20px"
                    //                      previewTemplate: '<script>{{{loaderText}}} grunticon(["icons.css"]);</script>{{#each icons}}{{#with this}}<pre><code>{{prefix}}{{name}}:</code></pre><div class="{{prefixClass}}{{name}}" style="width: {{width}}px; height: {{height}}px;" ></div><hr/>{{/with}}{{/each}}'
                }
            },

            dockIcons: {
                files: [{
                    expand: true,
                    datasvgcss: 'icons.data.svg.css',
                    cwd: 'deploy/img/svg',
                    src: ['*.svg'],
                    dest: "deploy"
                }],
                options: {
                    datasvgcss: 'icons.dock.svg.css',
                    cssprefix: ".dock-icon-",
                    defaultWidth: "20px",
                    defaultHeight: "20px"
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
    grunt.registerTask('deploy', ['grunticon', 'sass', 'autoprefixer', 'csso', 'concat']);
    grunt.registerTask("docs", ["yuidoc"]);
};