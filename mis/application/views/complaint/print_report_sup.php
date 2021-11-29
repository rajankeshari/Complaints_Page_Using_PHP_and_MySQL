<?php 

                        $fdate= date('Y-m-d',strtotime($this->input->post('from')));
                       
                        $tdate= date('Y-m-d',strtotime($this->input->post('to')));
                        
                        
                        $status=$this->input->post('selstatus');
                        if($status=="none")
                        {
                             $status='All';
                        }
                        $type=$this->input->post('seltype');
                        if($type=="none")
                        {
                             $type='All';
                        }
                        $loc=$this->input->post('selloc');
                        if($loc=="none")
                        {
                             $loc='All';
                        }

?>

<style>
    
body{ font-size: 11px; }
h2{ font:10px; font-weight:bold;}
table{ width:100%;}
.form-control{ width:50px;}

.anuj {
    border: 1px solid black;
    
    border-collapse: collapse;
}
.anuj td {
     border: 1px solid black;
}
.anuj tr {
     border: 1px solid black;
}

.anuj th {
     border: 1px solid black;
}


    
</style>
<table >
  <tr>
    <td align="center"><div style="font-size:18px; font-weight:bold;">IIT (ISM), Dhanbad - 826004</div></td>
  </tr>
 </table>

<table >
  <tr>
    <td width="32%">&nbsp;</td>
    <td width="39%" align="center" >Online Complaint System</td>
    <td width="29%" align="right">&nbsp;</td>
  </tr>
</table>

<table >
  
  <tr >
      <?php if($fdate!='1970-01-01' && $tdate!='1970-01-01' && $fdate!=$tdate){?>
    <td width="25%" >Date From-<?php echo date('d M Y', strtotime($fdate)); ?> To-<?php echo date('d M Y', strtotime($tdate)); ?></td>
      <?php } elseif($fdate!='1970-01-01' && $tdate!='1970-01-01' && $fdate==$tdate) { ?>
    <td width="25%" >Date-<?php echo date('d M Y', strtotime($fdate)); ?></td>
      <?php } else { ?>
      <td width="25%" >Date-<?php echo date('d M Y') ; ?></td>
      <?php }  ;?>
  </tr>
 
   </table>

<table >
  
  <tr >
    <td width="35%" >Complaint Type-</td>
    <td  width="50%" ><?php echo $type; ?></td>
    
 
    <td width="25%" >Status-</td>
    <td  width="50%" ><?php echo $status; ?></td>
 
    <td width="25%" >Location-</td>
    <td  width="50%" ><?php echo $loc; ?></td>
    
  </tr>
 

   </table>

 <table  class="anuj">
   <thead>
     <tr>
       <td>Sl.No</td>
	   <td> Complaint ID </td>
                <td>Registered By </td>
		<td>Registered On</td>
                 <td>Status</td>
                 <td>Type</td>
		<td>Location</td>
                <td>Remarks</td>
     </tr>
   </thead>
   <tbody>
   
       <?php $i=1; foreach($show_list as $b){ 
           
            $unm=$this->user_details_model->getUserById($b->user_id);
           
           ?>
		
	<tr>
								
			<td><?php echo $i; ?></td>
			<td> <?php echo $b->complaint_id ?> </td>
			<td><?php echo $unm->first_name." ".$unm->middle_name." ".$unm->last_name; ?></td>
                        <td><?php echo ($b->date_n_time); ?></td>
                        <td><?php echo ($b->status); ?></td>
                        <td><?php echo ($b->type); ?></td>
                        <td><?php echo ($b->location); ?></td>
                        <td><?php echo ($b->remarks); ?></td> </tr>
									 
								<?php  $i++; } ?>
								
   </tbody>
</table>


  
   
   
<table border="0">
  <tr> 
      <td align="center">This is System Generated Report, No Signature Required<br><?php ?> </td>
  </tr>
</table>  
    
   

 