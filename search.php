<?php

require 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

//echo $twig->render('searchPage.twig');


//      if(!empty ($_POST)){
//     
//      $searchString = $_POST['searchString'];
//      $searchString = preg_replace("#[^0-9a-z]#i", "", $searchString);
//      
//      mysql_connect("localhost", "admin", "admin123");
//      mysql_select_db("modeldb");
//      $output = '';
//      $query = "SELECT * FROM collectiveterm14_7 WHERE name LIKE '%$searchString%' ";
//      $result = mysql_query($query);
//      $count = mysql_num_rows($result);
//              if($count == 0){
//                  echo 'Sorry no match found!';  
//              }else{
//                  while($row = mysql_fetch_array($result)){
//                   $name   = $row['name'];
//                   $definition = $row['definition'];
//                   $ctstatus = $row['ctstatus'];
//                   
//                   $output .= '<div>'.$name.''.$definition.''.$ctstatus.'</div>';
//                  }
//                  $twig->render($output);
//              }
//      }
    mysql_connect("localhost", "admin", "admin123");
      mysql_select_db("gmdn");
      
      $selectQuery = "SELECT `termIsIVD`,`termName`,`termDefinition`FROM `term`";
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
    echo $twig->render('searchPage.twig', array('uploads' => $items) );
       
    }
      
//     
////if we got something through $_POST
//if (isset($_POST['search'])) {
//    // here you would normally include some database connection
////    include('db.php');
//    mysql_connect("localhost", "admin", "admin123");
//  $db =  mysql_select_db("GMDN");
//   
//    // never trust what user wrote! We must ALWAYS sanitize user input
//    $word = mysql_real_escape_string($_POST['search']);
//    $words = htmlentities($word);
//    // build your search query to the database
//    $sql = "SELECT * FROM collectiveterm14_7,term14_7 WHERE content LIKE '%" . $words . "%' ORDER BY title LIMIT 10";
//    $res = mysql_query($sql);
//    echo $res;
//    // get results
//    //$row = $db->select_list($result);
//    if(count($res)) {
//        $end_result = '';
//        foreach($res as $r) {
//            $result         = $r['*'];
//            // we will use this to bold the search word in result
//            $bold           = '<span class="found">' . $word . '</span>';    
//            $end_result     .= '<li>' . str_ireplace($word, $bold, $result) . '</li>';            
//        }
//        echo $end_result;
//    } else {
//        echo '<li>No results found</li>';
//    }
//}
