module.exports = function (grunt, options) {
    return {
        user: {
            files: [
                {
                    src: 'src/Ekyna/Bundle/UserBundle/Resources/private/ts/**/*.ts',
                    dest: 'src/Ekyna/Bundle/UserBundle/Resources/public/tmp/js'
                }
            ],
            options: {
                fast: 'never',
                module: 'amd',
                rootDir: 'src/Ekyna/Bundle/UserBundle/Resources/private/ts',
                noImplicitAny: false,
                removeComments: true,
                preserveConstEnums: true,
                sourceMap: false
            }
        }
    }
};
