'use strict';

var gulp = require('gulp');
var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
var less = require('gulp-less');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');

var dir = {
    admin_path: './src/AdminBundle/Resources/public/',
    admin_dist: './web/backend/',
    customer_path: './src/CustomerBundle/Resources/public/',
    customer_dist: './web/customer/',
    lp_path: './src/LandingPageBundle/Resources/public/',
    lp_dist: './web/landing/',
    app_dist: './web/',
    bower: './bower_components/'
};

//ADMIN fonts
gulp.task('admin-fonts', function () {
    gulp.src([
        dir.admin_path + 'fonts/**',
    ]).pipe(gulp.dest(dir.admin_dist + 'fonts'));
});


// ADMIN-STYLES
gulp.task('admin-styles', function () {
    gulp.src([
        //dir.bower + 'bootstrap/dist/css/bootstrap.min.css',
        dir.admin_path + '/css/**'
    ])
    .pipe(sourcemaps.init())
    .pipe(gulpif(/[.]less/, less()))
    .pipe(sourcemaps.write())
    .pipe(concat('admin.css'))
    .pipe(gulp.dest(dir.admin_dist + 'css'));
});


// ADMIN-SCRIPTS
gulp.task('admin-scripts', function () {
    gulp.src([
            dir.admin_path + '/js/jquery.min.js',
            dir.admin_path + '/js/bootstrap.min.js',
            dir.admin_path + '/js/vertical-responsive-menu.min.js',
            dir.admin_path + '/js/jquery.canvasjs.min.js'
        ])
        .pipe(concat('admin.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.admin_dist + 'js'));
});

gulp.task('admin-images', function () {
    gulp.src([
        dir.admin_path + 'images/**'
    ]).pipe(gulp.dest(dir.admin_dist + 'images'));
});

//CUSTOMER fonts
gulp.task('customer-fonts', function () {
    gulp.src([
        dir.customer_path + 'fonts/**',
    ]).pipe(gulp.dest(dir.customer_dist + 'fonts'));
});


// CUSTOMER-STYLES
gulp.task('customer-styles', function () {
    gulp.src([
        //dir.bower + 'bootstrap/dist/css/bootstrap.min.css',
        dir.customer_path + '/css/**'
    ])
        .pipe(sourcemaps.init())
        .pipe(gulpif(/[.]less/, less()))
        .pipe(sourcemaps.write())
        .pipe(concat('customer.css'))
        .pipe(gulp.dest(dir.customer_dist + 'css'));
});


// CUSTOMER-SCRIPTS
gulp.task('customer-scripts', function () {
    gulp.src([
        dir.customer_path + '/js/jquery.min.js',
        dir.customer_path + '/js/custom.min.js'
    ])
        .pipe(concat('customer.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.customer_dist + 'js'));
});

gulp.task('customer-images', function () {
    gulp.src([
        dir.customer_path + 'images/**'
    ]).pipe(gulp.dest(dir.customer_dist + 'images'));
});


///LANDING PAGE

//LANDING PAGE fonts
gulp.task('lp-fonts', function () {
    gulp.src([
        dir.lp_path + 'fonts/**',
    ]).pipe(gulp.dest(dir.lp_dist + 'fonts'));
});


// LANDING PAGE-STYLES
gulp.task('lp-styles', function () {
    gulp.src([
        //dir.bower + 'bootstrap/dist/css/bootstrap.min.css',
        dir.lp_path + '/css/**'
    ])
        .pipe(sourcemaps.init())
        .pipe(gulpif(/[.]less/, less()))
        .pipe(sourcemaps.write())
        .pipe(concat('landing.css'))
        .pipe(gulp.dest(dir.lp_dist + 'css'));
});


// LANDING PAGE-SCRIPTS
gulp.task('lp-scripts', function () {
    gulp.src([
        dir.lp_path + '/js/main.js'
    ])
        .pipe(concat('landing.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.lp_dist + 'js'));
});

gulp.task('lp-head-scripts', function () {
    gulp.src([
        dir.lp_path + '/js/jquery.min.js',
        dir.lp_path + '/js/modernizr.js'
    ])
        .pipe(concat('landing_head.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.lp_dist + 'js'));
});

gulp.task('lp-images', function () {
    gulp.src([
        dir.lp_path + 'images/**'
    ]).pipe(gulp.dest(dir.lp_dist + 'images'));
});



gulp.task('default', ['admin','customer','lp']);

gulp.task('admin', [
    'admin-fonts',
    'admin-styles',
    'admin-scripts',
    'admin-images',
]);

gulp.task('customer', [
    'customer-fonts',
    'customer-styles',
    'customer-scripts',
    'customer-images',
]);

gulp.task('lp', [
    'lp-fonts',
    'lp-styles',
    'lp-head-scripts',
    'lp-scripts',
    'lp-images',
]);
