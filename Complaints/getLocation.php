<?php

require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["type_id"])){
    $query = "SELECT * FROM location_name WHERE typeID = '" . $_POST["type_id"] . "' order by name asc ";
    $results = $db_handle->runQuery($query);
    ?>
<option value disabled selected>Select Location</option>
<?php
    foreach($results as $location_name){
        ?>
<option value="<?php echo $location_name["id"]; ?>"><?php echo $location_name["name"]; ?></option>

<?php
    }
}
?>