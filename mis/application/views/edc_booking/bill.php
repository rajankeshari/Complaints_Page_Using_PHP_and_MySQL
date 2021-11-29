  <style>
	@media print {
  		a[href]:after {
    		content: none !important;
    	}
  	}
	</style>
<div class="container">
	<br/>
	<!-- <table class="table">
		<tr>
			<td><img src="<?= site_url('../assets/images/mis-logo-big.png') ?>" height="85" width="100" /></td>
			<td align="center"><strong>INDIAN SCHOOL OF MINES</br>
							 DHANBAD - 826004</br>
						EXECUTIVE DEVELOPMENT CENTRE</strong></td>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>
	</table> -->
	<div class="row">
		 <div class="col-md-2"><img src="<?= site_url('../assets/images/mis-logo-big.png') ?>" height="85" width="100" /></div>
		 <div class="col-md-8" align="center"><b>INDIAN SCHOOL OF MINES</br>
							 DHANBAD - 826004</br>
						EXECUTIVE DEVELOPMENT CENTRE</b></div>
	</div>
	<table class="table">
		<tr>
			<td><strong>App. No.</strong> : <span id="app_num"><?= $app_num ?></span></td>
			<td align="right"><strong>Date</strong> : <?= date('d-m-Y') ?></td>
		</tr>
	</table>
	<br/>
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10"><p>Bill to <b><i>
			<?
				if($gender == 'm') echo 'Shri ';
				else echo 'Smt ';
			?>
			<span id="name"><?= $name ?></span>
        </i></b> for lodging charges from <strong><i><span id="check_in"><?= $check_in ?></span></i></strong> to <strong><i><?= $check_out ?></i></strong> for:</p>
		</div>
		<div class="col-md-1"></div>
	</div>
	<br/>
	<table class="table">
		<thead>
			<tr>
				<th> Sl. No.</th>
				<th> Room No </th>
				<th> Room Type </th>
				<th> Tariff (per Day)</th>
				<th> Subtotal </th>
			</tr>
		</thead>
		<tbody>
		<? $sno = 1;
		foreach($rooms as $room) {
			echo '
			  <tr>
				  <td>'.$sno++.'</td>
  				<td>'.ucfirst($room['building']).' - '.$room['room_no'].'</td>
  				<td>'.ucfirst($room['room_type']).'</td>
  				<td>'.$room['tariff'].'</td>
  				<td>Rs. '.$room['charge'].'</td>
			  </tr>
		  ';
    } ?>
			<tr>
				<td colspan="3"></td>
				<td><strong>Total Sum:</strong></td>
				<td><strong>Rs. <?= $total_sum ?></strong></td>
			</tr>
		</tbody>
	</table>

	<br/><br/><br/>
</div>
<br/>
<div class="row">
    <div class="col-md-5"></div>
        <div class="col-md-3">
            <button class="btn" id="btn_back">Back</button>
            <button class="btn" id="btn_print">Print</button>
            <a href="<?=site_url('edc_booking/guest_details/generate_receipt/'.$id)?>"><button class="btn" id="btn_receipt">Generate Receipt</button></a>
        </div>
</div>
<script>
	$(document).ready(function(){
		$('#btn_print').click(function(){
			$(this).hide();
			$('#btn_back').hide();
            $('#btn_receipt').hide();
			window.print();
			$(this).show();
			$('#btn_back').show();
            $('#btn_receipt').show();
		});

		$('#btn_back').click(function() {
			window.history.back();
		});
	});
</script>
