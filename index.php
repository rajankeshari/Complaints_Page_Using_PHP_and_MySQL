<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
$query = "SELECT * FROM location_type";
$results = $db_handle->runQuery($query);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Complaints</title>
</head>

<style type="text/css">
    body{width: 800px;
        font-family: calibri;
        padding: 0;
        margin: 0 auto;
    }
    .frm{
        border: 1px solid #7ddaff;
        background-color: #b4c8d0;
        margin: 0px auto;
        padding: 40px;
        border-radius: 4px;
    }
    .InputBox{
        padding: 10px;
        border: #bdbdbd 1px solid;
        border-radius: 4px;
        background-color: #FFF;
        width: 50%;
    }
    .row{
        padding-bottom: 15px;
        padding-left: 150px;
    }
</style>
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
    <div class="frm">
        <h2>On Line Complaint Form</h2>

        <div class="row">
            <lable>Type of Complaint</label><br>
            <select name="complaint_type" id="complaint-type-list" class="InputBox">
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


        <div class="row">
            <lable>Location Type</label><br>
            <select name="location_type" id="type-list" class="InputBox" onChange="getLocation(this.value);">
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

        <div class="row">
            <lable>Location</label><br>
            <select name="location" id="location-list" class="InputBox">
                <option value="">Select</option>
            </select>
        </div>  

        <div id="0b9be013b66cdd4d5ed5cd393b6d626c" class="row">
            <div class="form-group col-md-6 col-lg-6">
                <grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none;" class="cGcvT"></grammarly-extension>
                <grammarly-extension data-grammarly-shadow-root="true" style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none;" class="cGcvT"></grammarly-extension>
                <label class="control-label" for="locationDetails">Location Details</label>
                <textarea id="locationDetails" name="locationDetails" class="form-control form-control" rows="3" placeholder="Location Details" required="required" spellcheck="false"></textarea>
                <p class="help-block"></p>
            </div>
            <div class="form-group col-md-6 col-lg-6">
                <label class="control-label" for="7bad45f00991beed70020c5f9a31abbe">Problem Details</label>
                <textarea id="7bad45f00991beed70020c5f9a31abbe" name="problemDetails" class="form-control form-control" rows="3" placeholder="Problem Details" required="required"></textarea>
                <p class="help-block"></p>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label" for="9c150eba0a7a2c54fff986fb32c0e2f5">Time of Availability</label>
            <input id="9c150eba0a7a2c54fff986fb32c0e2f5" name="time" class="form-control" type="text" placeholder="Time of Availability" required="required">
            <p class="help-block"></p>
        </div>

        <center>
            <button id="complaint" value="Submit" class=" btn btn-primary" type="submit">Submit</button>
        </center>

    </div>


</body>
</html>