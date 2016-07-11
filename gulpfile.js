var gulp = require('gulp'),
    notify = require('gulp-notify'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat');

gulp.task('js', function() {
    gulp.src('assets/js/*.js')
        .pipe(concat('jquery.plugin.min.js'))
        .pipe(uglify({ preserveComments: 'some' }))
        .pipe(gulp.dest('assets/build'))
        .pipe(notify({ message: 'Scripts are updated' }));
});

gulp.task('watch', function(){
    gulp.watch('assets/js/*.js', ['js']);
});

gulp.task('default', ['js', 'watch']);