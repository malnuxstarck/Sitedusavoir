// Packages node
const gulp = require('gulp')
const plumber = require('gulp-plumber')
const notify = require('gulp-notify')
const sass = require('gulp-sass')
const sourcemaps = require('gulp-sourcemaps')
const postcss = require('gulp-postcss')
const autoprefixer = require('autoprefixer')
const mq = require('css-mqpacker')

// Paths
const pathCss = 'css'

const confPostCss = [
  autoprefixer({ browsers: ['last 2 versions', '> 1%', 'Firefox ESR', 'Safari >=7', 'ie >= 9'] }),
  mq()
]

// Compilation SCSS => CSS
gulp.task('scss', _ => {
  return gulp.src(`${pathCss}/*.scss`)
    .pipe(plumber({
      errorHandler (err) {
        notify.onError({
          title: 'Gulp Sass',
          message: 'Error: <%= error.message %>',
          sound: 'Beep'
        })(err)
        this.emit('end')
      }
    }))
    .pipe(sourcemaps.init())
    .pipe(sass({ outputStyle: 'expanded' }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(pathCss))
})

// Watchers
gulp.task('watch', _ => {
  gulp.watch(`${pathCss}/**/*.scss`, ['scss'])
})

// Production
gulp.task('prod', _ => {
  gulp.src(`${pathCss}/*.scss`)
    .pipe(sass({ outputStyle: 'compressed' }))
    .pipe(postcss(confPostCss))
    .pipe(gulp.dest(pathCss))
})

gulp.task('dev', ['scss'])
gulp.task('build', ['prod'])

gulp.task('default', ['dev', 'watch'])
