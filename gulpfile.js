var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var gulpIf = require('gulp-if');
var cleanCSS = require('gulp-clean-css');
var imagemin = require('gulp-imagemin');
var cache = require('gulp-cache');

/*
* Creating optimisation tasks to concat and minify js/css/images for use with gulp watch or gulp build
*/

// Setup imagemin
gulp.task('images', function(){
    return gulp.src('./../../uploads/**/*.+(png|jpg|gif|svg)')
        .pipe(cache(imagemin([
            imagemin.gifsicle({interlaced: true}),
            imagemin.jpegtran({progressive: true, quality: 70}),
            imagemin.optipng({optimizationLevel: 7})
        ])))
        .pipe(gulp.dest('./../../uploads/'));
});

// Concat and minify for JS
gulp.task('scripts', function(){
    return gulp.src(['assets/js/theme/*.js', '!assets/js/theme/ajax-loader.js'])
        .pipe(concat('main.min.js'))
        .pipe(gulpIf('*.js', uglify()))
        .pipe(gulp.dest('assets/js'));
});

// Minify CSS
gulp.task('styles', () => {
   return gulp.src('assets/css/*.css')
    .pipe(cleanCSS({compatibility: 'ie10'}))
    .pipe(gulp.dest('assets/css'));
});

// Compile sass to css
gulp.task('sass', function(){
    return gulp.src('sass/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('assets/css'))
        .pipe(browserSync.reload({
            stream: true
        }));
});


// Creating development task to compile sass, watch for changes and run MAMP browser sync
// use: gulp watch
gulp.task('watch', function() {
    // Start browser sync for MAMP
    browserSync.init({
        proxy: 'http://localhost/boilerplate', // Change to correct path for project
        reloadOnRestart: true
    });
    // Watch for changes and reload browser
    gulp.watch('sass/**/*.scss', gulp.series('sass', 'styles'));
    gulp.watch('assets/js/theme/*.js', gulp.series('scripts'));
    gulp.watch('assets/js/main.min.js').on("change", browserSync.reload);
    gulp.watch('**/*.php').on("change", browserSync.reload);
});


// Build task for optimisation
// use: gulp build
gulp.task('build', gulp.series('sass', 'scripts', 'styles', 'images'), function(){});