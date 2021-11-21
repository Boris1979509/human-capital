module.exports = {
    devServer: {
        proxy: process.env.VUE_APP_DEFAULT_DEVELOP_HOST,
        disableHostCheck: true,
    },
}
