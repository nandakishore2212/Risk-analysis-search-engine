<?php
session_start();
?>

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


        <style>
            body, html, .container-fluid {
                height: 100%;
            }
            .jumbotron{
                background-color:#ECF0F1;
            }
        </style>
       <script language='javascript'>

        function setReadOnly(obj)
        {
            var elem = document.getElementById("form1");  
            var nodes = elem.getElementsByTagName('*');

            var elem2 = document.getElementById("form2");  
            var nodes2 = elem2.getElementsByTagName('*');
            if(obj.value == "yes")
            {            

                elem.style.backgroundColor = "#ffffff";
                elem2.style.backgroundColor = "#eeeeee";
                for(var i = 0; i < nodes.length; i++){
                    nodes[i].disabled = false;
                }
                for(var i = 0; i < nodes2.length; i++){
                    nodes2[i].disabled = true;
                }
            //elem.readOnly = 0;            
            } 
            else {
                elem.style.backgroundColor = "#eeeeee";
                elem2.style.backgroundColor = "#ffffff";
                for(var i = 0; i < nodes.length; i++){
                     nodes[i].disabled = true;
                } 
                for(var i = 0; i < nodes2.length; i++){
                    nodes2[i].disabled = false;
                }
            }
        }

        </script>   

        <title>{% block title %} Risk analysis{% endblock %} </title>
        
    
      
    </head>
    <body>  
        
        <table class="table table-bordered" id="infotable">
                    
            <tbody>


                <tr>
<?php
     
error_reporting(0);

    if(isset($_GET["code"]) )
    {
        $termcode = $_GET["code"];                               
    }
    //echo $termcode;
    //$termcode = 'bonn-0003-as1010001';
    mysql_connect("localhost", "admin", "admin123");
    mysql_select_db("modeldb");
    $headingQuery = 'recordNo;UID;elementType;Class;elementName;Parent;GMDNname;GMDNDefinition;GMDNCollectiveterms;UMLSterm;UMLSdefinition;UMLSrelations;criticalCharacteristics;ccLocator;otherCharacteristics;KBlink;Failure mode;FMHeadline;Severity;Occurence;Detection;RPN;CriticalitySeverity;CriticalityOccurence;Criticality;CriticalityAccessment;KBID';
    $headings =  explode(';', $headingQuery );
    $dataQuery = "SELECT * FROM `scfiii_fmea_system_newmodelelements_180617_final` WHERE `UID` = '$termcode' ";

    if(!($dataResult = mysql_query($dataQuery))){
    echo 'Currently no files in the database.';
    }
    else{
        $dataResult = mysql_query($dataQuery);
        //<form method="GET" action="searchGMDN.php">
        //$ii= 0;
        while($dataresultlist = mysql_fetch_array($dataResult)){  
            for($ii=0; $ii<26; $ii++)
            {
                echo "<tr>";  
                echo "<td>".$headings[$ii]."</td>";
                echo "<td>".$dataresultlist[$ii]."</td>";
                echo "</tr>";
            }
            $kbfulllist = $dataresultlist[26];
        }
        echo "<tr>";  
        //echo "<td>KB Links</td>";
        echo "<td>".$headings[26]."</td>";
        
        $kblist = explode(';',$kbfulllist);
        //$kbbasepath = './KB/';
        echo "<td>";
        //echo $kbfulllist;
        
        for($jj =0; $jj < sizeof($kblist); $jj++){
            $kblist[$jj] = trim($kblist[$jj]);
            echo "<a href='./KB/".$kblist[$jj].".htm' target = '_blank'>".$kblist[$jj]."</a>";
            echo "<br>";
        }
         
        
        echo "</td>";
        echo "</tr>";
    }
 
 
    ?>

                    </tr>

                    </tbody>
                    </table>
    </body>