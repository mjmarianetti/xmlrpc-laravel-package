<?php

namespace Martinmarianetti\XmlRpc;

require_once('phpxmlrpc/xmlrpc.inc');
require_once('phpxmlrpc/xmlrpc_wrappers.inc');
require_once('phpxmlrpc/xmlrpcs.inc');

/**
 * Description of Xmlrpc
 *
 * @author mmarianetti
 */
class Xmlrpc {

    private $client = "";
    private $UserName = "";  // Nombre de usuario admin del sitio.
    private $PassWord = "";  // Pass del usuario.

// Constructor

    public function __construct() {
        //$log = new Logging(); // default en /tmp/logfile.txt
    }

    public function configure($xmlrpcurl, $username, $password) {
        $this->client = new \xmlrpc_client($xmlrpcurl);
        $this->client->return_type = 'phpvals';
        $this->UserName = $username;
        $this->PassWord = $password;
    }

    function featured_image($urlImg) {
        $fh = fopen($urlImg, 'r');
        $fs = filesize($urlImg);
        $theData = fread($fh, $fs);
        fclose($fh);
        $this->client->setDebug(2); // quiero que me muestre todo el proceso!
        $mensaje = new \xmlrpcmsg('wp.uploadFile');
        $mensaje->addParam(new \xmlrpcval(1, "int"));
        $mensaje->addParam(new \xmlrpcval($this->UserName));
        $mensaje->addParam(new \xmlrpcval($this->PassWord));
        $mensaje->addParam(php_xmlrpc_encode(array('name' => 'imagen' . mt_rand() . '.jpg', 'type' => 'image/jpg', 'bits' => new \xmlrpcval($theData, 'base64'), 'overwrite' => true)));
        $resp = $this->client->send($mensaje);
        if ($resp->faultCode()) {
            die('KO. Error uploading: ' . $resp->faultCode() . ' - ' . $resp->faultString());
        }
        $resultado = $resp->value();
        return $resultado['id'];
    }

// Comnienzo de funciones de posts.

    function create_post($title, $body, $featuredimg, $category, $keywords) {
        $idImg = $this->featured_image($featuredimg);
        echo "<br> ID de Imagen = " . $idImg . "<br>";
        $encoding = 'UTF-8';
        // limpio caracteres basura
        $body = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $body);
        //$title = htmlentities(utf8_encode($title), ENT_QUOTES, $encoding); 
        $keywords = htmlentities(utf8_encode($keywords), ENT_QUOTES, $encoding);
        $content['title'] = $title;
        $content['description'] = utf8_encode($body);
        $content['wp_post_thumbnail'] = $idImg;
        $content['terms_names'] = $category;
        $content['mt_keywords'] = $keywords;
        $mensaje = new \xmlrpcmsg('metaWeblog.newPost');
        $mensaje->addParam(new \xmlrpcval(''));
        $mensaje->addParam(new \xmlrpcval($this->UserName));
        $mensaje->addParam(new \xmlrpcval($this->PassWord));
        $mensaje->addParam(php_xmlrpc_encode($content));
        $mensaje->addParam(new \xmlrpcval(true));
        echo var_dump($mensaje);
        $resp = $this->client->send($mensaje);
        if ($resp->faultCode()) {
            die('KO. Error new post: ' . $resp->faultCode() . ' - ' . $resp->faultString());
        }
        return $resp->val;
    }

    function get_post($postId) {
        $mensaje = new \xmlrpcmsg('metaWeblog.getPost');
        $mensaje->addParam(new \xmlrpcval($postId, 'int'));
        $mensaje->addParam(new \xmlrpcval($this->UserName));
        $mensaje->addParam(new \xmlrpcval($this->PassWord));

        $resp = $this->client->send($mensaje);

        if ($resp->faultCode()) {
            die('KO. Error get post: ' . $resp->faultCode() . ' - ' . $resp->faultString());
        }
        return var_dump($resp->value);
    }

// Comnienzo de funciones de pages.

    function create_page($title, $body, $encoding = 'UTF-8') {
        $title = htmlentities($title, ENT_NOQUOTES, $encoding);

        $content = array(
            'title' => $title,
            'description' => $body
        );

        $mensaje = new \xmlrpcmsg('wp.newPage');
        $mensaje->addParam(new \xmlrpcval(0, 'int'));
        $mensaje->addParam(new \xmlrpcval($this->UserName));
        $mensaje->addParam(new \xmlrpcval($this->PassWord));
        $mensaje->addParam(php_xmlrpc_encode($content));
        $mensaje->addParam(new \xmlrpcval(true));

        $resp = $this->client->send($mensaje);

        if ($resp->faultCode()) {
            die('KO. Error create page: ' . $resp->faultCode() . ' - ' . $resp->faultString());
        }
        return $resp->value;
    }

    function display_authors() {
        $mensaje = new \xmlrpcmsg('wp.getAuthors');
        $mensaje->addParam(new \xmlrpcval(0, 'int'));
        $mensaje->addParam(new \xmlrpcval($this->UserName));
        $mensaje->addParam(new \xmlrpcval($this->PassWord));

        $resp = $this->client->send($mensaje);

        if ($resp->faultCode()) {
            die('KO. Error display authors: ' . $resp->faultCode() . ' - ' . $resp->faultString());
        }
        return $resp->value;
    }

}
