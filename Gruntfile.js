module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-composer');

    grunt.initConfig({


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

    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-git');

    grunt.registerTask('dev', ['bower:install', 'copy:dev_config']);
    grunt.registerTask('default', ['bower:install', 'copy:dev_config']);

};
