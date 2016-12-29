module.exports = {
    'build:user_css': [
        'less:user',
        'cssmin:user_less',
        'clean:user_less'
    ],
    'build:user_js': [
        'ts:user',
        'uglify:user_ts',
        'uglify:user_js',
        'clean:user_ts'
    ],
    'build:user': [
        'clean:user_pre',
        //'copy:user_img',
        //'build:user_css',
        'build:user_js',
        'clean:user_post'
    ]
};
