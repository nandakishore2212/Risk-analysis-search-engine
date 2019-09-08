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
      
      $selectQuery = "SELECT `id`,`xml_filename`,`modelio_filename` FROM `xml_files`";
      if(!($selectResult = mysql_query($selectQuery))){
          echo 'Currently no files in the database.';
      }
      else{
          $selectResult = mysql_query($selectQuery);

       $row = mysql_fetch_array($selectResult, MYSQL_ASSOC);
       do{
           $items[]=$row;
       }while ($row = mysql_fetch_array($selectResult, MYSQL_ASSOC));
       
    mysql_free_result($selectResult);
    echo $twig->render('viewClassElementsPage.twig', array('uploads' => $items) );
       
    }
    
 