<?php
	$ui = new UI();
//echo form_open('complaint/register_complaint/insert');   	
	$row = $ui->row()->open();
	
	$column1 = $ui->col()->width(2)->open();
	$column1->close();
	
	$column2 = $ui->col()->width(8)->open();
	$box = $ui->box()
			  ->solid()
			  ->title("Complaint ID: ".$complaint_id)
			  ->uiType('primary')
			  ->open();

	$inputRow1 = $ui->row()->open();
		$c1 = $ui->col()->width(4)->open();
			?><p><strong><? $ui->icon("user")->show() ?> Complaint By</strong><br/>
			  <span><?= $complaint_by ?></span></p><?
		$c1->close();
		$c2 = $ui->col()->width(4)->open();
			?><p><strong><? $ui->icon("mobile")->show() ?> Mobile No</strong><br/>
			  <span><?= $mobile ?></span></p><?
		$c2->close();
		$c3 = $ui->col()->width(4)->open();
			?><p><strong><? $ui->icon("mail-forward")->show() ?> Email ID</strong><br/>
			  <span><?= $email ?></span></p><?
		$c3->close();
	$inputRow1->close();
	

	$inputRow2 = $ui->row()->open();
		$c1 = $ui->col()->width(4)->open();
			?><p><strong><? $ui->icon("clock-o")->show() ?> Registered On</strong><br/>
			  <span><?= $date_n_time ?></span></p><?
		$c1->close();
		
		$c2 = $ui->col()->width(4)->open();
			?><p><strong> Category of Complaint </strong><br/>
			  <span><?= $category ?></span></p><?
		$c2->close();
		
		$c3 = $ui->col()->width(4)->open();
			?><p><strong> Problem Details </strong><br/>
			  <span><?= $problem_details ?></span></p><?
		$c3->close();
	$inputRow2->close();


	$inputRow3 = $ui->row()->open();
		
		
		$c3 = $ui->col()->width(4)->open();
//				$ui->textarea()->label('Action Taken')->name("action_taken")->value($remarks)->disabled()->show();
			?><p><strong> Status </strong><br/>
			  <span><?= $status ?></span></p><?
		$c3->close();
	$inputRow3->close();

//	$inputRow4 = $ui->row()->open();
//		$c1 = $ui->col()->width(4)->open();
//				$ui->textarea()->label('Action Taken')->name("action_taken")->value($remarks)->disabled()->show();
			?><p><strong> Remarks </strong><br/>
			  <span><?= $remarks ?></span></p><?
//		$c1->close();
//	$inputRow4->close();
?>
<center>
<?php

$footer = $ui->row()->open();

			$back = $ui->col()->width(3)->open();
                       $ui->button()
                        ->value('First Page')
                        ->submit(false)
                        ->id('back')
                        ->name('back')
                        ->uiType('primary')
                        ->show();
                $back->close();
				
		$c1 = $ui->col()->width(3)->open();
                        if($prev){
                       ?>
    <a href="<?php  echo site_url('complaint/complaint_details/mis_details/'.$supervisor.'/'.$prev->complaint_id.'/'.trim($prev->status.'/'.$prev->category)); ?>" class="btn btn-primary" ><< Previous</a>
                        <?php }
                $c1->close();

               
                
                $c2 = $ui->col()->width(3)->open();
                        if($next){
                       ?>
    <a href="<?php  echo site_url('complaint/complaint_details/mis_details/'.$supervisor.'/'.$next->complaint_id.'/'.trim($next->status.'/'.$next->category)); ?>" class="btn btn-primary" >Next >></a>
                        <?php }
                $c2->close();
$footer->close();

	$box->close();
	
	$column2->close();
	
	$row->close();
?>
</center>
<input type="hidden" name="comp" id="comp" value="<?php echo $complaint_id;?>">
<input type="hidden" name="htype" id="htype" value="<?php echo $supervisor;?>">
<script>
$(document).ready(function(){

$("#back").click(function(){
    //alert("The paragraph was clicked.");
    var a=$("#htype").val();
    //alert(a);
    window.location.href = "<?Php echo base_url(); ?>index.php/complaint/supervisor/view_mis_complaints/"+a;
  
    });

 });
 </script>