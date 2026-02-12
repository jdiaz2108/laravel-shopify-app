<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Shopify Store Shop Name
    |--------------------------------------------------------------------------
    | Your Shopify store subdomain, e.g. for "my-store.myshopify.com" set "my-store".
    | Set in your .env as SHOPIFY_SHOP.
    */
    'shop' => env('SHOPIFY_SHOP'),

    /*
    |--------------------------------------------------------------------------
    | Shopify OAuth2 Client Credentials
    |--------------------------------------------------------------------------
    | Client ID and Secret from your Shopify Partner Dashboard / Custom App.
    | Used to obtain a short-lived access token via client_credentials grant.
    */
    'client_id'     => env('SHOPIFY_CLIENT_ID'),
    'client_secret' => env('SHOPIFY_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    | HMAC secret used to verify incoming Shopify webhook payloads.
    | Set in your .env as SHOPIFY_WEBHOOK_SECRET.
    */
    'webhook_secret' => env('SHOPIFY_WEBHOOK_SECRET'),

];
