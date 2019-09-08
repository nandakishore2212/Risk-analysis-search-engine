<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>jQuery Easy Tree Example</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/easyTree.css">
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="src/easyTree.js"></script>
        <style>
            body {
                background: #FFFFF7;

            }
            .jumbotron{
                background-color:#ECF0F1;

            }

        </style>
    </head>
    
<body>
<div class="row">                   
        

        <div class="container">
            <div class="jumbotron">
                <font color="#16A085">  <h2 class="text-center"> Add Unique IDs to system components </h2></font> <br><br>
                <div class="container">
                    
                    <form method="GET" action="populateuids.php">                     
                        <label> <h4>Enter name of device (maximum 6 characters) </h4> </label> <br>                                
                        <input type="text" name="device" /> <br>
                        <label> <h4>Enter version number (maximum 4 characters) </h4> </label> <br>                                
                        <input type="text" name="version" /><br><br>
                        <input type="submit" value="Generate UIDs" />                          
                    </form>

                   
                    
                </div>

            </div>
        </div>

        


</div>
    
</body>