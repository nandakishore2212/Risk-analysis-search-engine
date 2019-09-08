<?php
    require 'vendor/autoload.php';
    mysql_connect("localhost", "admin", "admin123");
    mysql_select_db("modeldb");
    
    $id = $_GET['id'];
    echo $id;
    $query = "SELECT * FROM `temp_models` WHERE `Id` = $id ";
    if(!($result = mysql_query($query))){
          echo 'Currently no files in the database.';
      } 
      else{
          $result = mysql_query($query);
          if($result){
              
              while( $row = mysql_fetch_assoc($result)) {
                $tempname = $row['tempname'];
                echo $tempname;
                $type=$row['filetype']; 
                $filename = $row['filename'];
                
              }
          }
             
        $filePath = './myModelFiles/'.$tempname;
        $path = 'downloads/'.$filename;
       // $size = filesize($path);
        echo $filePath;
        //echo $size;
        if(($id)&& file_exists($filePath)){
        header('Content-Description: File Transfer');
        //header('Content-Type: text/xml');
        header('Content-Type: application/octet-stream ; charset=utf8');
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename=' . basename($filePath));
        header('Content-Length: ' . filesize($filePath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
       
        ob_clean();
        flush();
        readfile($filePath);
        exit;
    }
        
     
        // Print datac
      
}