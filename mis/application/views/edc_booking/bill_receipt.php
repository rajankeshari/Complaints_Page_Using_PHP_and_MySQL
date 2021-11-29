<!DOCTYPE html>
<html lang="en">
<head>
  <title>Checkout Receipt</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <style>
  @media print {
      a[href]:after {
        content: none !important;
      }
    }
  </style>
</head>
<body>
  <?
    $day_time = 86400;
  ?>
<div class="container">
  <br/>
  <table class="table">
    <tr>
      <td><img src="<?= site_url('../assets/images/mis-logo-big.png') ?>" height="85" width="100" /></td>
      <td align="center"><strong>INDIAN SCHOOL OF MINES</br>
               DHANBAD - 826004</br>
            EXECUTIVE DEVELOPMENT CENTRE</strong></td>
      <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
    </tr>
  </table>
  <!-- <div class="row">
     <div class="col-md-2"><img src="<?= site_url('../assets/images/mis-logo-small.png') ?>" height="85" width="100" /></div>
     <div class="col-md-8" align="center"><b>INDIAN SCHOOL OF MINES</br>
               DHANBAD - 826004</br>
            EXECUTIVE DEVELOPMENT CENTRE</b></div>
  </div> -->
  <table class="table">
    <tr>
      <td><strong>App. No.</strong> : <?= $app_num ?></td>
      <td align="right"><strong>Date</strong> : <?= date('d-m-Y') ?></td>
    </tr>
  </table>
  <br/>
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10"><p>Received with thanks from  <b><i>
      <?
        if($gender == 'm') echo 'Shri ';
        else echo 'Smt ';
      ?>
      <?= $name ?>
      </i></b> as lodging charges from <strong><i><?= $check_in ?></i></strong> to <strong><i><?= $check_out ?></i></strong> for:</p>
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
    $total_sum = 0;
    foreach($rooms as $room)
    {
            $sum = ceil((strtotime($check_out) - strtotime($check_in)) / $day_time) * $room['tariff'];
            $total_sum += $sum;
      ?>
      <tr>
        <td><?= $sno++ ?></td>
        <td><?= ucfirst($room['building']).' - '.ucfirst($room['floor']).' - '.$room['room_no']?></td>
        <td><?= ucfirst($room['room_type'])?> </td>
        <td><?= $room['tariff']?></td>
        <td><?= 'Rs. '.$sum ?> </td>
      </tr>
    <?  } ?>
      <tr>
        <td colspan="3"></td>
        <td><strong>Total Sum:</strong></td>
        <td><strong>Rs. <?= $total_sum ?></strong></td>
      </tr>
    </tbody>
  </table>

  <br/><br/><br/>
  <p align="right"><strong>Signature</strong>: ____________________</p>
</div>
<br/>
<div class="row">
  <div class="col-md-5"></div>
  <div class="col-md-1"><button class="btn" id="btn_back">Back</button></div>
  <div class="col-md-1"><button class="btn" id="btn_print">Print</button></div>
  <div class="col-md-5"></div>
</div>
<script>
  $(document).ready(function(){
    $('#btn_print').click(function(){
      $(this).hide();
      $('#btn_back').hide();
      window.print();
      $(this).show();
      $('#btn_back').show();
    });

    $('#btn_back').click(function(){
      window.history.back();
    });
  });
</script>
</body>
</html>