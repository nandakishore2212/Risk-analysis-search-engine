<?php
require 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

echo $twig->render('viewTreePage.twig');
if(!empty ($_POST)){
 $filename = $_POST['opfilename'];
$xml = new DOMDocument();
$xml->load($filename);

echo $xml->saveXML();
}