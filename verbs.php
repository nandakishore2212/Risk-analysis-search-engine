<?php
if(isset($_POST['data']))
   echo $_POST['filename1'];
$filename1 = $_POST['filename1'];
$filename2 = $_POST['filename2'];
$file_type = "xmi";
$description = "generated from create xmi";
$user = "admin";

//load the file to read from
$tempdoc = new DOMDocument();
$tempdoc->validateOnParse = true;
// CHANGE THE FILE NAME TO WHICH EVER FILE YOU WANT TO CONVERT
$tempdoc->load('./xmlFiles/'.$filename1);
// load the file to write into
$template = new DOMDocument("1.0", "UTF-8");
// DO NOT CHANGE THIS FILE NAME
$template->load('pro1.xml');
//specifying the place where the device class tags have to inserted in pro1.xml
$xpath = new DOMXpath($template);
$elements = $xpath->query('//uml:Model[@name="project1"]');
$elements1 = $xpath->query('//profileApplication[@name="test"]');


$xpath = new DOMXpath($tempdoc);
$nodes = $xpath->query('//*');
$nodeLength = $nodes->length;
$names = array();
foreach ($nodes as $node) {
    $names[] = $node->nodeName;
}
$d = $tempdoc->getElementsByTagName('device');
$a = $tempdoc->getElementsByTagName('classa');
$c = $tempdoc->getElementsByTagName('component');
//$at = $tempdoc->getElementsByTagName('attribute');
$dd = 0;
$aa = 0;
$cc = 0;
//$att = 0;
foreach ($names as $ul) {
    // echo "ul: ".$ul. "<br>"; 

    if ($ul == "device") {

        echo"device: " . $d->item($dd)->nodeValue . "<br>";
        $parentDevice = $d->item($dd);

        $newDeviceTag = $template->createElement("packagedElement");
        $newDeviceTag->setAttribute("xmi:type", "uml:Class");
        //generating random numbers for creating new id by appending it to the original one
        $rdmDev = 1+$dd;
        $newDeviceTag->setAttribute("xmi:id", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmDev . "Jtw");
        $newDeviceTag->setAttribute("name", $d->item($dd)->nodeValue);
         //inserting into database
        $deviceId = "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmDev . "Jtw";
        $deviceName = $d->item($dd)->nodeValue;
        
        //connect to database
        mysql_connect("localhost", "admin", "admin123");
        mysql_select_db("modeldb");
        $queryD = "insert into class_system(refId,name,xml_filename,xmi_filename)values('".$deviceId."','".$deviceName."','".$filename1."','".$filename2."')";
        mysql_query($queryD);
        echo'success';
        $queryF = "insert into xml_files(xml_filename,modelio_filename)values('".$filename1."','".$filename2."')";
        mysql_query($queryF);
        echo'success';
        //assigning where the new element has to put in the pro1.xml file. 
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newDeviceTag, $refnode);
        $dd++;
        continue;
    }
    if ($ul == "classa") {
        $parentAssembly = $a->item($aa);
        echo"assembly and parent: " . $parentAssembly->nodeValue . "" . $parentDevice->nodeValue . "<br>";

        $newAssocTag = $template->createElement("packagedElement");
        $newAssocTag->setAttribute("xmi:type", "uml:Association");
        $rdmAssID = 100+$aa;
        $newAssocTag->setAttribute("xmi:id", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAssID . "tw");
        $rdmDOA = 200+$aa;
        $rdmMEAssoc = 300+$aa;
        $newAssocTag->setAttribute("memberEnd", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmDOA . "tw _WiWHNzQBEeegr7mwXb5Jtw" . $rdmMEAssoc . "tw");
        //create new owned end tag
        $newOwnEndTag = $template->createElement("ownedEnd");
        $newOwnEndTag->setAttribute("xmi:id", "_WiWHNzQBEeegr7mwXb5Jtw" . $rdmMEAssoc . "tw");
        $newOwnEndTag->setAttribute("visibility", "public");
        $newOwnEndTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmDev . "Jtw");
        $newOwnEndTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAssID . "tw");
        //create new lowerValue tag
        $newLowerValueTag = $template->createElement("lowerValue");
        $newLowerValueTag->setAttribute("xmi:type", "uml:LiteralInteger");
        $rdmATLV = 400+$aa;
        $newLowerValueTag->setAttribute("xmi:id", "_WiWHODQBEeegr7mwXb5Jtw" . $rdmATLV . "tw");
        $newOwnEndTag->appendChild($newLowerValueTag);
        $newAssocTag->appendChild($newOwnEndTag);
        $refnode1 = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newAssocTag, $refnode1);
        //create new assembly blocks
        $newAssemblyTag = $template->createElement("packagedElement");
        $newAssemblyTag->setAttribute("xmi:type", "uml:Class");
        //generating random numbers for creating new id by appending it to the original one
        $rdmATID = 500+$aa;
        $newAssemblyTag->setAttribute("xmi:id", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmATID . "tw");
        $newAssemblyTag->setAttribute("name", $a->item($aa)->nodeValue);
        
        $assemblyId = "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmATID . "tw";
        $assemblyName = $a->item($aa)->nodeValue;
        
        //connect to database
        mysql_connect("localhost", "admin", "admin123");
        mysql_select_db("modeldb");
        $queryA = "insert into class_assembly(refId,name,xml_filename,xmi_filename)values('".$assemblyId."','".$assemblyName."','".$filename1."','".$filename2."')";
        mysql_query($queryA);
        echo'success';
        
        //assigning where the new element has to put in the pro1.xml file.    
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newAssemblyTag, $refnode);
        //create new device owned attribute tag
        $newDevOwnAttrTag = $template->createElement("ownedAttribute");
        $newDevOwnAttrTag->setAttribute("xmi:id", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmDOA . "tw");
        $newDevOwnAttrTag->setAttribute("name", $a->item($aa)->nodeValue);
        $newDevOwnAttrTag->setAttribute("visibility", "public");
        $newDevOwnAttrTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmATID . "tw");
        $newDevOwnAttrTag->setAttribute("aggregation", "composition");
        $newDevOwnAttrTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAssID . "tw");
        $newDeviceTag->appendChild($newDevOwnAttrTag);
        $aa++;
        continue;
    }
    if ($ul == "component") {
        echo"clenght; " . $c->length;
        $parentComponent = $c->item($cc);
        echo"component and parent: " . $parentComponent->nodeValue . "" . $parentAssembly->nodeValue . "<br>";
       // $parentComponent = $c->item($cc);

        $newCompAssocTag = $template->createElement("packagedElement");
        $newCompAssocTag->setAttribute("xmi:type", "uml:Association");
        $rdmCAT = 600+$cc;
        $newCompAssocTag->setAttribute("xmi:id", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmCAT . "tw");
        $rdmMECA = 700+$cc;
        $rdmOAC = 800+$cc;
        $newCompAssocTag->setAttribute("memberEnd", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmOAC . "tw _WiWHNzQBEeegr7mwXb5Jtw" . $rdmMECA . "tw");
        //create new owned end tag
        $newCAOwnEndTag = $template->createElement("ownedEnd");
        $newCAOwnEndTag->setAttribute("xmi:id", "_WiWHNzQBEeegr7mwXb5Jtw" . $rdmMECA . "tw");
        $newCAOwnEndTag->setAttribute("visibility", "public");
        $newCAOwnEndTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmATID . "tw");
        $newCAOwnEndTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmCAT . "tw");
        //create new lowerValue tag
        $newCALowerValueTag = $template->createElement("lowerValue");
        $newCALowerValueTag->setAttribute("xmi:type", "uml:LiteralInteger");
        $rdmCALV = 900+$cc;
        $newCALowerValueTag->setAttribute("xmi:id", "_WiWHODQBEeegr7mwXb5Jtw" . $rdmCALV . "tw");
        $newCAOwnEndTag->appendChild($newCALowerValueTag);
        $newCompAssocTag->appendChild($newCAOwnEndTag);
        $refnode1 = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newCompAssocTag, $refnode1);
        //create new component tags
        $newComponentTag = $template->createElement("packagedElement");
        $newComponentTag->setAttribute("xmi:type", "uml:Class");
        $rdmCTID = 1000+$cc;
        $newComponentTag->setAttribute("xmi:id", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmCTID . "tw");
        $newComponentTag->setAttribute("name", $c->item($cc)->nodeValue);
        
        $componentId = "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmCTID . "tw";
        $componentName = $c->item($cc)->nodeValue;
        
        //connect to database
        mysql_connect("localhost", "admin", "admin123");
        mysql_select_db("modeldb");
        $queryC = "insert into class_components(refId,name,xml_filename,xmi_filename)values('".$componentId."','".$componentName."','".$filename1."','".$filename2."')";
        mysql_query($queryC);
        echo'success';
        //assigning where the new element has to put in the pro1.xml file.    
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newComponentTag, $refnode);
        //create new device owned attribute tag
        $newAssOwnAttrTag = $template->createElement("ownedAttribute");

        $newAssOwnAttrTag->setAttribute("xmi:id", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmOAC . "tw");
        $newAssOwnAttrTag->setAttribute("name", $c->item($cc)->nodeValue);
        $newAssOwnAttrTag->setAttribute("visibility", "public");
        $newAssOwnAttrTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmCTID . "tw");
        $newAssOwnAttrTag->setAttribute("aggregation", "composition");

        $newAssOwnAttrTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmCAT . "tw");
        $newAssemblyTag->appendChild($newAssOwnAttrTag);

        $cc++;
        continue;
    }
    
        if ($ul == "attribute") {
        echo"atlength; " . $at->length;
        echo"attribute and parent: " . $at->item($att)->nodeValue . "" . $parentComponent->nodeValue . "<br>";
        $parentAttribute = $at->item($att);

        $newAttrAssocTag = $template->createElement("packagedElement");
        $newAttrAssocTag->setAttribute("xmi:type", "uml:Association");
        $rdmAAT = 1100+$att;
        $newAttrAssocTag->setAttribute("xmi:id", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAAT . "tw");
        $rdmMEAA = 1200+$att;
        $rdmOAA = 1300+$att;
        $newAttrAssocTag->setAttribute("memberEnd", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmOAA . "tw _WiWHNzQBEeegr7mwXb5Jtw" . $rdmMEAA . "tw");
        //create new owned end tag
        $newAtAOwnEndTag = $template->createElement("ownedEnd");
        $newAtAOwnEndTag->setAttribute("xmi:id", "_WiWHNzQBEeegr7mwXb5Jtw" . $rdmMEAA . "tw");
        $newAtAOwnEndTag->setAttribute("visibility", "public");
        $newAtAOwnEndTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmCTID . "tw");
        $newAtAOwnEndTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAAT . "tw");
        //create new lowerValue tag
        $newAtALowerValueTag = $template->createElement("lowerValue");
        $newAtALowerValueTag->setAttribute("xmi:type", "uml:LiteralInteger");
        $rdmAALV = 1400+$att;
        $newAtALowerValueTag->setAttribute("xmi:id", "_WiWHODQBEeegr7mwXb5Jtw" . $rdmAALV . "tw");
        $newAtAOwnEndTag->appendChild($newAtALowerValueTag);
        $newAttrAssocTag->appendChild($newAtAOwnEndTag);
        $refnode1 = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newAttrAssocTag, $refnode1);
        //create new component tags
        $newAttributeTag = $template->createElement("packagedElement");
        $newAttributeTag->setAttribute("xmi:type", "uml:Class");
        $rdmAtTID = 1500+$att;
        $newAttributeTag->setAttribute("xmi:id", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmAtTID . "tw");
        $newAttributeTag->setAttribute("name", $at->item($att)->nodeValue);
        
        $attributeId = "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmAtTID . "tw";
        $attributeName = $at->item($att)->nodeValue;
        //connect to database
        mysql_connect("localhost", "admin", "admin123");
        mysql_select_db("modeldb");
        $queryAt = "insert into class_attributes(refId,name,xml_filename,xmi_filename)values('".$attributeId."','".$attributeName."','".$filename1."','".$filename2."')";
        mysql_query($queryAt);
        echo'success';
        //assigning where the new element has to put in the pro1.xml file.    
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newAttributeTag, $refnode);
        //create new device owned attribute tag
        $newCompOwnAttrTag = $template->createElement("ownedAttribute");

        $newCompOwnAttrTag->setAttribute("xmi:id", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmOAA . "tw");
        $newCompOwnAttrTag->setAttribute("name", $at->item($att)->nodeValue);
        $newCompOwnAttrTag->setAttribute("visibility", "public");
        $newCompOwnAttrTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmAtTID . "tw");
        $newCompOwnAttrTag->setAttribute("aggregation", "composition");

        $newCompOwnAttrTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAAT . "tw");
        $newComponentTag->appendChild($newCompOwnAttrTag);

        $att++;
        continue;
    }
}

 echo $template->save('./modelioFiles/'.$filename2, LIBXML_NOEMPTYTAG);  
 