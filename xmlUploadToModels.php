<?php
if(!empty ($_POST)){
      $_FILES['model_file']['tmp_name'];
      $filename1 = ($_FILES['model_file']['name']);
      $uniqid = uniqid();
      move_uploaded_file($_FILES['model_file']['tmp_name'], './xmlFiles/' . $uniqid . '-' . $_FILES['model_file']['name']);
      $tempname = './xmlFiles/' . $uniqid . '-' . $_FILES['model_file']['name'];
      $filename2 = $_POST['opfilename'];
     // $filename = $_POST['filename'];
//      $filetype = $_POST['filetype'];
//      $description = $_POST['description'];
//      $user = $_POST['user'];
      
      // inserting into the database
      mysql_connect("localhost", "admin", "admin123");
      mysql_select_db("modeldb");
      $query = "insert into xml_files(xml_filename,modelio_filename,)values('".$filename1."', '".$filename2."')";
      mysql_query($query);
      


//load the file to read from
$tempdoc = new DOMDocument();
$tempdoc->validateOnParse = true;
// CHANGE THE FILE NAME TO WHICH EVER FILE YOU WANT TO CONVERT
echo $tempname;
$tempdoc->load($tempname);
// load the file to write into
$template = new DOMDocument("1.0", "UTF-8");
// DO NOT CHANGE THIS FILE NAME
$template->load('pro1.xml');
//specifying the place where the device class tags have to inserted in pro1.xml
$xpath1 = new DOMXpath($template);
$elements = $xpath1->query('//uml:Model[@name="project1"]');
$elements1 = $xpath1->query('//profileApplication[@name="test"]');


$xpath = new DOMXpath($tempdoc);
$nodes = $xpath->query('//*');
$nodeLength = $nodes->length;
$names = array();
foreach ($nodes as $node) {
    $names[] = $node->nodeName;
}
$d = $tempdoc->getElementsByTagName('system');
$a = $tempdoc->getElementsByTagName('assembly');
$c = $tempdoc->getElementsByTagName('component');
$at = $tempdoc->getElementsByTagName('attribute');
$dd = 0;
$aa = 0;
$cc = 0;
$att = 0;
foreach ($names as $ul) {
    // echo "ul: ".$ul. "<br>"; 

    if ($ul == "device") {

        echo"Device: " . $d->item($dd)->nodeValue . "<br>";
        $parentDevice = $d->item($dd);

        $newDeviceTag = $template->createElement("packagedElement");
        $newDeviceTag->setAttribute("xmi:type", "uml:Class");
        //generating random numbers for creating new id by appending it to the original one
        $rdmDev = 1+$dd;
        $newDeviceTag->setAttribute("xmi:id", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmDev . "Jtw");
        $newDeviceTag->setAttribute("name", $d->item($dd)->nodeValue);
        //assigning where the new element has to put in the pro1.xml file. 
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newDeviceTag, $refnode);
        $dd++;
        continue;
    }
    if ($ul == "assembly") {
        $parentAssembly = $a->item($aa);
        echo"Assembly: " . $parentAssembly->nodeValue . "<br> Parent Device: " . $parentDevice->nodeValue . "<br>";

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
        //assigning where the new element has to put in the pro1.xml file.    
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newAssemblyTag, $refnode);
        //create new device owned attribute tag
        $newDevOwnAttrTag = $template->createElement("ownedAttribute");
        $newDevOwnAttrTag->setAttribute("xmi:id", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmDOA . "tw");
        $newDevOwnAttrTag->setAttribute("name", $a->item($aa)->nodeValue);
        $newDevOwnAttrTag->setAttribute("visibility", "public");
        $newDevOwnAttrTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmATID . "tw");
        $newDevOwnAttrTag->setAttribute("aggregation", "composite");
        $newDevOwnAttrTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAssID . "tw");
        $newDeviceTag->appendChild($newDevOwnAttrTag);
        $aa++;
        continue;
    }
    if ($ul == "component") {
        echo"clenght; " . $c->length;
        $parentComponent = $c->item($cc);
        echo"component: " . $parentComponent->nodeValue . "<br> Parent Assembly: " . $parentAssembly->nodeValue . "<br>";
       // $parentComponent = $c->item($cc);

        $newCompAssocTag = $template->createElement("packagedElement");
        $newCompAssocTag->setAttribute("xmi:type", "uml:Association");
        $rdmCAT = 600+$cc;
        $newCompAssocTag->setAttribute("xmi:id", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmCAT . "tw");
        $rdmMECA = 1600+$cc;
        $rdmOAC = 2600+$cc;
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
        $rdmCALV = 3600+$cc;
        $newCALowerValueTag->setAttribute("xmi:id", "_WiWHODQBEeegr7mwXb5Jtw" . $rdmCALV . "tw");
        $newCAOwnEndTag->appendChild($newCALowerValueTag);
        $newCompAssocTag->appendChild($newCAOwnEndTag);
        $refnode1 = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newCompAssocTag, $refnode1);
        //create new component tags
        $newComponentTag = $template->createElement("packagedElement");
        $newComponentTag->setAttribute("xmi:type", "uml:Class");
        $rdmCTID = 4600+$cc;
        $newComponentTag->setAttribute("xmi:id", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmCTID . "tw");
        $newComponentTag->setAttribute("name", $c->item($cc)->nodeValue);
        //assigning where the new element has to put in the pro1.xml file.    
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newComponentTag, $refnode);
        //create new device owned attribute tag
        $newAssOwnAttrTag = $template->createElement("ownedAttribute");

        $newAssOwnAttrTag->setAttribute("xmi:id", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmOAC . "tw");
        $newAssOwnAttrTag->setAttribute("name", $c->item($cc)->nodeValue);
        $newAssOwnAttrTag->setAttribute("visibility", "public");
        $newAssOwnAttrTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmCTID . "tw");
        $newAssOwnAttrTag->setAttribute("aggregation", "composite");

        $newAssOwnAttrTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmCAT . "tw");
        $newAssemblyTag->appendChild($newAssOwnAttrTag);

        $cc++;
        continue;
    }
    
        if ($ul == "attribute") {
        echo"atlength; " . $at->length;
        echo"attribute: " . $at->item($att)->nodeValue . "<br> Parent Component: " . $parentComponent->nodeValue . "<br>";
        $parentAttribute = $at->item($att);

        $newAttrAssocTag = $template->createElement("packagedElement");
        $newAttrAssocTag->setAttribute("xmi:type", "uml:Association");
        $rdmAATID = 5600+$att;
        $newAttrAssocTag->setAttribute("xmi:id", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAATID . "tw");
        $rdmMEAA = 6600+$att;
        $rdmCOAA = 7600+$att;
        $newAttrAssocTag->setAttribute("memberEnd", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmCOAA . "tw _WiWHNzQBEeegr7mwXb5Jtw" . $rdmMEAA . "tw");
        //create new owned end tag
        $newAtAOwnEndTag = $template->createElement("ownedEnd");
        $newAtAOwnEndTag->setAttribute("xmi:id", "_WiWHNzQBEeegr7mwXb5Jtw" . $rdmMEAA . "tw");
        $newAtAOwnEndTag->setAttribute("visibility", "public");
        $newAtAOwnEndTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmCTID . "tw");
        $newAtAOwnEndTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAATID . "tw");
        //create new lowerValue tag
        $newAtALowerValueTag = $template->createElement("lowerValue");
        $newAtALowerValueTag->setAttribute("xmi:type", "uml:LiteralInteger");
        $rdmAALV = 8600+$att;
        $newAtALowerValueTag->setAttribute("xmi:id", "_WiWHODQBEeegr7mwXb5Jtw" . $rdmAALV . "tw");
        $newAtAOwnEndTag->appendChild($newAtALowerValueTag);
        $newAttrAssocTag->appendChild($newAtAOwnEndTag);
        $refnode1 = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newAttrAssocTag, $refnode1);
        //create new component tags
        $newAttributeTag = $template->createElement("packagedElement");
        $newAttributeTag->setAttribute("xmi:type", "uml:Class");
        $rdmAtTID = 9600+$att;
        $newAttributeTag->setAttribute("xmi:id", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmAtTID . "tw");
        $newAttributeTag->setAttribute("name", $at->item($att)->nodeValue);
        //assigning where the new element has to put in the pro1.xml file.    
        $refnode = $elements1->item(0)->previousSibling;
        $elements->item(0)->insertBefore($newAttributeTag, $refnode);
        //create new device owned attribute tag
        $newCompOwnAttrTag = $template->createElement("ownedAttribute");

        $newCompOwnAttrTag->setAttribute("xmi:id", "_WiWHTDQBEeegr7mwXb5Jtw" . $rdmCOAA . "tw");
        $newCompOwnAttrTag->setAttribute("name", $at->item($att)->nodeValue);
        $newCompOwnAttrTag->setAttribute("visibility", "public");
        $newCompOwnAttrTag->setAttribute("type", "_WiWHNDQBEeegr7mwXb5Jtw" . $rdmAtTID . "tw");
        $newCompOwnAttrTag->setAttribute("aggregation", "composite");

        $newCompOwnAttrTag->setAttribute("association", "_WiWHNjQBEeegr7mwXb5Jtw" . $rdmAATID . "tw");
        $newComponentTag->appendChild($newCompOwnAttrTag);

        $att++;
        continue;
    }
}

 echo $template->save('./modelioFiles/'.$filename2, LIBXML_NOEMPTYTAG); 
 echo" UPLOAD SUCCESSFUL!!!!"; 
 
} 