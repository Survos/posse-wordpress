module.exports = function (grunt) {

    grunt.initConfig({
        gitpull: {
            dev: {
                options: {}
            }
        },
    });

    grunt.loadNpmTasks('grunt-git');

    grunt.registerTask('default', ['gitpull']);
};
