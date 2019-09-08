<?php
session_start();
    if(isset($_GET["code"]) && isset($_GET["name"]))
    {
        $termcode = $_GET["code"];
        $termname = $_GET["name"];
    }
    
    //echo $termname."<br>";
    
    $filename = $_SESSION["filename"];
    //$newfilename = basename($filename,".xml")."new.xml";
    //echo $newfilename."<br>";
    $compname = $_SESSION["compname"];
    $compname = trim($compname);
    //echo "xxx".$compname."xxx";
    //echo "<hr>". $_SESSION["test"]."<hr>";
    $myfile = fopen($filename, "r") or die("Unable to open file!");
    mysql_connect("localhost", "admin", "admin123");
    mysql_select_db("gmdn");
    
    $selectQuery = "SELECT `collectivetermID` FROM `termcollectiveterm` WHERE (`termCode` = $termcode)";
    //echo $selectQuery."<hr>";
    $xmlfile = fread($myfile,filesize($filename));
    //$xmlfile = htmlspecialchars($xmlfile);
    //echo htmlspecialchars($xmlfile);
    //echo $compname."<br>";
    //echo gettype($xmlfile);
    if (strpos($xmlfile, $compname) !== false) {
        $xmltext = explode($compname,$xmlfile );
        //echo "<hr>";
        /*foreach($xmltext as $term){
            echo htmlspecialchars($term)."<br>";
        }*/
        
        $removetext = $xmltext[0].$compname;
        $edittext = str_replace($removetext, '', $xmlfile);
        //echo htmlspecialchars($edittext);
        
        for( $i = 0; $i <= strlen($edittext); $i++ ) {
            $char = substr( $edittext, $i, 1 );
            if($char == '>'){
                $pos = $i;
                break;
            }
        }
        //echo "<hr>".$i."<hr>";
        
        $begin = substr($edittext, 0, $pos+1);
        $end = substr($edittext, $pos+1);
        $checkrepeat = substr($end, 0, 20);
        if (strpos($checkrepeat, 'attribute') == false) {
    
            $modtext = $xmltext[0].$compname.$begin. "<id>attribute</id><gmdnterm>".$termname."</gmdnterm><collectiveterms>";
            //echo htmlspecialchars($begin)."<br>";
            //echo htmlspecialchars($modtext)."<br>";
            //echo htmlspecialchars($end)."<br>";
           // print $xmltext[1];


            if($selectResult = mysql_query($selectQuery)){

                $selectResult = mysql_query($selectQuery);
                $row = mysql_fetch_array($selectResult, MYSQL_ASSOC);
    //            $string_version = implode(',', $original_array)
                do{
                    $row = implode(',', $row);
                    $collectivequery = "SELECT `name` FROM `collectiveterm` WHERE (`collectivetermID` = '$row')";
                    //$items[]=$row;
                    //echo $row."<br>";
                    //fwrite($myfile, $row);
                    $collectresult = mysql_query($collectivequery);
                    $collect = mysql_fetch_array($collectresult, MYSQL_ASSOC);
                    $collect = implode(',', $collect);
                    $modtext= $modtext.$collect.",";
                    //fwrite($myfile, $collect);
                    //echo $collect."<br>";
                }while ($row = mysql_fetch_array($selectResult, MYSQL_ASSOC));
                $modtext = $modtext."</collectiveterms>".$end;

                //echo htmlspecialchars($modtext);
            }   
        $myfile2 = fopen($filename, "w") or die("Unable to open file!");    
        fwrite($myfile2, $modtext);
        fclose($myfile2); 
        echo "<br><h1 style='color:green'>The XML file has been updated and saved as 'filename(version n+1)'".'<a href="http://localhost/ProjectModel/tree.php">Click here to return to the ADD GMDN data page</a></h1>';
        }
        else{
            echo "<br><h1 style='color:red'> You tried to add GMDN data for the same component</h1>".'<a href="http://localhost/ProjectModel/tree.php">Click here to return to the ADD GMDN data page</a></h1>';    
        }
    }
    else{
        echo "<br><h1 style='color:red'>Wrong component name chosen".'<a href="http://localhost/ProjectModel/tree.php">Click here to return to the ADD GMDN data page</a></h1>';
    }    
        
?>   

    