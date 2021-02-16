module.exports = function(grunt) {
  const sass = require('node-sass');

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      main: {
        options: {
          implementation: sass,
          outputStyle: 'compressed',
          sourceComments: false,
          sourceMap: true
        },
        files: {
          'assets/css/editor-style.css': 'assets/sass/editor-style.scss',
          'assets/css/print.css': 'assets/sass/print.scss',
          'assets/css/narrow-width.css': 'assets/sass/responsive_narrow.scss',
          'assets/css/default-width.css': 'assets/sass/responsive_default.scss',
          'assets/css/wide-width.css': 'assets/sass/responsive_wide.scss',
          'style.css': 'assets/sass/style.scss'
        }
      }
    },
    'string-replace': {
      style: {
        options: {
          replacements: [
            {
              pattern: '@@author',
              replacement: '<%= pkg.author.name %>'
            },
            {
              pattern: '@@author_url',
              replacement: '<%= pkg.author.url %>'
            },
            {
              pattern: '@@version',
              replacement: '<%= pkg.version %>'
            },
            {
              pattern: '@@license',
              replacement: '<%= pkg.license.name %>'
            },
            {
              pattern: '@@license_url',
              replacement: 'https://opensource.org/licenses/<%= pkg.license %>'
            },
            {
              pattern: '@@name',
              replacement: '<%= pkg.name %>'
            },
            {
              pattern: '@@description',
              replacement: '<%= pkg.description %>'
            },
            {
              pattern: '@@homepage',
              replacement: '<%= pkg.homepage %>'
            },
            {
              pattern: '@@tags',
              replacement: '<%= pkg.keywords.join(", ") %>'
            }
          ]
        },
        files: [
          {
            expand: true,
            flatten: true,
            src: 'style.css',
            dest: ''
          }
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
          exclude: ['bin/.*', '.git/.*', 'vendor/.*', 'node_modules/.*', '_build/.*'],
          potFilename: 'autonomie.pot',
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
    },
    copy: {
      build: {
        src: [
          '**',
          '!node_modules/**',
          '!.**',
          '!Gruntfile.js',
          '!package.json',
          '!package-lock.json',
          '!composer.json',
          '!docker-compose.yml',
          '!phpcs.xml',
          '!readme.md',
          '!**/**.map'
        ],
        dest: '_build/',
      },
    },
    clean: {
      build: {
        src: ['_build/']
      }
    },
    compress: {
      build: {
        options: {
          archive: '_build/<%= pkg.name %>.zip'
        },
        cwd: '_build/',
        src: ['**/*'],
        dest: '/',
        expand: true
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-string-replace');
  grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-compress');

  // Default task(s).
  grunt.registerTask('default', ['sass', 'string-replace', 'wp_readme_to_markdown', 'makepot']);

  grunt.registerTask('build', ['default', 'clean:build', 'copy:build', 'compress:build']);
};
