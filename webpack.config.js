var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // read main.js     -> output as web/build/chamilo.js
    .addEntry('chamilo', './public/assets/js/main.js')
    // read main.scss -> output as web/build/chamilo_style.css
    .addStyleEntry('chamilo_style', './public/assets/css/main.scss')

    // enable features!
    .enableSassLoader()
    .autoProvidejQuery()
    .enableReactPreset()
    .enableSourceMaps(!Encore.isProduction())
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery'
    })
    //.enableVersioning() // hashed filenames (e.g. main.abc123.js)
;

module.exports = Encore.getWebpackConfig();

