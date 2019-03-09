module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      main: {
        options: {
          style: 'compressed',
          line_comments: false,
          line_numbers: false,
          sourcemap: 'none'
        },
        files: {
          'css/editor-style.css': 'sass/editor-style.scss',
          'css/print.css': 'sass/print.scss',
          'css/narrow-width.css': 'sass/responsive_narrow.scss',
          'css/default-width.css': 'sass/responsive_default.scss',
          'css/wide-width.css': 'sass/responsive_wide.scss',
          'style.css': 'sass/style.scss'
        }
      }
    },
    replace: {
      style: {
        options: {
          variables: {
            'author': '<%= pkg.author.name %>',
            'author_url': '<%= pkg.author.url %>',
            'version': '<%= pkg.version %>',
            'license': '<%= pkg.license.name %>',
            'license_version': '<%= pkg.license.version %>',
            'license_url': '<%= pkg.license.url %>',
            'name': '<%= pkg.name %>',
            'description': '<%= pkg.description %>',
            'homepage': '<%= pkg.homepage %>',
            'tags': '<%= pkg.keywords.join(", ") %>'
          },
          prefix: '@@'
        },
        files: [
          {expand: true, flatten: true, src: ['style.css'], dest: ''}
        ]
      }
    },
    wp_readme_to_markdown: {
      target: {
        files: {
          'readme.md': 'readme.txt'
        },
      },
    },
	makepot: {
      target: {
        options: {
          domainPath: 'languages',
          exclude: ['bin/.*', '.git/.*', 'vendor/.*', 'node_modules/.*'],
          potFilename: 'autonom.pot',
          type: 'wp-theme',
          updateTimestamp: true
        }
      }
    },
    watch: {
      styles: {
        files: ['**/*.scss'],
        tasks: ['default']
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-replace');
  grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
  grunt.loadNpmTasks('grunt-wp-i18n');

  // Default task(s).
  grunt.registerTask('default', ['sass', 'replace', 'wp_readme_to_markdown', 'makepot']);
};
