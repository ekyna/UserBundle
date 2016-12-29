module.exports = function (grunt, options) {
    return {
        user_img: {
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/UserBundle/Resources/private/img',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UserBundle/Resources/public/img'
                }
            ]
        },
        user_less: { // For watch:user_less
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/UserBundle/Resources/public/tmp/css',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UserBundle/Resources/public/css'
                }
            ]
        },
        user_ts: { // For watch:user_ts
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/UserBundle/Resources/public/tmp/js',
                    src: ['**/*.js'],
                    dest: 'src/Ekyna/Bundle/UserBundle/Resources/public/js'
                }
            ]
        },
        user_js: { // For watch:user_js
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/UserBundle/Resources/private/js',
                    src: ['**/*.js'],
                    dest: 'src/Ekyna/Bundle/UserBundle/Resources/public/js'
                }
            ]
        }
    }
};
