xmlrpc-laravel-package
======================

Package to use xmlrpc (wordpress posts) with laravel


Installation:

Add to your composer.json:

    "martinmarianetti/xml-rpc": "dev-master"

Add this to your config/app.php file:

    'providers' => array(
        ....
        'Martinmarianetti\XmlRpc\XmlRpcServiceProvider',
    ),
    
Run this command:

    composer update
