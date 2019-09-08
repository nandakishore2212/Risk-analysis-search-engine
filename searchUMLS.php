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
                    
            <title>{% block title %} Search UMLS database{% endblock %} </title>
      
    </head>
    <body>     
<section id="table">
    <div class="container-fluid">

        <table class="table table-bordered" id="gmdn">
            <thead>
                <tr>
                    <th>CUI (Click to select)</th>
                    <th>Name </th>
                    <th>Source</th>                   
                </tr>
            </thead>
            <tbody>

                <tr>
                    <?php
                                       
                     
                     //$_SESSION["test"] = 'hello';
                        //mysql_connect("localhost", "admin", "admin123");
                        //mysql_select_db("gmdn");

                        $query = $_GET['query']; 
                          // gets value sent over search form
                        //echo $query."<br>";                        
                        $compname = $_GET['comp2'];                        
                        $_SESSION["compname"]= $compname;
                        $min_length = 3;
                        
                        //echo $compname."<br>";
                          // you can set minimum length of the query if you want

                        if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then
                        $query = htmlspecialchars($query);
                        //echo $query."no 1";
                        //$comm ='python D:\software\xampp\htdocs\ProjectModel\testpy.py '.$query;
                        $output= shell_exec('python D:\software\xampp\htdocs\ProjectModel\searchterms.py '.$query);
                        //echo $output;
                        //echo '<hr> Execution completed';               
                        $sets = explode("*", $output); 
                        $i = 0;
                        while($i < count($sets)){  
                            echo "<tr>";  
                            //echo "<td>"."<a href='./updatexml.php?code=".$results['termCode']."&name=".$results['termName']."'>".$results['termCode']."</a>"."</td>";
                            $words = explode("|", $sets[$i]); 
                            $j = 0;
                            while($j < count($words)){
                                if($j==0){
                                    echo "<td>"."<a href='./updateumlsxml.php?code=".$words[$j]."&name=".$words[$j+1]."'>".$words[$j]."</a>"."</td>";
                                }
                                else{
                                    echo "<td>".$words[$j]."</td>";                                
                                }
                                $j++;
                            }
                            echo "</tr>";
                            $i++;
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
    </body>
