module.exports = function(grunt) {
     grunt.loadNpmTasks('grunt-angular-gettext');

    grunt.initConfig({
        nggettext_extract:{
            pot:{
                files:{
                    'translate/template.pot' : ['../public/apps/**/*.html','../public/apps/**/*.js','../resources/views/**/*.blade.php']
                }
            }
        },
        nggettext_compile:{
            all:{
                files:{
                    'translate/translation.js' : ['po2/*.po']
                }
            }
        }
    })
    grunt.registerTask('default', ['nggettext_extract', ]);
    //grunt.registerTask('default', [ 'nggettext_compile']);
 }
