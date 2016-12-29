module.exports = function (grunt, options) {
    return {
        user_less: {
            files: ['src/Ekyna/Bundle/UserBundle/Resources/private/less/**/*.less'],
            tasks: ['less:user', 'copy:user_less', 'clean:user_less'],
            options: {
                spawn: false
            }
        },
        user_js: {
            files: ['src/Ekyna/Bundle/UserBundle/Resources/private/js/**/*.js'],
            tasks: ['copy:user_js'],
            options: {
                spawn: false
            }
        },
        user_ts: {
            files: ['src/Ekyna/Bundle/UserBundle/Resources/private/ts/**/*.ts'],
            tasks: ['ts:user', 'copy:user_ts', 'clean:user_ts'],
            options: {
                spawn: false
            }
        }
    }
};
