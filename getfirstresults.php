<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
           
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/easyTree.css">
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="src/easyTree.js"></script>
        <script>
            function resulttable() {
              alert("Hello");
              /*
              if (str=="") {
                document.getElementById("resultlist").innerHTML="";
                return;
              }
        */
              if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
              } else { // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
              }
              xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                  //document.getElementById("resultlist").innerHTML=this.responseText;
                  document.getElementById("resultlist").innerHTML='wazzup';
                }
              }
              //xmlhttp.open("GET","showtableresult.php?q="+str,true);
              //xmlhttp.send();
            }
            
        </script>

    </head>
    
    <body>
        
        
<?php 

    error_reporting(0);
    mysql_connect("localhost", "admin", "admin123");
    mysql_select_db("modeldb");
    $compid = $_GET['q'];
    $compid = trim($compid);
    $_SESSION["compid"]= $compid;
    //echo $compname;

    $selectQuery = "SELECT `BonnUID_ccLocator` FROM `scfiii_fmea_system_aachenuid_bonnuid` WHERE `chenUID_cc` = '$compid' ";
    //$selectQuery = "SELECT `bonnUID` FROM `namevsuidtest` WHERE `chenName` = '$compid' ";

    if(!($selectResult = mysql_query($selectQuery))){
        echo 'Currently no files in the database.';
    }
    else{
        $selectResult = mysql_query($selectQuery);
        //<form method="GET" action="searchGMDN.php">
        while($results = mysql_fetch_array($selectResult)){                                        
            $_SESSION["results"] = $results['BonnUID_ccLocator'];
        } 

        $firstresults = explode(';', $_SESSION["results"] );
        echo "<h4>Results for Aachen model element: ".$_SESSION["compid"]."</h5> <br>";
        
        //echo "<form>";
        //echo '<select name="resulttab" onchange="resulttable(this.value)">';
        //echo '<option value="">Select a result:</option>';
        for($x = 0; $x < count($firstresults); $x++) {

            //$selectQuery = "SELECT `elementName` FROM `scfiii_fmea_system_newmodelelements_180617` WHERE `UID` = 'bonn-0003-as1010001' ";
            $selectQuery = "SELECT `elementName` FROM `scfiii_fmea_system_newmodelelements_180617_final` WHERE `UID` = '$firstresults[$x]' ";
                        
//$selectQuery = "SELECT `elementName` FROM `maintabletest` WHERE `UID` = '$firstresults[$x]' ";

            if(!($selectResult = mysql_query($selectQuery))){
                echo 'Currently no files in the database.';
            }
            else{
                $selectResult = mysql_query($selectQuery);
                //<form method="GET" action="searchGMDN.php">
                while($results = mysql_fetch_array($selectResult)){ 
                    //echo '<select name="firstres">';
                    //echo "<option value=".$results['elementName'].">".$results['elementName']."</option>";

                    //echo '<input type="button" id="button" value="$results['elementName']" onclick="clickMe(this)"/>';
                    //echo '<a href="" onclick="resulttable()">'.$results['elementName']."</a>";  
                    echo "<a href='./showtableresult.php?code=".$firstresults[$x]."' target = '_blank' >".$results['elementName']."</a>";
                }               
            }
            
            
            echo "<br>";                           

        }
        
    }
 
 
?>    
 
    
    