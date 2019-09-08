<?php
require 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

echo $twig->render('reverseModelPage.twig');
if(!empty ($_POST)){
      $_FILES['model_file']['tmp_name'];
      $filename1 = ($_FILES['model_file']['name']);
      $uniqid = uniqid();
      move_uploaded_file($_FILES['model_file']['tmp_name'], './myModelFiles/' . $uniqid . '-' . $_FILES['model_file']['name']);
      $tempname = $uniqid . '-' . $_FILES['model_file']['name'];
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
      

//create a DOM element
$tempdoc = new DOMDocument();
$tempdoc->validateOnParse = true;
// load the xml file exported from modelio
$tempdoc->load($filename1);
$template = new DOMDocument("1.0", "UTF-8");
$template->formatOutput = true;
//recursive function to find the child nodes of each node in the xml file. 
//the function is called whenever a node has children, to parse through each child element.
function traverseXML($children) {
    foreach ($children as $child) {
        if ($child->nodeType == XML_ELEMENT_NODE) {
            $tagName = $child->tagName;
            $hasChild = $child->hasChildNodes();
            //  echo"tag name: " . $tagName . "<br>";
            //  echo"has child; " . $hasChild . "<br>";
            if ($tagName == "packagedElement") {
                writeXML($child);
            }
            if ($hasChild == 1) {
                $level1 = $child->childNodes;
                traverseXML($level1);
            }
        }
    }
}
// function accesses the name and id of the classes in the xml file (from packagedElement tag)
function writeXML($child) {
    // access the global variable
    global $tempdoc;
    //initialize the new xml file
    global $template;
   
    //doesn't do anything if the packagedElement's type is Association
    if ($child->getAttribute('xmi:type') == "uml:Association") {
        return;
    }
// echo"writeXML child: ".$child->tagName."<br>"; 
    // get the name and id attribute values for each packagedElement class tag
    $name = $child->getAttribute('name');
    $id = $child->getAttribute('xmi:id');
    echo"name: " . $name . "<br>";
    echo"id: " . $id . "<br>";
    // get the Id in the new xml file and check if the new id is already present in it
    // if the new id is not present then write it and it's corresponding name into the xml file
    $idCheck = $template->getElementsByTagName('id');
    $idLen = $idCheck->length;
    $getIdinTemplate = NULL;
    // iterate through all the id tags to check if id is already present in new xml file
    for($ii = 0 ; $ii < $idLen; $ii++){
        //if the id is present assign that value to getIdinTemplate and break the loop 
         if($idCheck->item($ii)->nodeValue == $id){
            $getIdinTemplate = $idCheck->item($ii);
            break;
         }
    }
    // if the getIdinTemplate wasn't assigned anything
    if (!$getIdinTemplate) {
        //to identify if the child node is a device, assembly or component look for the tagname in the LocalProfile
        $tag = findTagName($id);
        echo"tag: " . $tag . "<br>";
        echo"add element<br>";
        $ul1Tag = $template->createElement("ul");
        $li1Tag = $template->createElement("li");
        //creating the new id tag and adding the corresponding id value in the new xml file
        $newId1Tag = $template->createElement("id");
        $idElement1 = $template->createTextNode($id);
        $newId1Tag->appendChild($idElement1);
        $li1Tag->appendChild($newId1Tag);
        
        //creating the new element tag and adding its corresponding value in the new xml file
        $newElement1Tag = $template->createElement($tag);
        $newElement1 = $template->createTextNode($name);
        $newElement1Tag->appendChild($newElement1);
        $li1Tag->appendChild($newElement1Tag);        
        
        $ul1Tag->appendChild($li1Tag);
        $template->appendChild($ul1Tag);
        
     
    }
    else{
        $li1Tag = $getIdinTemplate->parentNode;       
    }
    if ($child->hasChildNodes()) {
        $ul2Tag = $template->createElement("ul");
        foreach ($child->childNodes as $ownedAttr) {
            if ($ownedAttr->nodeType == XML_ELEMENT_NODE) {
                $type = $ownedAttr->getAttribute('type');
                echo"type: ".$type."<br>";
                $getElembyId = findID($type);
              //  $getElembyId = $tempdoc->getElementById($type);
              //  echo"getElement by id lenght: ".$getElembyId->length."<br>";
                if ($getElembyId){
                    $getIdinTemplate = $template->getElementById($type);
                    if ($getIdinTemplate) {
                        continue;
                    }
                    $childTag = findTagName($type);
                    $childName = $getElembyId->getAttribute('name');
                    echo"added child element: " . $getElembyId->getAttribute('name') . "<br>";
                    
                    $li2Tag = $template->createElement("li");
                    //creating the new id tag and adding the corresponding id value in the new xml file
                    $newId2Tag = $template->createElement("id");
                    $idElement2 = $template->createTextNode($type);
                    $newId2Tag->appendChild($idElement2);
                    $li2Tag->appendChild($newId2Tag);

                    //creating the new element tag and adding its corresponding value in the new xml file
                    $newElement2Tag = $template->createElement($childTag);
                    $newElement2 = $template->createTextNode($childName);
                    $newElement2Tag->appendChild($newElement2);
                    $li2Tag->appendChild($newElement2Tag);

                    $ul2Tag->appendChild($li2Tag);
                    $li1Tag->appendChild($ul2Tag);
                    
                }
            }
        }
    }
}
function findID($id){
    global $tempdoc;
    $elem = $tempdoc->getElementsByTagName("packagedElement");
    $elemLen = $elem->length;
    for($e = 0 ; $e<$elemLen; $e++){
        $elemID = $elem->item($e)->getAttribute('xmi:id');
        if($elemID == $id){
            return $elem->item($e);
        }
    }
    return null; 
}

function findTagName($id) {
    global $tempdoc;

    $getElembyTag = $tempdoc->getElementsByTagNameNS('http:///schemas/LocalProfile/_eF2OYGZVEeemQdBTYWR5CQ/0', "device");

    //echo"node type: ".$getElembyTag->item(0)->nodeType."<br>";
    //echo"tagname: ".$getElembyTag->item(0)->tagName."<br>";
    $gETLen = $getElembyTag->length;
    echo "len: " . $gETLen . "<br>";
    for ($t = 0; $t < $gETLen; $t++) {
        echo"getAtt: " . $getElembyTag->item($t)->getAttribute('base_NamedElement') . "<br>";
        if ($getElembyTag->item($t)->getAttribute('base_NamedElement') == $id) {
            echo"findTag: device <br>";
            return "device";
        }
    }
    $getElembyTag = $tempdoc->getElementsByTagNameNS('http:///schemas/LocalProfile/_eF2OYGZVEeemQdBTYWR5CQ/0', "assembly");
    $gETLen = $getElembyTag->length;
    for ($t = 0; $t < $gETLen; $t++) {
        if ($getElembyTag->item($t)->getAttribute('base_NamedElement') == $id) {
            return "assembly";
        }
    }
    $getElembyTag = $tempdoc->getElementsByTagNameNS('http:///schemas/LocalProfile/_eF2OYGZVEeemQdBTYWR5CQ/0', "component");
    $gETLen = $getElembyTag->length;
    for ($t = 0; $t < $gETLen; $t++) {
        if ($getElembyTag->item($t)->getAttribute('base_NamedElement') == $id) {
            return "component";
        }
    }
    $getElembyTag = $tempdoc->getElementsByTagNameNS('http:///schemas/LocalProfile/_eF2OYGZVEeemQdBTYWR5CQ/0', "attribute");
    $gETLen = $getElembyTag->length;
    for ($t = 0; $t < $gETLen; $t++) {
        if ($getElembyTag->item($t)->getAttribute('base_NamedElement') == $id) {
            return "attribute";
        }
    }
}

echo "has child nodes : " . $tempdoc->hasChildNodes() . "<br>";
echo "number of child nodes : " . $tempdoc->childNodes->length . "<br>";
$children = $tempdoc->childNodes; //xmi:XMI
traverseXML($children);
echo $template->save($filename2);
}