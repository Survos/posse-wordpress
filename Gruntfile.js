module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-composer');

    grunt.initConfig({

        compass: {
            dist: {
                options: {
                    sassDir: 'src/AppBundle/Resources/scss',
                    cssDir: 'web/css',
                    outputStyle: 'compressed'
                }
            },
            dev: {
                options: {
                    sassDir: 'src/AppBundle/Resources/scss',
                    cssDir: 'web/css',
                    outputStyle: 'expanded',
                    watch: false
                }
            },
            devpoll: {
                options: {
                    sassDir: 'src/AppBundle/Resources/scss',
                    cssDir: 'web/css',
                    outputStyle: 'expanded',
                    watch: true
                }
            }
        },

        bower: {
            install: {

                options: {
                    layout: 'byComponent',
                    targetDir: './web/components',
                    verbose: false
                }
            }

        },

        "git-describe": {
            options: {},
            version: {}
        },


        'sf2-console': {
            options: {
                bin: 'app/console'
            },
            schema: {
                cmd: 'doctrine:schema:update',
                args: {
                    force: true
                }
            }
        },

        composer: {
            options: {}
        },

        gitpull: {
            dev: {
                options: {}
            }
        },
        copy: {
            dev_config: {
                src: 'config/wp-config.php.dev',
                dest: 'src/wp-config.php',
                options: {
                    //process: function (content, srcpath) {
                    //    return content.replace(/@GITVERSION@/g, grunt.config("meta.revision"));
                    //}
                }
            },

        },
    });

    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-bower');
    grunt.loadNpmTasks('grunt-bower-task');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-preprocess');
    grunt.loadNpmTasks('grunt-symfony2');
    grunt.loadNpmTasks('grunt-git');
    grunt.loadNpmTasks('grunt-git-describe');


    grunt.registerTask('create-staging-config', function () {
        var config = {
            "parameters": {
                "database_name": "jenkins_fda",
                "database_host": "pg1.survos.com",
                "database_user": "jenkins",
                "database_password": "6mmH^%K8@1!K"
            }
        };
        YAML = require('yamljs');
        grunt.file.write('app/config/parameters.yml', YAML.stringify(config));

    });

    grunt.registerTask('update-revision', function () {
        grunt.event.once('git-describe', function (rev) {
            grunt.options.set();
        });
        grunt.task.run('git-describe');
    });

    grunt.registerTask('dev', ['bower:install', 'copy:dev_config']);
    grunt.registerTask('default', ['gitpull:dev', 'update-revision', 'bower:install', 'composer:install', 'sf2-console:schema', 'compass:dev']);

};
