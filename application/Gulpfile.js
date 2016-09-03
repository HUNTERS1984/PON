'use strict';

var gulp = require('gulp');
var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
var less = require('gulp-less');
var concat = require('gulp-concat');

var dir = {
    admin_path: './src/AdminBundle/Resources/public/',
    admin_dist: './web/backend/',
    app_dist: './web/',
    bower: './bower_components/'
};

// ADMIN-FONTS
gulp.task('admin-fonts-css', function () {
    gulp.src([
            dir.bower + 'font-awesome/css/font-awesome.min.css',
            dir.bower + 'ionicons/css/ionicons.min.css',
            dir.bower + 'material-design-iconic-font/css/material-design-iconic-font.min.css',
            dir.bower + 'simple-line-icons/css/simple-line-icons.css',
            dir.bower + 'themify-icons/css/themify-icons.css',
            dir.bower + 'weather-icons/css/weather-icons.min.css'
        ])
        .pipe(concat('fonts.less'))
        .pipe(gulp.dest(dir.admin_path + 'less/fonts'));
});

gulp.task('admin-fonts', function () {
    gulp.src([
        dir.bower + 'bootstrap/dist/fonts/**',
        dir.bower + 'font-awesome/fonts/**',
        dir.bower + 'ionicons/fonts/**',
        dir.bower + 'material-design-iconic-font/fonts/**',
        dir.bower + 'simple-line-icons/fonts/**',
        dir.bower + 'themify-icons/fonts/**',
        dir.bower + 'weather-icons/fonts/**'
    ]).pipe(gulp.dest(dir.admin_dist + 'fonts'));
});

// ADMIN-STYLES
gulp.task('admin-styles', function () {
    gulp.src([
            dir.bower + 'bootstrap/dist/css/bootstrap.min.css',
            dir.admin_path + '/less/**/*.less'
        ])
        .pipe(gulpif(/[.]less/, less()))
        .pipe(concat('admin.css'))
        .pipe(gulp.dest(dir.admin_dist + 'css'));
});

// ADMIN-SCRIPTS
gulp.task('admin-scripts', function () {
    gulp.src([
            dir.bower + 'jquery/dist/jquery.min.js',
            dir.bower + 'bootstrap/dist/js/bootstrap.min.js',
            dir.bower + 'wow/dist/wow.min.js',
            dir.admin_path + '/js/components/*.js',
            dir.admin_path + '/js/global/*.js',
            dir.bower + 'jquery-slimscroll/jquery.slimscroll.min.js',
            dir.bower + 'blockUI/jquery.blockUI.js',
            dir.bower + 'jquery.nicescroll/dist/jquery.nicescroll.min.js',
            dir.bower + 'jquery-slimscroll/jquery.slimscroll.min.js',
            dir.admin_path + '/js/*.js'
        ])
        .pipe(concat('admin.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.admin_dist + 'js'));
});

gulp.task('admin-scripts-head-assets', function () {
    gulp.src([
        dir.bower + 'modernizr/bin/modernizr.js'
    ])
        .pipe(concat('head.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.admin_dist + 'js'));
});

gulp.task('admin-images', function () {
    gulp.src([
        dir.admin_path + 'images/**'
    ]).pipe(gulp.dest(dir.admin_dist + 'images'));
});

// Datatables styles
gulp.task('datatables-styles', function () {
    gulp.src([
        dir.bower + 'datatables.net-bs/css/dataTables.bootstrap.min.css',
        dir.bower + 'datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css',
        dir.bower + 'datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
        dir.bower + 'datatables-colvis/css/dataTables.colVis.css'
    ])
        .pipe(concat('datatables.css'))
        .pipe(gulp.dest(dir.admin_dist + 'css'));
});

// Datatables styles
gulp.task('datatables-scripts', function () {
    gulp.src([
        dir.bower + 'datatables.net/js/jquery.dataTables.min.js',
        dir.bower + 'datatables.net-bs/js/dataTables.bootstrap.min.js',
        dir.bower + 'datatables.net-fixedheader/js/dataTables.fixedHeader.min.js',
        dir.bower + 'datatables.net-responsive/js/dataTables.responsive.min.js',
        dir.bower + 'datatables.net-responsive-bs/js/responsive.bootstrap.js',
        dir.bower + 'datatables-buttons/js/dataTables.buttons.js',
        dir.bower + 'datatables-buttons/js/buttons.bootstrap.js',
        dir.bower + 'datatables-buttons/js/buttons.colVis.js',
        dir.bower + 'datatables-colvis/js/dataTables.colVis.js',
        dir.admin_path + '/js/datatable/*.js'
    ])
        .pipe(concat('datatables.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.admin_dist + 'js'));
});

gulp.task('default', ['admin']);

gulp.task('admin', [
    'admin-fonts-css',
    'admin-fonts',
    'admin-styles',
    'admin-scripts',
    'admin-scripts-head-assets',
    'admin-images',
    'datatables-styles',
    'datatables-scripts'
]);
