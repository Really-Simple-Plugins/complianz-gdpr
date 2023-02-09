const gulp = require('gulp');
const concat = require('gulp-concat');
const cssbeautify = require('gulp-cssbeautify');
const cssuglify = require('gulp-uglifycss');
const jsuglify = require('gulp-uglify');
const spawn = require('child_process').spawn;
var gulpless = require('gulp-less');
var gulpautoprefixer = require('gulp-autoprefixer');
  //Creating a Style task that convert LESS to CSS

function lessTask(cb) {
      var srcfile = './assets/css/admin.less';
      var temp = './assets/css';
        gulp
                 .src(srcfile)
                 .pipe(gulpless())
                .pipe(gulpautoprefixer())
                .pipe(gulp.dest(temp));

	   srcfile = './assets/css/cookieblocker.less';
	   temp = './assets/css';
		gulp
				 .src(srcfile)
				 .pipe(gulpless())
				.pipe(gulpautoprefixer())
				.pipe(gulp.dest(temp));

	   srcfile = './assets/css/document.less';
	   temp = './assets/css';
		gulp
				 .src(srcfile)
				 .pipe(gulpless())
				.pipe(gulpautoprefixer())
				.pipe(gulp.dest(temp));
	   srcfile = './assets/css/document-grid.less';
	   temp = './assets/css';
		gulp
				 .src(srcfile)
				 .pipe(gulpless())
				.pipe(gulpautoprefixer())
				.pipe(gulp.dest(temp));

	   srcfile = './assets/css/wizard.less';
	   temp = './assets/css';
		gulp
				 .src(srcfile)
				 .pipe(gulpless())
				.pipe(gulpautoprefixer())
				.pipe(gulp.dest(temp));
}
exports.less = lessTask

function jsTask(cb) {
	gulp.src('assets/js/admin.js')
	.pipe(concat('admin.js'))
	.pipe(gulp.dest('./assets/js'))
	.pipe(concat('admin.min.js'))
	.pipe(jsuglify())
	.pipe(gulp.dest('./assets/js'));
	gulp.src('assets/js/dashboard.js')
	.pipe(concat('dashboard.js'))
	.pipe(gulp.dest('./assets/js'))
	.pipe(concat('dashboard.min.js'))
	.pipe(jsuglify())
	.pipe(gulp.dest('./assets/js'));
	gulp.src('cookiebanner/js/complianz.js')
	 .pipe(concat('complianz.js'))
	 .pipe(gulp.dest('./cookiebanner/js'))
	 .pipe(concat('complianz.min.js'))
	 .pipe(jsuglify())
	 .pipe(gulp.dest('./cookiebanner/js'));
	cb();
}
exports.js = jsTask

function defaultTask(cb) {
  gulp.watch('./assets/css/*.less', { ignoreInitial: false }, lessTask);
  gulp.watch('./assets/js/*.js', { ignoreInitial: false }, jsTask);
  gulp.watch('./cookiebanner/js/*.js', { ignoreInitial: false }, jsTask);
//   spawn('npm', ['start'], { cwd: 'settings', stdio: 'inherit' })
  cb();
}
exports.default = defaultTask

// function buildTask(cb) {
//   gulp.task(scssTask);
//   spawn('npm', ['build'], { cwd: 'settings', stdio: 'inherit' })
//   run('npm run build').exec()
//   cb();
// }
// exports.build = buildTask
