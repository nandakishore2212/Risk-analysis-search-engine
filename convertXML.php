<?php
require 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

if(isset($_POST['data']))
    echo $_POST['filename'];
$filename = $_POST['filename'];
$sxe = simplexml_load_string($_POST['data']);
if ($sxe === false) {
  echo 'Error while parsing the document';
  exit;
}
 
$dom_sxe = dom_import_simplexml($sxe);
if (!$dom_sxe) {
  echo 'Error while converting XML';
  exit;
}
 
$dom = new DOMDocument('1.0');
$dom_sxe = $dom->importNode($dom_sxe, true);
$dom_sxe = $dom->appendChild($dom_sxe);
 
echo $dom->save('./xmlFiles/'.$filename);
 
?>