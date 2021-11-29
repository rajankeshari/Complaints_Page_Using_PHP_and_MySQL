<?php
$ui = new UI();
$row0 = $ui->row()->open();
$ui->col()->width(1)->open()->close();
$col0 = $ui->col()->width(10)->open();

$box = $ui->box()->title('New Tariff')->solid()->uiType('primary')->open();
  $form = $ui->form()
    ->multipart()
    ->action('edc_booking/management/add_tariff')
    ->open();

  echo '<div class="row">';
    echo '<div class="form-group col-md-6 col-lg-6">
      <label for="suite_official">Suite Official</label>
      <input class="form-control" required type="number" min="0" name="suite_official" />
    </div>';
    echo '<div class="form-group col-md-6 col-lg-6">
      <label for="suite_personal">Suite Personal</label>
      <input class="form-control" required type="number" min="0" name="suite_personal" />
    </div>';
    echo '<div class="form-group col-md-6 col-lg-6">
      <label for="double_official">Double Official</label>
      <input class="form-control" required type="number" min="0" name="double_official" />
    </div>';
    echo '<div class="form-group col-md-6 col-lg-6">
      <label for="double_personal">Double Personal</label>
      <input class="form-control" required type="number" min="0" name="double_personal" />
    </div>';
    $ui->datePicker()
      ->width(6)
      ->label ('W.E.F Date')
      ->name('wef_date')
      ->placeholder("yyyy-mm-dd")
      ->addonLeft($ui->icon("calendar"))
      ->dateFormat('yyyy-mm-dd')
      ->required()
      ->show();

    $ui->timePicker()
      ->width(6)
      ->label("W.E.F Time")
      ->value('12:00 AM')
      ->name('wef_time')
      ->addonLeft($ui->icon("clock-o"))
      ->required()
      ->show();
  echo '</div>';

  echo '<div class="row" align="right">';
    $col2 = $ui->col()->width(12)->open();
    $ui->button()
      ->value('Submit')
      ->uiType('primary')
      ->submit()
      ->show();
    $col2->close();
  echo '</div>';

  $form->close();
$box->close();

$box = $ui->box()->title('Tariff History')->solid()->uiType('primary')->open();
  if($tariff) {
    echo '<table class="table table-striped">';
    echo '<tr>
      <th>Sl.No.</th>
      <th>Suite Official</th>
      <th>Suite Personal</th>
      <th>Double Official</th>
      <th>Double Personal</th>
      <th>W.E.F</th>
    </tr>';
    for($i = 0; $i < count($tariff); $i++) {
      echo  '<tr>
        <td>'.($i + 1).'</td>
        <td>'.$tariff[$i]['suite_official'].'</td>
        <td>'.$tariff[$i]['suite_personal'].'</td>
        <td>'.$tariff[$i]['double_official'].'</td>
        <td>'.$tariff[$i]['double_personal'].'</td>
        <td>'.$tariff[$i]['wef'].'</td>
      </tr>';
    }
    echo '</table>';
  }

$box->close();
$col0->close();
$row0->close();
?>
