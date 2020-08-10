/**
 * A simple Gulp 4 Starter Kit for modern web development.
 *
 * ________________________________________________________________________________
 *
 * gulpfile.js
 *
 * The gulp configuration file.
 *
 */

const gulp                      = require('gulp'),
      del                       = require('del'),
      sourcemaps                = require('gulp-sourcemaps'),
      plumber                   = require('gulp-plumber'),
      sass                      = require('gulp-sass'),
      autoprefixer              = require('gulp-autoprefixer'),
      minifyCss                 = require('gulp-clean-css'),
      babel                     = require('gulp-babel'),
      webpack                   = require('webpack-stream'),
      uglify                    = require('gulp-uglify'),
      concat                    = require('gulp-concat'),
      imagemin                  = require('gulp-imagemin'),
      dependents                = require('gulp-dependents'),
      rename                    = require('gulp-rename'),

      src_folder                = './src/',
      dist_folder               = './assets/',
      node_modules_folder       = './node_modules/',
      vendors_folder            = dist_folder + 'vendors/',

      node_dependencies         = Object.keys(require('./package.json').dependencies || {});

gulp.task('clear', () => del([ dist_folder ]));

gulp.task('sass', () => {
  return gulp.src([
    src_folder + 'scss/**/*.scss'
  ], { since: gulp.lastRun('sass') })
    .pipe(sourcemaps.init())
      .pipe(plumber())
      .pipe(dependents())
      .pipe(sass())
      .pipe(autoprefixer())
      .pipe(gulp.dest('.'))
      .pipe(minifyCss())
      .pipe(rename({ suffix: '.min' }))
      .pipe(sourcemaps.write('.'))
  .pipe(gulp.dest('.'))
});

gulp.task('js', () => {
  return gulp.src([ src_folder + 'js/**/*.js' ], { since: gulp.lastRun('js') })
    .pipe(plumber())
    .pipe(webpack({
      mode: 'production'
    }))
    .pipe(sourcemaps.init())
      .pipe(babel({
        presets: [ '@babel/env' ]
      }))
      .pipe(concat('app.js'))
      .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(dist_folder + 'js'))
});

gulp.task('images', () => {
  return gulp.src([ src_folder + 'images/**/*.+(png|jpg|jpeg|gif|svg|ico)' ], { since: gulp.lastRun('images') })
    .pipe(plumber())
    .pipe(imagemin())
    .pipe(gulp.dest(dist_folder + 'images'))
});

gulp.task('fonts', () => {
  return gulp.src([ src_folder + 'fonts/**/*.+(woff|wofff2)' ], { since: gulp.lastRun('fonts') })
      .pipe(plumber())
      .pipe(imagemin())
      .pipe(gulp.dest(dist_folder + 'fonts'))
});

gulp.task('vendor', () => {
  if (node_dependencies.length === 0) {
    return new Promise((resolve) => {
      console.log("No dependencies specified");
      resolve();
    });
  }

  return gulp.src(node_dependencies.map(dependency => node_modules_folder + dependency + '/**/*.*'), {
    base: node_modules_folder,
    since: gulp.lastRun('vendor')
  })
    .pipe(gulp.dest(vendors_folder))
});

gulp.task('build', gulp.series('clear', 'sass', 'js', 'images', 'fonts', 'vendor'));

gulp.task('dev', gulp.series('sass', 'js'));

gulp.task('watch', () => {
  const watchImages = [
    src_folder + 'images/**/*.+(png|jpg|jpeg|gif|svg|ico)'
  ];

  const watchFonts = [
    src_folder + 'fonts/**/*.+(woff|woff2)'
  ];

  const watchVendor = [];

  node_dependencies.forEach(dependency => {
    watchVendor.push(node_modules_folder + dependency + '/**/*.*');
  });

  const watch = [
    src_folder + 'scss/**/*.scss',
    src_folder + 'js/**/*.js'
  ];

  gulp.watch(watch, gulp.series('dev'));
  gulp.watch(watchImages, gulp.series('images'));
  gulp.watch(watchFonts, gulp.series('fonts'));
  gulp.watch(watchVendor, gulp.series('vendor'));
});

gulp.task('default', gulp.series('build', gulp.parallel('watch')));
