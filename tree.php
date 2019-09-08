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

        <title>{% block title %} Add GMDN data{% endblock %} </title>
      
    </head>


<?php

    require 'vendor/autoload.php';
    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader);

    if(isset ($_POST['uploadFile'])){
        $_FILES['model_file']['tmp_name'];
        move_uploaded_file($_FILES['model_file']['tmp_name'], $_FILES['model_file']['name']);
        $filename = $_FILES['model_file']['name'];
        $_SESSION["uploadedfile"] = $filename;
        $basefile = basename($_SESSION["uploadedfile"], ".xml");
        //echo $basefile."<br>";
        if(strpos($basefile, 'version')!= false){
            $verloc = strpos($basefile, 'version') + 7;
            $version = substr($basefile, $verloc);
            $vernum = (int)$version;
            $vernum++;
            $base = substr($basefile,0,$verloc);
            $newfile = $base.$vernum.".xml";
        }
        else{
            $newfile = $basefile."version2".".xml";
        }
        
        copy($filename,$newfile);
        //echo $newfile."<br>";
        //echo $xml->saveXML();

        $_SESSION["filename"] = $newfile;
    }
    $xml = new DOMDocument();
    $xml->load($_SESSION["uploadedfile"]);
    $xmlfile = $xml->saveXML();

    //echo "<hr>". $_SESSION["filename"]."<hr>";
    //echo $twig->render('addGMDNdata.twig');

?>

 
<body>
        
    <script type="text/javascript">    
         
    // script for building tree         
    
    $(document).ready(function() {
        var rawHtml;
        rawHtml = '<?php echo json_encode($xmlfile) ;?>';
        //alert(rawHtml);
	$("#draw").click(function () {
	//Placing xml data to the div
            rawHtml = rawHtml.replace(/<id>[\s\S]*?<\/id>/g, '');
            console.log(rawHtml);
            $("#esyTreeOnLoad").html(rawHtml);
            // alert(rawHtml);

            //Loading EasyTree On Load..
            $('#esyTreeOnLoad').EasyTree({
                selectable: true,
                deletable: true,
                editable: true,
                addable: true,
                addable_assembly: true,
                addable_component: true,
                i18n: {
                    confirmButtonLabel: 'Okay',
                    cancelButtonLabel: 'cancel',
                    deleteConfirmation: 'Delete this node?',
                }
            });
            $("a").click(function(e) {
                e.preventDefault();
        // this.append wouldn't work
                //$(this).append(" Clicked");
                //rawHTML = rawHTML.replace(/class="li_selected parent_li"/g, Hola);
            temper = $(this).html();
            var temp = temper;
            temp = temp.trim();
            var cuttemp = temp.substring(0,16);
            //alert(cuttemp);
            var ab = cuttemp.indexOf("de");
            //alert(ab);
            
            var ac = cuttemp.indexOf("as");
            var ad = cuttemp.indexOf("co");
            var ind = 0;
            //alert(ab+ac+ad);
            
            if(ab>ac){
                if(ab>ad){
                    ind = ab;
                }
                else{
                    ind = ad;
                }
            }
            else{
                if(ac>ad){
                    ind = ac;                    
                }
                else{
                    ind = ad;
                }
            }
            //alert(ind);
            var usabletemp = temp.substring(ind + 8);
            temp = usabletemp;
            
            
            alert("Add GMDN or UMLS data for the component:    "+temp);

            var comp=document.getElementById('comp');
            comp.value=temp;
            
            var comp2=document.getElementById('comp2');
            comp2.value=temp;

            //alert(rawHtml.startsWith('li_selected'));
            //alert(temp);
                //alert($(this));
            });
                    
        });
    });
                
    </script>
            
        
    <div class="page-header">
        <h1 class='text-center'> Add GMDN/UMLS data - Step 2 </h1>
    </div>
     
        <!-- <div class="row style="height:100%; display: block;">-->
    <h2><a href="http://localhost/ProjectModel/adduids.php">Click Here if your model does not have unique IDs set</a></h2>            
      
    <div class="row">                   
        <div class="col-sm-4" style="background-color:lavender;">
            <h2>Click Draw Tree and click on required element</h2>
            <div class="easy-tree" id="esyTreeOnLoad">

            </div>
            <button class="btn btn-success" id="draw"> Draw Tree </button>
        </div>

        <div class="col-sm-8" style="background-color:lavenderblush;">
            <div class="container">
                <div class="jumbotron">
                    <font color="#16A085">  <h2 class="text-center"> Search for GMDN or UMLS term </h2></font>
                    <div class="container">
                        <a href="#" data-toggle="tooltip" title="System/Assembly level elements are looked up in GMDN database and Components or lower in UMLS database"> 
                            <h3>Select the element category </h3>
                        </a>
                        <form method="GET" action="searchGMDN.php">
                            <label class="radio-inline">
                                <input type=radio name="update" value="yes" onclick="setReadOnly(this)">Device/Assembly level (Add GMDN data)<br />
                            </label>
                            <label class="radio-inline">
                                <input type=radio name="update" value="no" onclick="setReadOnly(this)">Component or lower level (Add UMLS data) <br />
                            </label>
                            <br><br><br>

                            <div id ="form1">  
                                <a href="#" data-toggle="tooltip" title="In Vitro Diagnostic medical devices (IVDs) are those which are used in the laboratory and which are not used for clinical interventions "> 
                                    <h4>Select IVD or Non-IVD </h4> 
                                </a>
                                <input type="hidden" name="comp" id="comp">

                                <label class="radio-inline">
                                    <input type="radio" name="optradio" value = "IVD">IVD
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optradio" value = "Non-IVD"> Non-IVD
                                </label> <br><br><br>

                                <label> <h4>Enter query to search GMDN database with: </h4> </label> <br>                                
                                <input type="text" name="query" />
                                <input type="submit" value="Search" />
                            </div>    
                        </form>

                        <br><br><br>
                        <form method="GET" action="searchUMLS.php">

                        <div id ="form2">                   
                            <input type="hidden" name="comp2" id="comp2">
                            <label> <h4>Enter query to search UMLS database with: </h4> </label> <br>                                
                            <input type="text" name="query" />
                            <input type="submit" value="Search" />
                        </div>    
                        </form>
                    </div>

                </div>
            </div>

            <div class="container" style="margin-top: 50px">
                <div class="jumbotron"> 
                    <font color="#16A085">  <h2 class="text-left"> Properties </h2></font>

                </div>
            </div>

        </div>

    </div>
    <h3><a href="http://localhost/ProjectModel/index.php">Click here to return to the main menu when done. Your file is saved as uploadedfilenameversion2 in the main ProjectModel folder</a></h3>;
  
</body>
</html>