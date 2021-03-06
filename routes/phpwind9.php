<?php

// index.php and read.php
Route::any('/{file?}', function ($file = 'index.php') {
    Wekit::run($file, 'phpwind');
})->where('file', 'index.php|read.php');

// admin.php
Route::any('/admin.php', function () {
    Wekit::run('admin.php', 'pwadmin', [
        'router' => [
            'config' => [
                'module' => ['default-value' => 'default'],
                'routes' => [
                    'admin' => [
                        'class'   => PwAdminRoute::class,
                        'default' => true,
                    ],
                ],
            ],
        ],
    ]);
});

// windid.php
Route::any('/windid.php', function () {
    Wekit::run('windid.php', 'windidnotify', ['router' => []]);
});

// install.php
Route::any('/install.php', function () {
    Wekit::run('install.php', 'install');
});

// windid/index.php
Route::any('/windid/{filename?}', function () {
    Wekit::run('windid/index.php', 'windid', ['router' => []]);
})->where('filename', 'index.php');

// windid/admin.php
Route::any('/windid/admin.php', function () {
    Wekit::run('windid/admin.php', 'windidadmin', ['router' => []]);
});

Route::any('/crossdomain.xml', function () {
    return response()->file(base_path('phpwind9/crossdomain.xml', [
        'Content-Type' => 'application/xml',
    ]));
});

Route::any('/windid/crossdomain.xml', function () {
    return response()->file(base_path('phpwind9/crossdomain.xml', [
        'Content-Type' => 'application/xml',
    ]));
});

Route::any('/{type}/{filename}.{ext}', function (Illuminate\Filesystem\Filesystem $filesystem, $type, $filename, $ext) {
    $filename = base_path(sprintf('phpwind9/%s/%s.%s', $type, $filename, $ext));

    $alias = [
        'css' => 'text/css',
        'js'  => 'text/javascript',
        'xml' => 'application/xml',
    ];

    $headers = [];
    if (isset($alias[$ext])) {
        $headers['Content-Type'] = $alias[$ext];
    }

    return response()->file($filename, $headers);
})->where([
    'type'     => 'attachment|res|themes|windid|',
    'filename' => '.*',
]);
