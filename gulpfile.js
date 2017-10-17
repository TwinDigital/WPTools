/*
 * Copyright (c) 2016 Twin Digital - All Rights reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 */

var fs = require('fs');
var gulp = require('gulp');
var minifycss = require('gulp-clean-css');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin');
var licenser = require('gulp-licenser');
var concatCss = require('gulp-concat-css');
var concat = require('gulp-concat');
var header = require('gulp-header');
var es = require('event-stream');
var bump = require('gulp-bump');
var semver = require('semver');

var getPackageJson = function() {
  return JSON.parse(fs.readFileSync('./package.json', 'utf8'));
};
var config = {};
config.license_template =
  '/*!\n\
 * Copyright (c) 2017 Twin Digital - All Rights reserved\n\
 * Unauthorized copying of this file, via any medium is strictly prohibited\n\
 * Proprietary and confidential\n\
 */';
config.files = {};
config.files.js = [
  './assets/js/*.js',
  '!./assets/js/*.min.js',
  './assets/js/3rdparty/*.js',
  '!./assets/js/3rdparty/*.min.js',
  './templates/js/*.js',
  '!./templates/js/*.min.js'
];
config.files.css = [
  './assets/css/admin/admin.css'
//  '!./assets/css/*.min.css',
//  '!./assets/css/*.scss',
];
config.files.scss = [
  './assets/css/**/*.scss'
];

config.files.cssbundle = {};

config.files.cssbundle.admin = [
  './assets/css/admin.css'
];
config.files.cssbundle.public = [
  './assets/css/public.css'
];

config.files.jsbundle = {};
config.files.jsbundle.public = [
  './assets/js/3rdparty/modernizr.min.js',
  './assets/js/3rdparty/jquery.mobile.custom.js',
  './assets/js/3rdparty/jquery.transit.js',
  './assets/js/3rdparty/jquery.waitforimages.js',
  './assets/js/3rdparty/jquery-ui.js',
  './assets/js/pagetransitions.js',
  './assets/js/3rdparty/unslider.js',
  './assets/js/3rdparty/nprogress.js',
  './assets/js/3rdparty/velocity.js',
  './assets/js/browser.js'
];
config.files.jsbundle.editor = [
  './assets/js/3rdparty/jquery.transit.js',
  './assets/js/3rdparty/jquery.dlmenu.js',
  './assets/js/3rdparty/unslider.js',
  './assets/js/3rdparty/jquery-ui.js',
  './assets/js/editor.js'
];

config.files.versioning = [
  './lib/TwinDigital/WPPlugins/FoodlogGravityFormsInvoices/**/*.php',
];

var banner_js = [
  '/**',
  ' * <%= pkg.name %> - <%= pkg.description %>',
  ' * @version v<%= pkg.version %>',
  ' * @link <%= pkg.homepage %>',
  ' * @license <%= pkg.license %>',
  ' */',
  ''
].join('\n');

gulp.task('minify-css', function() {
  return gulp.src(config.files.css, {base: "./"})
             .pipe(minifycss({keepSpecialComments: 0, processImport: false}))
             .pipe(rename(function(path) {
               path.extname = ".min.css";
             }))
             .pipe(gulp.dest('.'));
});

gulp.task('copy-from-node', function() {
  return gulp.src(['./node_modules/preload-js/index.js'])
             .pipe(uglify())
             .pipe(rename('preload-js.min.js'))
             .pipe(gulp.dest('./assets/js/3rdparty/'));
});

gulp.task('minify-js', function() {
  var pkg = getPackageJson();
  return gulp.src(config.files.js, {base: "./"})
             .pipe(uglify())
             .pipe(rename(function(path) {
               path.extname = '.min.js';
             }))
             .pipe(header(banner_js, {pkg: pkg}))
             .pipe(gulp.dest('.'));
});

gulp.task('bundle-css', function() {
  return es.concat(
    gulp.src(config.files.cssbundle.public, {base: "./assets/css/"})
        .pipe(concatCss('./public.css'))
        .pipe(licenser(config.license_template))
        .pipe(gulp.dest('./dist/css/'))
        .pipe(minifycss({keepSpecialComments: 0, processImport: false}))
        .pipe(licenser(config.license_template))
        .pipe(rename(function(path) {
          path.extname = '.min.css';
        }))
        .pipe(gulp.dest('./dist/css/')),
    gulp.src(config.files.cssbundle.admin, {base: "./assets/css/"})
        .pipe(concatCss('admin.css'))
        .pipe(licenser(config.license_template))
        .pipe(gulp.dest('./dist/css/'))
        .pipe(minifycss({keepSpecialComments: 0, processImport: false}))
        .pipe(licenser(config.license_template))
        .pipe(rename(function(path) {
          path.extname = '.min.css';
        }))
        .pipe(gulp.dest('./dist/css/')),
    gulp.src(config.files.cssbundle.editor, {base: "./assets/css/"})
        .pipe(concatCss('editor.css'))
        .pipe(licenser(config.license_template))
        .pipe(gulp.dest('./dist/css/'))
        .pipe(minifycss({keepSpecialComments: 0, processImport: false}))
        .pipe(licenser(config.license_template))
        .pipe(rename(function(path) {
          path.extname = '.min.css';
        }))
        .pipe(gulp.dest('./dist/css/')),
    gulp.src(['./assets/css/3rdparty/themify/**/*'])
        .pipe(gulp.dest('dist/css/3rdparty/themify/'))
  )
    ;
});

gulp.task('bundle-js', function() {
  return es.concat(
    gulp.src(config.files.jsbundle.public, {base: "./assets/"})
        .pipe(concat('public.js'))
        .pipe(gulp.dest('./dist/js/'))
        .pipe(uglify())
        .pipe(licenser(config.license_template))
        .pipe(rename(function(path) {
          path.extname = ".min.js";
        }))
        .pipe(gulp.dest('./dist/js/')),
    gulp.src(config.files.jsbundle.editor, {base: "./assets/"})
        .pipe(concat('editor.js'))
        .pipe(gulp.dest('./dist/js/'))
        .pipe(uglify())
        .pipe(licenser(config.license_template))
        .pipe(rename(function(path) {
          path.extname = ".min.js";
        }))
        .pipe(gulp.dest('./dist/js/'))
  );

});

gulp.task('image-optimisation', function() {
  return es.concat(
    gulp.src('./assets/images/**', {base: './assets/'})
        .pipe(imagemin([
          imagemin.jpegtran({progressive: true}),
          imagemin.gifsicle({interlace: true}),
          imagemin.optipng({optimizationLevel: 5})
        ]))
        .pipe(gulp.dest('./dist/'))
  );
});

gulp.task('version', function() {
  var pkg = getPackageJson();
  return es.concat(
    gulp.src(config.files.versioning, {base: './'})
        .pipe(bump({
          version: pkg.version
        }))
        .pipe(gulp.dest('.')),
    gulp.src('diziner-core.php', {base: './'})
        .pipe(bump({
          version: pkg.version
        }))
        .pipe(gulp.dest('.'))
  );
});

gulp.task('default', [
  'minify-css',
  'minify-js'
]);
