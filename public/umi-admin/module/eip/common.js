require.config({
    baseUrl: '../../',

    paths: {
        core: './module/eip/core/main',
        adminPackages: 'partials',
        jquery: 'vendor/jquery/dist/jquery'
    },

    packages: [
        {name: 'topbar', location: './module/eip/component/topbar'}
    ]
});

require(['core']);