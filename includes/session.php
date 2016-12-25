


<?php

// Demarrer la session dans une page 

$protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
    header('Location: '.$protocol.substr($_SERVER['HTTP_HOST'], 4).'/'.$_SERVER['REQUEST_URI']);
    exit();
}


if(session_status()==PHP_SESSION_NONE)
session_start(["cookie_domain" => ".sitedusavoir.com"]);

?>
