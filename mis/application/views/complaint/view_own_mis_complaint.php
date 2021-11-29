<?php 
	$ui = new UI();

	$box = $ui->box()->uiType('primary')->open();
	
	$table = $ui->table()->hover()->bordered()
				->sortable()->searchable()->paginated()
				->open();
?>
	<thead>
		<tr>
			<th>Complaint ID</th>
			<th>Status</th>
			<th>Registered On</th>
			<th>Category of Complaint</th>
<!--		<th>Location Details</th>
			<th>Problem Details</th>
			<th>Remarks</th> -->
		</tr>
	</thead>
	<?php
			$sno=1;
			while ($sno <= $total_rows)
			{
	?>
				<tr>
					<!-- <td><a href="<?php echo site_url("complaint/complaint_details/mis_details_own/".$data_array[$sno][1]."/".$data_array[$sno][2]."/".$data_array[$sno][3]);?>"><?php echo $data_array[$sno][1];?></a></td>
                                   
					<td><?php echo $data_array[$sno][2];?></td>
					<td><?php echo $data_array[$sno][4];?></td>
					<td><?php echo $data_array[$sno][3];?></td>
					<td><?php echo $data_array[$sno][6];?></td> -->
<!--					<td><?php //echo $data_array[$sno][6];?></td>
					<td><?php echo $data_array[$sno][7];?></td>
					<td><?php echo $data_array[$sno][8];?></td> -->
					
					<?php if(strcmp($data_array[$sno][2],"Closed")==0) { ?>
					 <td bgcolor="#34B208"><a style="color:white" href="<?php echo site_url("complaint/complaint_details/mis_details_own/".$data_array[$sno][1]."/".$data_array[$sno][2]."/".$data_array[$sno][3]);?>"><?php echo $data_array[$sno][1];?></a></td>
                                   
					<td bgcolor="#34B208"><?php echo $data_array[$sno][2];?></td>
					<td bgcolor="#34B208"><?php echo $data_array[$sno][4];?></td>
					<td bgcolor="#34B208"><?php echo $data_array[$sno][3];?></td>
					<?php } ?>
					
					<?php if(strcmp($data_array[$sno][2],"New")==0) { ?>
					 <td bgcolor="#F74B3A"><a style="color:white" href="<?php echo site_url("complaint/complaint_details/mis_details_own/".$data_array[$sno][1]."/".$data_array[$sno][2]."/".$data_array[$sno][3]);?>"><?php echo $data_array[$sno][1];?></a></td>
                                   
					<td bgcolor="#F74B3A"><?php echo $data_array[$sno][2];?></td>
					<td bgcolor="#F74B3A"><?php echo $data_array[$sno][4];?></td>
					<td bgcolor="#F74B3A"><?php echo $data_array[$sno][3];?></td>
					<?php } ?>
					
					<?php if(strcmp($data_array[$sno][2],"Rejected")==0) { ?>
					 <td bgcolor="#ABA6AB"><a style="color:red" href="<?php echo site_url("complaint/complaint_details/mis_details_own/".$data_array[$sno][1]."/".$data_array[$sno][2]."/".$data_array[$sno][3]);?>"><?php echo $data_array[$sno][1];?></a></td>
                                   
					<td bgcolor="#ABA6AB"><?php echo $data_array[$sno][2];?></td>
					<td bgcolor="#ABA6AB"><?php echo $data_array[$sno][4];?></td>
					<td bgcolor="#ABA6AB"><?php echo $data_array[$sno][3];?></td>
					<?php } ?>
					
					<?php if(strcmp($data_array[$sno][2],"Under Processing")==0) { ?>
					 <td bgcolor="#EABB1A"><a style="color:green" href="<?php echo site_url("complaint/complaint_details/mis_details_own/".$data_array[$sno][1]."/".$data_array[$sno][2]."/".$data_array[$sno][3]);?>"><?php echo $data_array[$sno][1];?></a></td>
                                   
					<td bgcolor="#EABB1A"><?php echo $data_array[$sno][2];?></td>
					<td bgcolor="#EABB1A"><?php echo $data_array[$sno][4];?></td>
					<td bgcolor="#EABB1A"><?php echo $data_array[$sno][3];?></td>
					<?php } ?>
					
				</tr>
<?php
				$sno++;
			}
?>
</table>
<?php
	$table->close();

	$box->close();
?>