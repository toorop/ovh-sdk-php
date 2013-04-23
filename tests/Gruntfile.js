module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        phpunit: {
            classes: {
                dir: 'tests/'
            },
            options: {
                bin: '../vendor/phpunit/phpunit/phpunit.php',
                bootstrap: 'phpunit.php',
                colors: true
            }
        },

        watch: {
            tests: {
                files: ['../src/Ovh/**/*.php','tests/*.php'],
                tasks: ['phpunit']
            }
        }
    });

    // tasks
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['phpunit']);

}
