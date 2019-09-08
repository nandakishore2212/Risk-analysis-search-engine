<?php

ob_start();
require 'vendor/autoload.php';
// display the form page
$loader = new Twig_Loader_Filesystem('views');
//$twig = new Twig_Environment($loader);
$twig = new Twig_Environment($loader, array(
    'debug' => true,
    // ...
));
$twig->addExtension(new Twig_Extension_Debug());

// display the values from the database
      mysql_connect("localhost", "admin", "admin123");
      mysql_select_db("modeldb");
      $filename = $_GET['filename'];
      
      $selectQuery = "SELECT * FROM `class_system` WHERE `xml_filename` = $filename ";
      $selectQuery = "SELECT * FROM `class_assembly` WHERE `xml_filename` = $filename ";
      $selectQuery = "SELECT * FROM `class_components` WHERE `xml_filename` = $filename ";
      $selectQuery = "SELECT * FROM `class_attributes` WHERE `xml_filename` = $filename ";
//      if(!($selectResultS = mysql_query($selectQueryS))||!($selectResultA = mysql_query($selectQueryA))||!($selectResultC = mysql_query($selectQueryC))||!($selectResultAt = mysql_query($selectQueryAt))){
//          echo 'Currently no files in the database.';
//      }
     // else{
          $selectResultS = mysql_query($selectQueryS);
          $selectResultA = mysql_query($selectQueryA);
          $selectResultC = mysql_query($selectQueryC);
          $selectResultAt = mysql_query($selectQueryAt);

       $rowS = mysql_fetch_array($selectResultS, MYSQL_ASSOC);
       $rowA = mysql_fetch_array($selectResultA, MYSQL_ASSOC);
       $rowC = mysql_fetch_array($selectResultC, MYSQL_ASSOC);
       $rowAt = mysql_fetch_array($selectResultAt, MYSQL_ASSOC);
       do{
           $itemsS[]=$rowS;
       }while ($rowS = mysql_fetch_array($selectResultS, MYSQL_ASSOC));
       do{
           $itemsA[]=$rowA;
       }while ($rowA = mysql_fetch_array($selectResultA, MYSQL_ASSOC));
       do{
           $itemsC[]=$rowC;
       }while ($rowC = mysql_fetch_array($selectResultC, MYSQL_ASSOC));
       do{
           $itemsAt[]=$rowAt;
       }while ($rowAt = mysql_fetch_array($selectResultAt, MYSQL_ASSOC));
    mysql_free_result($selectResultS);
    mysql_free_result($selectResultA);
    mysql_free_result($selectResultC);
    mysql_free_result($selectResultAt);
    echo $twig->render('viewClassElementsPage.twig', array('uploadsS' => $itemsS), array('uploadsA' => $itemsA), array('uploadsC' => $itemsC), array('uploadsAt' => $itemsAt) );
       
  //  }
    
 