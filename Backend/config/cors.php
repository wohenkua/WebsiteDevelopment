<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 后端允许访问的接口
    'paths' => ['*'],

    // 允许的HTTP方法
    'allowed_methods' => ['*'],

    // 允许的源
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    // 允许的http headers
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,
    
    'supports_credentials' => true,

];
