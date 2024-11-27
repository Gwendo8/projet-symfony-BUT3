// Assurez-vous que vous avez installé @symfony/stimulus-bridge dans votre projet
// npm install @symfony/stimulus-bridge

const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')

    // Activation du support Stimulus
    .enableStimulusBridge('./assets/controllers.json')  // Assure-toi que ce fichier existe à l'emplacement spécifié

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })
    .enableSingleRuntimeChunk();

;

module.exports = Encore.getWebpackConfig();