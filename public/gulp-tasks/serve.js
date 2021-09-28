'use strict'
var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var gutil = require('gulp-util');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
// const del = require('del');




// Static Server + watching scss/html files
gulp.task('serve', ['sass'], function () {

    browserSync.init({
        port: 3000,
        server: "./",
        ghostMode: false,
        notify: false
    });

    gulp.watch('./scss/**/*.scss', ['sass']);
    gulp.watch(['./scss/**/*.scss', './images/**/', './js/**/*.js', './pages/**/*.html', './*.html']).on('change', browserSync.reload);

});



gulp.task('sass', function () {
    return gulp.src('./scss/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(plumber({
            errorHandler: function (err) {
                notify.onError({
                    title: "Gulp error in " + err.plugin,
                    message: err.toString()
                })(err);
                gutil.beep();
            }
        }))
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./css'))
        .pipe(browserSync.stream());
});



gulp.task('sass:watch', function () {
    gulp.watch('./scss/**/*.scss');
});



// Static Server without watching scss files
gulp.task('serve:lite', function () {

    browserSync.init({
        server: "./",
        ghostMode: false,
        notify: false
    });

    gulp.watch('**/*.css').on('change', browserSync.reload);
    gulp.watch('**/*.html').on('change', browserSync.reload);
    gulp.watch('js/**/*.js').on('change', browserSync.reload);

});