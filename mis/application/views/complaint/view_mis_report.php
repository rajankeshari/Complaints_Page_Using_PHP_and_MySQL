<?php echo form_open('complaint/mis_report/view_result/'.$supervisor.'/',array("id"=>"logfile")); ?>
<?php

if(!empty($show_list))
{
	?>
<div class="row">
	<div class="col-md-12 ">
	<!-- Error Form-->
		<?php if(validation_errors()){ ?>
	<div class="alert alert-danger alert-dismissable">
                                <?php echo validation_errors(); ?>
    </div>
	<?php } ?>
	
        <div class="row">
            <div class="col-md-12">
		<div class="form-group">
                    <div class="row">
			<div class="col-sm-2">
			<label for="from">Date From</label>
                            <input type="text" id="from" name="from" class="form-control" data-date-format="dd M yyyy">
			</div>
			<div class="col-sm-2">
			<label for="to">Date to</label>
                            <input type="text" id="to" name="to" class="form-control"data-date-format="dd M yyyy">
			</div>
                        <div class="col-sm-2">
			<label for="to">Status</label>
                             <select name="selstatus" id="selstatus" class="form-control" >
                                    <option value="none" selected>Select Status</option>
                                     <option value="Closed">Closed</option>
                                      <option value="New">New</option>
                                     <option value="Rejected">Rejected</option>
                                     <option value="Under Processing">Under Processing</option>
                                     
                                    
                             </select> 
			</div>
                   <?php if($supervisor=="All") { ?>
                        <div class="col-sm-3">
			<label for="to">Category of Complaint</label>
                         <select  name="selcat" id="selcat" class="form-control" >
                                    <option value="none" selected>Select Category</option>
                                    <option value="Attendance">Attendance</option>
                                    <option value="Personal_Details">Personal Details</option>
                                    <option value="Login">Login</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Semester_Registration">Semester Registration</option>
                                    <option value="Grade_Sheet">Grade Sheet</option>
                                    <option value="Hall_Ticket">Hall Ticket</option>
                                    <option value="Others">Others</option>
                                    
                             </select> 
			</div> <?php } ?>
    <?php if($supervisor!="All") { ?>
          <div class="col-sm-3">
      <label for="to">Type</label>
                        <input type="text" id="selcat" name="selcat" class="form-control" readonly="readonly" value="<?php echo $show_list[0]->category; ?>">
      </div>
      <?php } ?>
                                               
			</div>
                   
                    </div>
		</div>
	    </div>
         <div class="row">
                        <div class="col-md-6">
                           <button type="submit" class="btn btn-primary" name="b_sub" id="b_sub">Show</button>
			
                        </div>
                        <div class="col-md-6"><span class="pull-right">
                           <!--<button type="submit" class="btn btn-primary" name="p_sub" id="p_sub">Print</button>-->
			</span>
                        </div>
                
           
        </div>
	<div class="box box-solid box-primary">
				
	<div class="table-responsive">					
		
	
	<h2 class="page-header"></h2>
		<table class="table table-bordered table-striped" id="ex_mod">
		<thead>
		<tr>
		
		<th>Complaint ID</th>
                <th>Registered By </th>
                <th>Mobile No </th>
		<th>Registered On</th>
                <th>Problem Details</th>
                 <th>Status</th>
                 <th>Category of Complaint</th>
                <th>Remarks</th>
               
		
		</tr>
		</thead>
                
               
                
		<tbody>
		<?php $i=0; foreach($show_list as $b){ 
		
                   $unm=$this->user_details_model->getUserById($b->user_id);
                   $mno=$this->complaints->get_mobile_no($b->user_id);
                  
                                      
		?>
		<tr>
						
			 <td><?php echo ($b->complaint_id); ?></td>
			<td><?php echo $unm->first_name." ".$unm->middle_name." ".$unm->last_name; ?></td>
                        <td><?php echo $mno->mobile_no;?></td>
                        <td><?php echo ($b->date_n_time); ?></td>
                         <td><?php echo ($b->problem_details); ?></td>
                         <td><?php echo ($b->status); ?></td>
                           <td><?php echo ($b->category); ?></td>
                        <td><a class="js-open-modal" href="#" data-modal-id="popup-<?php echo $i; ?>"> Remarks </a> </td> 
						
		</tr>
								
		<div id="popup-<?php echo $i; ?>" class="modal-box"> 
 <header>
    <a href="#" class="js-modal-close close">�</a>
    <h3> Remarks</h3>
  </header>
  <div class="modal-body">
    <p><?php echo ($b->remarks); ?></p>
  </div>
  <footer>
    <a href="#" class="js-modal-close">Close Button</a>
  </footer>
</div>
                
                
                
               
		<?php $i++; } ?>
		</tbody>
		
		</table>
	
	</div>
	</div>
	
</div>
</div>
<?php
}
else
{?>
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <strong>Error!</strong> No Record Found!.
</div>
<?php echo form_close(); ?>

<?Php
}

?>



<script>
$(function(){
	
	$('#ex_mod').dataTable(); 
	
$( "#from" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
	
});

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

 

  $('a[data-modal-id]').click(function(e) {

    e.preventDefault();

    $("body").append(appendthis);

    $(".modal-overlay").fadeTo(500, 0.7);

    //$(".js-modalbox").fadeIn(500);

    var modalBox = $(this).attr('data-modal-id');

    $('#'+modalBox).fadeIn($(this).data());

  }); 

   

   

$(".js-modal-close, .modal-overlay").click(function() {

  $(".modal-box, .modal-overlay").fadeOut(500, function() {

    $(".modal-overlay").remove();

  });

});

  

$(window).resize(function() {

  $(".modal-box").css({

    top: ($(window).height() - $(".modal-box").outerHeight()) / 2,

    left: ($(window).width() - $(".modal-box").outerWidth()) / 2

  });

});

  
$(window).resize();

</script>
<style>
    
    .modal-box {
  display: none;
  position: absolute;
  z-index: 1000;
  width: 98%;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}

.modal-box header,
.modal-box .modal-header {
  padding: 1.25em 1.5em;

  border-bottom: 1px solid #ddd;

}

.modal-box header h3,

.modal-box header h4,

.modal-box .modal-header h3,

.modal-box .modal-header h4 { margin: 0; }
 
.modal-box .modal-body { padding: 2em 1.5em; }

 

.modal-box footer,

.modal-box .modal-footer {

  padding: 1em;

  border-top: 1px solid #ddd;

  background: rgba(0, 0, 0, 0.02);

  text-align: right;

}

 

.modal-overlay {

  opacity: 0;

  filter: alpha(opacity=0);

  position: absolute;

  top: 0;

  left: 0;

  z-index: 900;

  width: 100%;

  height: 100%;

  background: rgba(0, 0, 0, 0.3) !important;

}

 

a.close {

  line-height: 1;

  font-size: 1.5em;

  position: absolute;

  top: 5%;

  right: 2%;

  text-decoration: none;

  color: #bbb;

}

 

a.close:hover {

  color: #222;

  -webkit-transition: color 1s ease;

  -moz-transition: color 1s ease;

  transition: color 1s ease;

}
@media (min-width: 32em) {

  .modal-box { width: 70%; }

}

</style>

