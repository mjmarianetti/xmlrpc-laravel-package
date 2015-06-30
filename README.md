xmlrpc-laravel-package
======================

Package to use xmlrpc (wordpress posts) with laravel 4




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
    
------------------------------

Example usage:

    $xmlrpc = new Xmlrpc;
    $xmlrpc::configure($url . "xmlrpc.php", $username, $password);
    $xmlrpc::create_post(utf8_encode($ttitle), utf8_encode($body), $imagePath, $category, utf8_encode($t));
             
Note: Not all functions documented.

You can look at the code for more functions or add yours!!
