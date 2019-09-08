<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
       

            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
            <link rel="stylesheet" href="css/easyTree.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="tree/easyTree.js"></script>
            <style>
                body, html, .container-fluid {
                    height: 100%;
                }
                .jumbotron{
                    background-color:#ECF0F1;
                }
            </style>
                    
            <title>{% block title %} Search GMDN database{% endblock %} </title>
      
    </head>
     
<section id="table">
    <div class="container-fluid">

        <table class="table table-bordered" id="gmdn">
            <thead>
                <tr>
                    <th>Term code (Click to select)</th>
                    <th>IVD/Non-IVD </th>
                    <th>Term Name</th>
                    <th>Term Definition</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <?php
                                     
                     
                     //$_SESSION["test"] = 'hello';
                        mysql_connect("localhost", "admin", "admin123");
                        mysql_select_db("gmdn");

                        $query = $_GET['query']; 
                          // gets value sent over search form
                        $ivd = $_GET['optradio'];
                        
                        $compname = $_GET['comp'];                        
                        $_SESSION["compname"]= $compname;
                        $min_length = 3;
                          // you can set minimum length of the query if you want
                        //echo $compname."<br>";
                        if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then

                            $query = htmlspecialchars($query); 
                              // changes characters used in html to their equivalents, for example: < to &gt;
                            $query = mysql_real_escape_string($query);
                              // makes sure nobody uses SQL injection

                            $selectQuery = "SELECT `termCode`,`termIsIVD`,`termName`,`termDefinition`FROM `term` WHERE ((`termName` LIKE '%".$query."%') AND (`termIsIVD` = '$ivd' )) ";
                                                       
                            if(!($selectResult = mysql_query($selectQuery))){
                                echo 'Currently no files in the database.';
                            }
                            
                            //echo "<td>"."<a href='./updatexml.php?data=$results["termCode"]&data2=$results["termName"]'> $results["termCode"] </a>"."</td>";
                            //<a href="./display.php?data=Data1&data2=Data120">Click here</a>        
                            else{
                                $selectResult = mysql_query($selectQuery);
                                //<form method="GET" action="searchGMDN.php">
                                while($results = mysql_fetch_array($selectResult)){  
                                    echo "<tr>";  
                                    echo "<td>"."<a href='./updatexml.php?code=".$results['termCode']."&name=".$results['termName']."'>".$results['termCode']."</a>"."</td>";
                                    echo "<td>".$results['termIsIVD']."</td>";
                                    echo "<td>".$results['termName']."</td>";
                                    echo "<td>".$results['termDefinition']."</td>";
                                    echo "</tr>";
                                }
                          
                            }
                          //echo $twig->render('searchPage.twig', array('uploads' => $items) );


                          }
                          else{ // if query length is less than minimum
                              echo "Minimum length is ".$min_length;
                          }

                        

                    ?>
                </tr>
                
            </tbody>

        </table>
    </div>
</section>

<br><h1 style='color:green'><a href="http://localhost/ProjectModel/tree.php">Click here to return to the Search page</a></h1>
        