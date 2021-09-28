'use strict'
var gulp = require('gulp');
var injectPartials = require('gulp-inject-partials');
var inject = require('gulp-inject');
var rename = require('gulp-rename');
var prettify = require('gulp-prettify');
var replace = require('gulp-replace');
var runSequence = require('run-sequence');



/*sequence for injecting partials and replacing paths*/
gulp.task('inject', function () {
    runSequence('injectPartial', 'injectAssets', 'html-beautify', 'replacePath');
});



/* inject partials like sidebar and navbar */
gulp.task('injectPartial', function () {
    return gulp.src("./*.html", {
            base: "./"
        })
        .pipe(injectPartials())
        .pipe(gulp.dest("."));
});



/* inject Js and CCS assets into HTML */
gulp.task('injectAssets', function () {
    return gulp.src('./**/*.html')
        .pipe(inject(gulp.src([
            './vendors/iconfonts/mdi/css/materialdesignicons.min.css',
            './vendors/iconfonts/puse-icons-feather/feather.css',
            './vendors/css/vendor.bundle.base.css',
            './vendors/css/vendor.bundle.addons.css',
            './vendors/js/vendor.bundle.base.js',
            './vendors/js/vendor.bundle.addons.js'
        ], {
            read: false
        }), {
            name: 'plugins',
            relative: true
        }))
        .pipe(inject(gulp.src([
            '!css/horizontal-layouts.css', // <== !
            '!css/horizontal-layouts-2.css', // <== !
            '!css/sidebar-layouts.css', // <== !
            './css/*.css',
            './shared/off-canvas.js',
            './js/shared/hoverable-collapse.js',
            './js/shared/misc.js',
            './js/shared/settings.js',
            './js/shared/todolist.js'
        ], {
            read: false
        }), {
            relative: true
        }))
        .pipe(gulp.dest('.'));
});



/*replace image path and linking after injection*/
gulp.task('replacePath', function () {
    gulp.src('pages/*.html', {
            base: "./"
        })
        .pipe(replace('src="images/', 'src="../images/'))
        .pipe(replace('href="pages/', 'href="../pages/'))
        .pipe(replace('href="index.html"', 'href="../index.html"'))
        .pipe(gulp.dest('.'));
    gulp.src('pages/**/*.html', {
            base: "./"
        })
        .pipe(replace('src="images/', 'src="../../images/'))
        .pipe(replace('href="pages/', 'href="../../pages/'))
        .pipe(replace('href="index.html"', 'href="../../index.html"'))
        .pipe(gulp.dest('.'));
});



gulp.task('html-beautify', function () {
    return gulp.src(['**/*.html', '!node_modules/**/*.html'])
        .pipe(prettify({
            unformatted: ['pre', 'code', 'textarea']
        }))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});