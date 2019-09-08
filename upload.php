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

echo $twig->render('uploadPage.twig');


// retreive info from the form
      if(!empty ($_POST)){
      $_FILES['model_file']['tmp_name'];
      $filename = ($_FILES['model_file']['name']);
      $uniqid = uniqid();
      move_uploaded_file($_FILES['model_file']['tmp_name'], './legacyModelFiles/' . $uniqid . '-' . $_FILES['model_file']['name']);
      $tempname = $uniqid . '-' . $_FILES['model_file']['name'];
     // $filename = $_POST['filename'];
      $filetype = $_POST['filetype'];
      $description = $_POST['description'];
      $user = $_POST['user'];
      
      // inserting into the database
      mysql_connect("localhost", "admin", "admin123");
      mysql_select_db("modeldb");
      $query = "insert into legacy_models(filename,filetype,tempname,description,user)values('".$filename."','".$filetype."', '".$tempname."', '".$description."','".$user."')";
      mysql_query($query);
      
      header("Refresh:0");
      }