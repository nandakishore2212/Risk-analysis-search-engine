<?php

require 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

echo $twig->render('createPage.twig');
// $connect = mysqli_connect("localhost", "root", "", "xmi_file_data");  
// $numberD = count($_POST["dname"]); 
// $numberA = count($_POST["aname"]);
// $numberC = count($_POST["cname"]); 
// if($numberD > 0 || $numberA > 0 || $numberC > 0)  
// {  
////      for($i=0; $i<$numberD; $i++)  
////      {  
////           if(trim($_POST["nameD"][$i] != ''))  
////           {  
////                $sql = "INSERT INTO tbl_name(name) VALUES('".mysqli_real_escape_string($connect, $_POST["nameD"][$i])."')";  
////                mysqli_query($connect, $sql);  
////           }  
////      } 
//       for($i=0; $i<$numberD; $i++)
//       {
//          $device = $_POST["dname"]; 
//       }
//      echo "Data Inserted";  
// }  
// else  
// {  
//      echo "Please Enter Name";  
// } 


//if(isset($_POST['data'])) {
//$sxe = simplexml_load_string($_POST['data']);
// 
//if ($sxe === false) {
//  echo 'Error while parsing the document';
//  exit;
//}
// 
//$dom_sxe = dom_import_simplexml($sxe);
//if (!$dom_sxe) {
//  echo 'Error while converting XML';
//  exit;
//}
// 
//$dom = new DOMDocument('1.0');
//$dom_sxe = $dom->importNode($dom_sxe, true);
//$dom_sxe = $dom->appendChild($dom_sxe);
// 
//echo $dom->save('test5.xml');
////header('xmlToModels.php');
//}
?>