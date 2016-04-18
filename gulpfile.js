var gulp = require('gulp');
var gutil = require('gulp-util');
var notify = require('gulp-notify');
var exec = require('child_process').exec;
var sys = require('sys');


var    phpunit = require('gulp-phpunit'),
    _       = require('lodash');

gulp.task('phpunit', function() {
    gulp.src('phpunit.xml')
        .pipe(phpunit('', {notify: true}))
        .on('error', notify.onError(testNotification('fail', 'phpunit')))
        .pipe(notify(testNotification('pass', 'phpunit')));
});

function testNotification(status, pluginName, override) {
    var options = {
        title:   ( status == 'pass' ) ? 'Tests Passed' : 'Tests Failed',
        message: ( status == 'pass' ) ? '\n\nAll tests have passed!\n\n' : '\n\nOne or more tests failed...\n\n',
        icon:    __dirname + '/node_modules/gulp-' + pluginName +'/assets/test-' + status + '.png'
    };
    options = _.merge(options, override);
    return options;
}

// Keep an eye on Sass, Coffee, and PHP files for changes...
gulp.task('watch', function () {
    gulp.watch('app/**/*.php', ['phpunit']);
    gulp.watch('tests/**/*.php', ['phpunit']);
    gulp.watch('bootstrap/**/*.php', ['phpunit']);
    gulp.watch('packages/itlabs/**/*.php', ['phpunit']);
});

// What tasks does running gulp trigger?
gulp.task('default', ['phpunit', 'watch']);