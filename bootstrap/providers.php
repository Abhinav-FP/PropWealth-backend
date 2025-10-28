<?php

return [
    App\Providers\AppServiceProvider::class,
    // Removed external providers that are not installed in production.
    // Views are loaded via namespaces in AppServiceProvider.
];
