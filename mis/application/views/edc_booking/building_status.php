<style>
    .legend {
        display: inline-block;
        padding: 5px 10px;
        color: #909090;
    }

    .bg-occupied {
        background-color: #FFFF99;
    }

    .bg-warning{
      background-color:#C0C0C0;
    }
</style>
<?php
$flag=1;
$ui = new UI();

$row = $ui->row()->open();

$col1 = $ui->col()
->width(1)
->open();
$col1->close();

$col2 = $ui->col()
->width(10)
->open();


    $tabBox = $ui->tabBox()
            ->uiType('primary')
            //->title('Building Status')
		    ->title("Building Status")
	->open();
	
 echo '<span class="legend bg-success">Available</span>
    <span class="legend bg-danger">Booked</span>
    <span class="legend bg-occupied">Checked In</span>
    <span class="legend bg-warning">Blocked</span>';

$box = $ui->box()
->uiType('primary')
->title('Check Availability')
->solid()
->open();

$ui->select()
->name('building')
->id('building')
->label('Select Building')
->addonLeft($ui->icon("bars"))
->required()
->options(array(
	$ui->option()->value('select')->text('Select')->selected(),
	$ui->option()->value('old')->text('Old'),
	$ui->option()->value('extension')->text('Extension')))
->required()
->show();
echo '<span id="auth" style="display:none;">'.$auth.'</span>';
?>

<div id = "result_content"></div>
<script>
$(document).ready(function(){
	var current_build = $('#building :selected').text();
	//If the page is loaded first time show initial value as Select .
	if(current_build!='Selected' && $('#result_content').is(':empty'))
	{	
		
		$('[name="building"]').val('select');
	}

$('select[name="building"]').change(function(){
		
	var current_build = $('#building :selected').text();
	//Check the current building selected and accordingly get the content by an ajax call .
	if(current_build=='Extension'){
			$.ajax({
	method: 'POST',
        url: site_url('edc_booking/management/load_building_status/extension/'+$('#auth').text()),
        error : function(e) {
            alert(e.responseText);
        },
	data : {flag : 1} ,
	
        success: function(result) {
            $('#result_content').html(result);
		
        }});}
	
	else if(current_build=='Old')
	{
			$.ajax({
		method: 'POST',
		url: site_url('edc_booking/management/load_building_status/old/'+$('#auth').text()),
		error : function(e) {
		    alert(e.responseText);
		},
		data : {flag : 1} ,
	
		success: function(result) {
		    $('#result_content').html(result);
		
		}});
	}
	else//If select is chosen make the result_content empty .
	{
		$('#result_content').empty()	;	
	}
    
	});
});
</script>
