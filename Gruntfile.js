module.exports = function (grunt) {

    grunt.initConfig({


        gitpull: {
            dev: {
                options: {}
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('dev', []);
    grunt.registerTask('default', ['cprod:dev_config']);

};
