module.exports = function (grunt, options) {
    return {
        user_ts: {
            files: [{
                expand: true,
                cwd: 'src/Ekyna/Bundle/UserBundle/Resources/public/tmp/js',
                src: '**/*.js',
                dest: 'src/Ekyna/Bundle/UserBundle/Resources/public/js'
            }]
        },
        user_js: {
            files: [{
                expand: true,
                cwd: 'src/Ekyna/Bundle/UserBundle/Resources/private/js',
                src: ['*.js', '**/*.js'],
                dest: 'src/Ekyna/Bundle/UserBundle/Resources/public/js'
            }]
        }
    }
};
