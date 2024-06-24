module.exports = {
    css: {
        loaderOptions: {
            sass: {
                additionalData: '@import "@/scss/main.scss";',
            },
        },
    },
    chainWebpack: (config) => {
        config.plugin('html').tap((args) => {
            args[0].filename = 'index.html';
            return args;
        });
    },
};