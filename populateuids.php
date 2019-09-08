<?php
    session_start();

    $filename = $_SESSION["filename"];
    $device = $_GET['device'];                           
    $version = $_GET['version'];
    $version = str_pad($version,4,"0",STR_PAD_LEFT); 
    $uidbase = $device."-".$version."-";
    $compno = 0;
    //echo $uid.$filename;
    
    $myfile = fopen($filename, "r") or die("Unable to open file!");
    //echo $selectQuery."<hr>";
    $xmlfile = fread($myfile,filesize($filename));
    
    $check =0;
    if(strpos($xmlfile,"<uid>") != false){
        while($check<strlen($xmlfile)){
            $startloc = strpos($xmlfile, "<uid>", $check);
            $endloc = strpos($xmlfile, "</uid>", $check);
            if(($startloc !=false) && ($endloc != false)){
                $begin = substr($xmlfile,0, $startloc);
                $end = substr($xmlfile, $endloc+6);
                $xmlfile = $begin.$end;  
                $check = $startloc;
            } 
            else{
                $check = $check +1;
            }
        }
    }
    
    $loc = strpos($xmlfile, "<package>");
    $begin = substr($xmlfile, 0, $loc);
    $end = substr($xmlfile, $loc);
    $uid=$uidbase."pa"."000000";
    $xmlfile = $begin."<uid>".$uid."</uid>".$end;
    $iddev=0;
    $idass=0;
    $j=0;
    
    
   
    while($j<strlen($xmlfile)){
        $deloc = strpos($xmlfile, "<device>", $j);
        $assloc = strpos($xmlfile, "<assembly>", $j);
        
        
        if((($deloc<$assloc)&& ($deloc!= FALSE))||(($assloc == FALSE)&& ($deloc!= FALSE))){
            $begin = substr($xmlfile, 0, $deloc);
            $end = substr($xmlfile, $deloc);
            $iddev++;
            $idass = 0;
            $iddev2char = str_pad($iddev,2,"0",STR_PAD_LEFT); 
            $uid=$uidbase."de".$iddev2char."0000";
            $xmlfile = $begin."<uid>".$uid."</uid>".$end;
        //echo $xmlfile."<hr>";
             $j = $deloc+40;
            
        }
        else if((($assloc<$deloc)&& ($assloc!= FALSE))||(($deloc == FALSE)&& ($assloc!= FALSE))){
            $begin = substr($xmlfile, 0, $assloc);
            $end = substr($xmlfile, $assloc);
            $idass++;
            $iddev2char = str_pad($iddev,2,"0",STR_PAD_LEFT); 
            $idass4char = str_pad($idass,4,"0",STR_PAD_LEFT);             
            $uid=$uidbase."as".$iddev2char.$idass4char;
            $xmlfile = $begin."<uid>".$uid."</uid> ".$end;
        //echo $xmlfile."<hr>";
            
                
                                        
            $j = $assloc+40;
        }
        else{
            $j=$j+10;
        }
        
        //echo $j."  ".strlen($xmlfile)."<br>";
    }
    $myfile = fopen($filename, "w") or die("Unable to open file!");    
    fwrite($myfile, $xmlfile);
    fclose($myfile); 
    echo "<br><h1 style='color:green'>The XML file has been updated and saved as 'filenameversion2.xml'".'<a href="http://localhost/ProjectModel/tree.php">Click here to return to the ADD GMDN/UMLS data page</a></h1>';
?>
