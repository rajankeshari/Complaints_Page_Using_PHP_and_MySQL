<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
$query = "SELECT * FROM location_type";
$results = $db_handle->runQuery($query);
?>



<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!--j Query library-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!--java script-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register Complaints</title>
        
         <style>
            .container{
              position: absolute;
              top: 15vh;
              margin-left: 19%;
              
              align-items: center;
          }
          
          .btn-primary{
              justify-content: center; 
              text-align: center;             
          }
          .panel .panel-body .panel-heading {
              
              align-items: center;
          }
          
          @media (min-width:320px) {
              .container {
                  width: 320px;
              }
          }
          @media (min-width:481px) {
              .container {
                  width: 481px;
              }
          }
          @media (min-width:641px) {
              .container {
                  width: 641px;
              }
          }

          @media (min-width:961px) {
              .container {
                  width: 961px;
              }
          }
          @media (min-width:1000px) {
              .container {
                  width: auto;
              }
          }
          
          
          
          
    </style>
    </head>
    
    <script src="jquery.main.js" type="text/javascript"></script>

        <script type="text/javascript">

            function getLocation(val){
                $.ajax({
                    type: "POST",
                    url: "getLocation.php",
                    data: 'type_id='+val,
                    success: function(data){
                        $("#location-list").html(data);
                    }
                });
            }

        </script>
    
    
    <body>
        
        <div class="container container-fluid">
        <section class="row justify-content-center">
            <div class="row row_style">
                <div class="col-xs-6  col-sm-6 col-sm-offset-3">
                    
                    
                    
                    
                    <div class="panel panel-primary  col-12  ">
                        <div class="panel-heading">
                            <h4>On Line Complaint Form</h4>
                        </div>
                        <div class="panel-body">


                            <form>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputComplaintType">Type of Complaint</label>
                                        <select id="inputComplaintType" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                        $complaints = ["Civil", "Electrical", "Mess", "Sanitation", "Internet", "Computer", "UPS Related", "Telephone", "Student Contigency"];
                                        foreach($complaints as $complaint_type){
                                        ?>
                                        <option value="<?php echo $complaint_type; ?>"><?php echo $complaint_type; ?></option>
                                        <?php    
                                        }
                                        ?>
                                        </select>
                                    </div>
                                
                                    <div class="form-group col-md-6">
                                        <label for="inputLocation">Location Type</label>
                                        <select name="location_type" id="type-list" class="form-control" onChange="getLocation(this.value);">
                                           <option value="">Select</option>
                                            <?php
                                            foreach($results as $location_type){
                                            ?>
                                            <option value="<?php echo $location_type["id"]; ?>"><?php echo $location_type["type"]; ?></option>
                                            <?php    
                                            }
                                            ?>
                                    </select>
                                    </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="inputLocation">Location</label>
                                    <br>
                                    <select name="location" id="location-list" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>  
                                
                                
                                <div id="0b9be013b66cdd4d5ed5cd393b6d626c" class="form-group col-md-6">
                                    <label for="inputLocDetails">Location Details</label>
                                     <grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none;" class="cGcvT"></grammarly-extension>
                                     <grammarly-extension data-grammarly-shadow-root="true" style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none;" class="cGcvT"></grammarly-extension>
                              <textarea id="locationDetails" name="locationDetails" class="form-control form-control" rows="3" placeholder="Location Details" required="required" spellcheck="false"></textarea>

                                                <p class="help-block"></p>


                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputProblemDetails" for="7bad45f00991beed70020c5f9a31abbe">Problem Details</label>
                                    <textarea id="7bad45f00991beed70020c5f9a31abbe" class="form-control is-invalid" id="inputProblemDetails" placeholder="Problem Details" required></textarea>
                                    <!-- <div class="invalid-feedback">
                                        Please enter Problem Details in the textarea.
                                    </div> -->
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label" for="9c150eba0a7a2c54fff986fb32c0e2f5">Time of Availability</label>
                                        <input id="9c150eba0a7a2c54fff986fb32c0e2f5" name="time" class="form-control" type="text" placeholder="Time of Availability" required="required">
                                        <p class="help-block"></p>
                                </div>
                                <br>
                            
                                    
                                    
                             <center>           
                            <button id="complaint" value="Submit" class="btn btn-primary col-md-3" type="submit">Submit</button>
                             </center>
                            
                      
                            </div>


                            </div>   </form>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                </div>
            </div>
        </section>
        </div>
          
    </body>
</html>