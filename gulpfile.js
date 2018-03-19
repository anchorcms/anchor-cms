'use strict';

/*
 global module,
 require
 */

const path         = require('path'),
      gulp         = require('gulp'),
      plumber      = require('gulp-plumber'),
      rename       = require('gulp-rename'),
      autoprefixer = require('gulp-autoprefixer'),
      babel        = require('gulp-babel'),
      concat       = require('gulp-concat'),

      /**
       * @TODO JSHint is disabled for now, JS will be refactored later on
       */
      // jshint       = require('gulp-jshint'),
      uglify       = require('gulp-uglify'),
      imageMin     = require('gulp-imagemin'),
      cache        = require('gulp-cache'),
      cleanCss     = require('gulp-clean-css'),
      scss         = require('gulp-sass'),
      scssLint     = require('gulp-scss-lint'),
      sourceMaps   = require('gulp-sourcemaps'),
      browserSync  = require('browser-sync');

/**
 * holds the configuration data.
 * for now, there are two presets:
 *  - development: writes out beautified source code with maps, compatibility reduced
 *  - production: writes out minified and optimized source code without maps
 */
const config = {
  path:        {
    base: path.join(__dirname, 'anchor', 'views', 'assets'),
    scss: 'scss',
    css:  'css',
    js:   'js',
    img:  'img'
  },
  development: {
    autoprefixer: 'last 1 versions',
    babel:        {
      presets: ['env']
    },
    cleanCss:     {
      compatibility: '*',
      format:        'beautify',
      inline:        ['local'],
      level:         1
    },
    imageMin:     {
      optimizationLevel: 3,
      progressive:       true,
      interlaced:        true
    },
    scss:         {
      outputStyle:    'nested',
      sourceComments: true,
      sourceMap:      true
    },
    scssLint:     {
      config: 'scss-lint.yml'
    },
    rename:       {
      suffix: '.min'
    },
    uglify:       {
      warnings: 'verbose',
      compress: false,
      output:   {
        beautify: true
      }
    }
  },
  production:  {
    autoprefixer: 'last 3 versions',
    babel:        {
      presets: ['env']
    },
    cleanCss:     {
      compatibility: 'ie9',
      inline:        ['local', 'remote', '!fonts.googleapis.com'],
      level:         2
    },
    imageMin:     {
      optimizationLevel: 3,
      progressive:       true,
      interlaced:        true
    },
    scss:         {
      outputStyle: 'compressed',
      sourceMap:   true
    },
    rename:       {
      suffix: '.min'
    },
    uglify:       {
      warnings: false,
      compress: true,
      mangle:   true
    }
  },

  get scssPath() {
    return path.join(this.path.base, this.path.scss)
  },

  get cssPath() {
    return path.join(this.path.base, this.path.css)
  },

  get jsPath() {
    return path.join(this.path.base, this.path.js)
  },

  get imgPath() {
    return path.join(this.path.base, this.path.img)
  }
};

/**
 * starts browserSync
 */
gulp.task('browser-sync', () => browserSync({
    server: {
      baseDir: "./"
    }
  })
);

/**
 * reloads the browser
 */
gulp.task('bs-reload', () => browserSync.reload());

/**
 * optimizes images
 */
gulp.task('images', () => gulp.src(path.join(config.imgPath, '**', '*'))
  .pipe(cache(imageMin(config.development.imageMin)))
  .pipe(gulp.dest(config.imgPath))
);

/**
 * compiles SCSS
 */
gulp.task('styles', () => gulp.src(path.join(config.scssPath, '**', '*.scss'))
  .pipe(plumber({
    errorHandler: error => console.log(error)
  }))
  .pipe(scssLint(config.development.scssLint))
  .pipe(sourceMaps.init({base: config.path}))
  .pipe(scss(config.development.scss))
  .pipe(autoprefixer(config.development.autoprefixer))
  .pipe(cleanCss(config.development.cleanCss))
  .pipe(rename(config.development.rename))
  .pipe(sourceMaps.write('.', {
    includeContent: false,
    sourceRoot:     '../scss'
  }))
  .pipe(gulp.dest(config.cssPath))
  .pipe(browserSync.reload({stream: true}))
);

/**
 * compiles SCSS
 */
gulp.task('styles:prod', () => gulp.src(path.join(config.scssPath, '**', '*.scss'))
  .pipe(plumber({
    errorHandler: error => {
      console.log("ERROR:");
      console.error(error.message);
      // this.emit('end');
    }
  }))
  .pipe(scss(config.production.scss))
  .pipe(autoprefixer(config.production.autoprefixer))
  .pipe(rename(config.production.rename))
  .pipe(cleanCss(config.production.cleanCss))
  .pipe(gulp.dest(config.cssPath))
  .pipe(browserSync.reload({stream: true}))
);

/**
 * compiles javascript
 */
gulp.task('scripts', () => gulp.src(path.join(config.jsPath, '**', '*.js'))
  .pipe(plumber({
    errorHandler: error => {
      console.log("ERROR:");
      console.error(error.message);
      // this.emit('end');
    }
  }))
  // .pipe(jshint())
  // .pipe(jshint.reporter('default'))
  .pipe(concat('main.js'))
  .pipe(babel(config.development.babel))
  .pipe(gulp.dest(config.jsPath))
  .pipe(rename(config.development.rename))
  .pipe(uglify(config.development.uglify))
  .pipe(gulp.dest(config.jsPath))
  .pipe(browserSync.reload({stream: true}))
);

/**
 * compiles javascript
 */
gulp.task('scripts:prod', () => gulp.src(path.join(config.jsPath, '**', '*.js'))
  .pipe(plumber({
    errorHandler: error => {
      console.log("ERROR:");
      console.error(error.message);
      // this.emit('end');
    }
  }))
  // .pipe(jshint())
  // .pipe(jshint.reporter('default'))
  .pipe(concat('main.js'))
  .pipe(babel(config.production.babel))
  .pipe(gulp.dest(config.jsPath))
  .pipe(rename(config.production.rename))
  .pipe(uglify(config.production.uglify))
  .pipe(gulp.dest(config.jsPath))
  .pipe(browserSync.reload({stream: true}))
);

/**
 * watches source files
 */
gulp.task('watch', ['browser-sync'], () => {
  gulp.watch(path.join(config.scssPath, '**', '*.scss'), ['styles']);
  gulp.watch(path.join(config.jsPath, '**', '*.js'), ['scripts']);
  gulp.watch(path.join(__dirname, '**', '*.php'), ['bs-reload']);
});

gulp.task('help', () => {
  console.log([
    '⚓\tAnchorCMS asset compilation script',
    '',
    'Available tasks:',
    '  \x1b[36mimages\x1b[0m\t\x1b[2mOptimizes images\x1b[0m',
    '  \x1b[36mstyles\x1b[0m\t\x1b[2mCompiles SCSS for development\x1b[0m',
    '  \x1b[36mstyles:prod\x1b[0m\t\x1b[2mCompiles SCSS for production\x1b[0m',
    '  \x1b[36mscripts\x1b[0m\t\x1b[2mCompiles JavaScript for development\x1b[0m',
    '  \x1b[36mscripts:prod\x1b[0m\t\x1b[2mCompiles JavaScript for production\x1b[0m',
    '  \x1b[36mwatch\x1b[0m\t\t\x1b[2mWatches source files and starts browserSync\x1b[0m'
  ].join('\n'));
});

gulp.task('default', ['help']);
