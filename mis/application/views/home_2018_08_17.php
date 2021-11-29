<? $ui = new UI();

$row = $ui->row()->open();
$eventsCol = $ui->col()->width(8)->open();
$unreadNoticeBadge = ($unreadNotice > 0)? '<small class="badge bg-red">'.$unreadNotice.'</small>': '';
$unreadCircularBadge = ($unreadCircular > 0)? '<small class="badge bg-red">'.$unreadCircular.'</small>': '';
$unreadMinuteBadge = ($unreadMinute > 0)? '<small class="badge bg-red">'.$unreadMinute.'</small>': '';
$eventsTabBox = $ui->tabBox()
->tab("notices", $ui->icon("info-circle") ." Notices " . $unreadNoticeBadge, true)
->tab("circulars", $ui->icon("file-text-o") . " Circulars"." ".$unreadCircularBadge)
->tab("minutes", $ui->icon("users") . " Meetings "." ".$unreadMinuteBadge)
->open();

$noticesTab = $ui->tabPane()
->id("notices")
->active()
->open();
?>
<div id="notices">
</div>

<!-- <div>
<?php foreach ($notices as $key => $notice) { ?>

        <div class="notice">
            <div class="sender-info">
                <div class="dp">
                    <img src="<?= base_url() . "assets/images/" . $notice->photopath; ?>" />
                </div>
                         <div class="dp">
                <? $im=$notice->photopath; ?>
                    <img src="<?= base_url() . "assets/images/" . strtolower($im); ?>" />
                </div>
    <!-- The above line was modified as it was unable to display image in some cases where the folder and path does not match so all have been converted to lowercase  as shown above  -->
    <div class="sender">
        <p class="sender-designation"><?= ucwords($notice->auth_name) ?>, <?= $notice->department ?></p>
        <p class="sender-name"><?= $notice->salutation ?> <?= $notice->first_name ?> <?= $notice->middle_name ?> <?= $notice->last_name ?></p>
        <p class="notice-date"><?= date_format(new DateTime($notice->posted_on), "d M Y h:m:s") ?></p>
    </div>
    </div>

    <div class="notice-content">
        <div class="content">
            <?= $notice->notice_sub ?>
        </div>

        <div class="attachments">
            <a href="<?= base_url() . "assets/files/information/notice/" . $notice->notice_path ?>">Download attachment</a>
        </div>
    </div>

    </div>


<?php } ?>
</div> 
<!-- END timeline item -->
<!-- timeline item -->
<!-- <li>
    <i class="fa fa-user bg-aqua"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>
        <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
    </div>
</li> -->
<!-- END timeline item -->
<!-- timeline item -->
<!-- <li>
    <i class="fa fa-comments bg-yellow"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>
        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
        <div class="timeline-body">
            Take me to your leader!
            Switzerland is small and neutral!
            We are more like Germany, ambitious and misunderstood!
        </div>
        <div class='timeline-footer'>
            <a class="btn btn-warning btn-flat btn-xs">View comment</a>
        </div>
    </div>
</li> -->
<!-- END timeline item -->
<!-- timeline time label -->
<!-- <li class="time-label">
    <span class="bg-green">
        3 Jan. 2014
    </span>
</li> -->
<!-- /.timeline-label -->
<!-- timeline item -->
<!-- <li>
    <i class="fa fa-camera bg-purple"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>
        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>
        <div class="timeline-body">
            <img src="http://placehold.it/150x100" alt="..." class='margin' />
            <img src="http://placehold.it/150x100" alt="..." class='margin'/>
            <img src="http://placehold.it/150x100" alt="..." class='margin'/>
            <img src="http://placehold.it/150x100" alt="..." class='margin'/>
        </div>
    </div>
</li>
--><!-- END timeline item -->
<!-- timeline item -->
<!-- <li>
    <i class="fa fa-video-camera bg-maroon"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> 5 days ago</span>
        <h3 class="timeline-header"><a href="#">Mr. Doe</a> shared a video</h3>
        <div class="timeline-body">
            <iframe width="300" height="169" src="//www.youtube.com/embed/abcismxyz" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="timeline-footer">
            <a href="#" class="btn btn-xs bg-maroon">See comments</a>
        </div>
    </div>
</li> -->
<!-- END timeline item -->
<!-- <li>
    <i class="fa fa-clock-o"></i>
</li>
</ul> -->
<?
$noticesTab->close();

$circularsTab = $ui->tabPane()
->id("circulars")
->open();
?>
<div id="circulars">
</div>
<!--  <ul class="timeline">
      <li class="time-label">
          <span class="bg-green">
              3 Jan. 2014
          </span>
      </li> -->
<!-- /.timeline-label -->
<!-- timeline item -->
<!-- <li>
    <i class="fa fa-camera bg-purple"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>
        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>
        <div class="timeline-body">
            <img src="http://placehold.it/150x100" alt="..." class='margin' />
            <img src="http://placehold.it/150x100" alt="..." class='margin'/>
            <img src="http://placehold.it/150x100" alt="..." class='margin'/>
            <img src="http://placehold.it/150x100" alt="..." class='margin'/>
        </div>
    </div>
</li> -->
<!-- END timeline item -->
<!-- timeline item -->
<!-- <li>
    <i class="fa fa-video-camera bg-maroon"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> 5 days ago</span>
        <h3 class="timeline-header"><a href="#">Mr. X</a> shared a video</h3>
        <div class="timeline-body">
            <iframe width="300" height="169" src="//www.youtube.com/embed/ABCXYZISM" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="timeline-footer">
            <a href="#" class="btn btn-xs bg-maroon">See comments</a>
        </div>
    </div>
</li> -->
<!-- END timeline item -->
<!-- <li>
    <i class="fa fa-clock-o"></i>
</li>
</ul> -->
<?
$circularsTab->close();

$minutesTab = $ui->tabPane()
->id("minutes")
->open();
?>
<div id="minutes">
</div>
<?
$minutesTab->close();
$eventsTabBox->close();
?>





<?
$eventsCol->close();

$calendarCol = $ui->col()->width(4)->open();
$calendar = $ui->box()
->solid()
->containerClasses("bg-blue-gradient")
->title("Calendar")
->icon($ui->icon("calendar"))
->open();
?><div id="calendar"></div><?
$calendar->close();
$calendarCol->close();
?>

<!-- Authored By -> Kunwar Sachin Singh   -->   

<?php
$ui = new UI();
//echo "<br/><br/><br/>";

$col1 = $ui->col()->width(3)->open();
$col1->close();
$col1 = $ui->col()->width(4)->open();
?>

<?php
$test_row = $ui->row()->classes('modal fade')->id('birthday_dialog_fade')->open();
$test_col = $ui->col()->classes('modal-dialog')->id('birthday_dialog_dialog')->open();
$test_box = $ui->box()->classes('modal-content')->id('birthday_dialog_content')->open();
?>
<!-- <div class="modal-header">
</div> -->
<div class="modal-body">
    <div class="box box-solid" >
        <div id="dropdown">  </div>
        <?php
        $row1 = $ui->row()->open();
        $row1->close();
        ?>
        <div id="pic"></div>

    </div>
</div>
<!--  <div class="modal-footer">
 </div> -->
<?php
$test_box->close();
$test_col->close();
$test_row->close();
?>

<?php $col1->close(); ?>

<?php
$col1 = $ui->col()->width(1)->open();
$col1->close();

$col1 = $ui->col()->width(4)->open();
// echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
?>
<!--<div class="box box-solid" id="border">
  <div class="user-header bg-light-blue">
    <div class="box-body" id="heading">
   <i class="fa fa-birthday-cake"></i>
        <b id="head_b"> Today's  Birthdays</b>
        <small class="badge pull-right role">   <?php // echo $overall_count ;   ?>   </small>
     </div>
   </div>-->
<!-- <div class="box-footer" >

<?php // $col1=$ui->col()->width(5)->open();  ?>

   <button class="btn btn-default btn-flat" id="emp_select" value=<?php // echo $employee_count;  ?> >  
<i class="glyphicon glyphicon-user"></i>
       <small class="badge pull-right role" id="alter_colour2"> <b> <?php // echo "Employees - ". $employee_count ;   ?> </b>  </small>
   </button>
   
   <button class="btn btn-default btn-flat" id="stu_select" value=<?php // echo $student_count;   ?> >  
        <i class="glyphicon glyphicon-user"></i>
       <small class="badge pull-right role" id="alter_colour1"> <b> <?php // echo "Students - ". $student_count ;   ?> </b>  </small>
       
  </button>
</div>
</div>-->
<!--@anuj start-->
<input type="hidden" name="sauth" id="sauth" value="<?php echo $sauth = (in_array('stu', $this->CI->session->userdata('auth')) == 'stu') ? '1' : '0'; ?>">
<input type="hidden" name="sadmn_no" id="sadmn_no" value="<?php echo $this->session->userdata('id'); ?>">
<input type ="hidden" name="scategory" id="scategory" value="<?php echo $student_category; ?>">
<input type ="hidden" name="obcstatus" id="obcstatus" value="<?php echo $obc_tbl_status->sub_cast; ?>">
<input type ="hidden" name="obcidist" id="obcidist" value="<?php echo $obc_tbl_status->iss_dist; ?>">
<input type ="hidden" name="obcistate" id="obcistate" value="<?php echo $obc_tbl_status->iss_state; ?>">
<input type ="hidden" name="obciauth" id="obciauth" value="<?php echo $obc_tbl_status->iss_auth; ?>">
<!--@anuj close -->
<?php //$col2->close();  ?>
<!-- being closed due to obc model -->

<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><span class="label label-danger">Attention Student</span></h4>
            </div>
            <div class="modal-body">
                <p>Please ensure that the data against the following essential fields are CORRECT </p>
                <!--<p>(If You find any kind of mistake, Correct it using edit student details (if available).</p>
                <p>otherwise submit an application to the office of head computer science department with proof .)</p>
				<p>Submit an application to computer center with proof.</p>-->
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Name(In English)</label>
                            </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                               
                                <div id="sname"></div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <div id="ename"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Name(In Hindi)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                <div id="hsname"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <div id="hename"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                <div id="sdob"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Category</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                <div id="scat"></div>
                            </div>
                        </div>
                    </div>
                                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                <div id="sdept"></div>
                            </div>
                        </div>
                    </div>
                                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Course</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                <div id="scourse"></div>
                            </div>
                        </div>
                    </div>
                                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                <div id="sbranch"></div>
                            </div>
                        </div>
                    </div>
                                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syear">Permanent Address</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                
                                <div id="sadd"></div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <p>In case of any incorrect information, you are advised to submit an application (with proof of correct data) to <strong>Computer Centre by 15 August 2018 positively </strong>.   </p>
                 <!--       <p style="color:red;">Your Photograph should be of passport size, It will be shared for placement purpose</p> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script charset="utf-8">
    $(document).ready(function () {
        $('#stu_select').on('click', function () {
            //alert(this.value);
            if (this.value != 0) {
                $.ajax
                        ({
                            //url: site_url("birthday/birthday_controller/show_names_students"),
                            success: function (result)
                            {
                                $('#dropdown').html(result);
                            }

                        });
            }
        });

    });


    $(document).ready(function () {
        $('#emp_select').on('click', function () {
            //alert(this.value);
            if (this.value != 0) {
                $.ajax
                        ({
                            //url: site_url("birthday/birthday_controller/show_names_employees"),
                            success: function (result)
                            {
                                $('#dropdown').html(result);
                            }

                        });
            }

        });
    });

    $(document).ready(function () {
        $('#stu_select').on('click', function () {
            if (this.value != 0) {
                $('#birthday_dialog_fade').modal('show');
                return false;
            }
        });
    });
    $(document).ready(function () {
        $('#emp_select').on('click', function () {
            if (this.value != 0)
            {


                $('#birthday_dialog_fade').modal('show');
                return false;
            }
        });

        //code added for student, after login student will see message regarding name, hindi name, address etc.--starts
        var id = $('#sauth').val();
        var adm = $('#sadmn_no').val();
        var scat = $('#scategory').val();
        var obcst=$('#obcstatus').val();
        var scast = $('#scast').val();
     //alert(scast);   alert(scast.length);
      //alert(id);alert(adm);alert(scat);alert(obcst);
        //==================================OBC SUB CATEGORY========================================
        

        if (id == '1') {
            $.ajax({
                url: "<?Php echo base_url(); ?>index.php/home/stu_information",
                type: "POST",
                data: {"admn_no": adm},
                success: function (data)
                {
                    var json = $.parseJSON(data);
                    $("#sname").html(json.stu_details.stu_name);
                    $("#ename").html('(It is essential for Grade Sheet. It should be as per your 10th and 12th Certificate)');
                    $("#ename").css('color', 'red');
                    $("#hsname").html(json.stu_details.hindi_name);
                   if(json.stu_details.hindi_name=='Not Available'){
                         $('#hsname').css('color', 'red');
                    }
                    $("#hename").html('(It is essential for getting Migration Certificate, after course completion)');
                    $("#hename").css('color', 'red');
                    $("#sdob").html(json.stu_details.dob);
                    $("#scat").html(json.stu_details.category);
                    $("#sdept").html(json.stu_details.dname);
                    $("#scourse").html(json.stu_details.cname);
                    $("#sbranch").html(json.stu_details.bname);
                    $("#sadd").html(json.stu_details.line1+' '+json.stu_details.line2 );
                    
                }
            });

            $("#myModal").modal('show');
        }
        //code added for student, after login student will see message regarding name, hindi name, address etc.--ends


    });
</script>   


<!-- Authored By -> Kunwar Sachin Singh   -->   

<!-- Authored By -> Kunwar Sachin Singh   Security question part from this line -->   


<div id="security_popup">
    <?php
    $ui = new UI();
    $test_row = $ui->row()->classes('modal fade')->id('reset_dialog_fade')->open();
    $test_col = $ui->col()->classes('modal-dialog')->id('reset_dialog_dialog')->open();
    $test_box = $ui->box()->classes('modal-content')->id('reset_dialog_content')->open();
    ?>
    <div class="modal-header" id="header_style">
        <i class="fa fa-hand-o-right"></i>
        <b> Warning</b>
    </div>
    <div class="modal-body"> <b> You have not set your security questions yet</b>
        <button id="link"> click here </button> <b> to set it now </b>
    </div>
    <!--     <div class="modal-footer">
            
        </div> -->

    <?php
    $test_box->close();
    $test_col->close();
    $test_row->close();
    ?>
</div>

<script>
    $(document).ready(function () {
	
		
		
          var id = $('#sauth').val();
              var scat = $('#scategory').val();
        var asd = '<?php echo $display; ?>';
        if (asd == '0')
            // $('#reset_dialog_fade').modal('show');
            $('#reset_dialog_fade').modal({keyboard: false, backdrop: 'static'}); // This line was added to make security question compulsory         
        
         if (id == '1' && (scat.match(/obc.*/) || scat.match(/OBC.*/))) {
                 if($('#ebutton').is(":visible")){
	var stu_cast1 = $('#scast').val();    
    var idist1=$('#idist').val();
    var istate1=$('#istate').val();
    var iauth1=$('#iauth').val();
    if(stu_cast1.length>0 && istate1.length>0 && idist1.length>0 && iauth1.length>0){  
					 $('#close').show(); 
	}else{$('#close').hide(); }
					 
					 
                } else{
                     $('#close').hide(); 
                }
            }
    });


    $(document).ready(function () {
        //$('#reset_dialog_fade').modal('hide');
        //aa="2013JE0780";
        $('#link').on('click', function () {
            //alert("slg");
            $.ajax({
                url: site_url("home"),
                type: 'POST',
                //data:{id:$('#adm_no').val()},
                success: function (result)
                {
                    window.location = "<?php echo base_url() ?>index.php/sec_inside/sec_inside_controller/open_form";

                }
            });

        });
    });
</script>
<style>

    #link{
        background: none;
        border: none;
        color: #337ab7;
        font-family: sans-serif;
        font-size: 12px;
    }
    #link:hover{
        color: #3c8dbc;
        text-decoration: underline;
    }

</style>

<!-- Authored By -> Kunwar Sachin Singh   Security question part upto this line -->  


<?php $row->close(); ?>	

<script type="text/javascript">

    $(document).ready(function () {
        $("#calendar").datepicker("setDate", moment("<?= date('d-m-Y', time() + 19800); ?>", "DD-MM-YYYY").toDate());
        $("#calendar").datepicker().on('changeDate', function (e) {
            getNotices(e.format('yyyy-mm-dd'));
            getCirculars(e.format('yyyy-mm-dd'));
            getMinute(e.format('yyyy-mm-dd'));
        });

        getNotices('<?= date("Y-m-d", time() + 19800); ?>');
        getCirculars('<?= date("Y-m-d", time() + 19800); ?>');
        getMinute('<?= date("Y-m-d", time() + 19800); ?>');
    });

    /*function getNotices(date) {
     $.ajax({
     url: site_url("home/getNotices" + "/" + date),
     success: function(result) {
     $("#notices").html(result);
     }
     });
     }*/
    // The function given below was added when Attachement was removed as mandatory part from notices
    function getNotices(date) {
        $.ajax({
            url: site_url("home/getNotices/" + "/" + date),
            success: function (result) {
                $("#notices").html(result);
            }
        });
    }

    function getCirculars(date) {
        $.ajax({
            url: site_url("home/getCirculars" + "/" + date),
            success: function (result) {
                $("#circulars").html(result);
            }
        });
    }
    function getMinute(date) {
        $.ajax({
            url: site_url("home/getMinute" + "/" + date),
            success: function (result) {
                $("#minutes").html(result);
            }
        });
    }
</script>
<script>
        
        
function submitContactForm(){
    
    var stu_cast = $('#scast').val();
    var adm = $('#sadmn_no').val();
    var idist=$('#idist').val();
    var istate=$('#istate').val();
    var iauth=$('#iauth').val();
    if(stu_cast.length>0 && istate.length>0 && idist.length >0 && iauth.length>0){  
    
         
    $.ajax({
         url: "<?Php echo base_url(); ?>index.php/home/insert_stu_subcast",
         type: "POST",
         data: {"admn_no": adm,"stu_cast": stu_cast,"iss_dist": idist,"iss_state": istate,"iss_auth": iauth},
         beforeSend: function () {
           $('.submitBtn').attr("disabled","disabled");
           $('.modal-body').css('opacity', '.5');
          },
         success: function (data)
         {
             //alert(data);
             if($.trim(data) == '1'){
                       $('#statusMsg').html('<span style="color:green;">Thanks for filling form. Click close button to close.</p>');
                       //$("#myModalobc").modal('hide');
                        $('.submitBtn').attr("disabled","disabled");
                        $('.modal-body').css('opacity', '.5');                        
                         str=$('#statusMsg').html();
                         n = str.search("Thanks");
                        //console.log(n);
                        if(n>0){
                         $('#close').show() ;
                         }
                       else{
                         $('#myModalobc').modal({backdrop: 'static', keyboard: false})  
                         } 
                    
                }else{
                    $('#statusMsg').html('<span style="color:red;">Some problem occurred, please try again.</span>');
                    $('.submitBtn').removeAttr("disabled");
                    $('.modal-body').css('opacity', '');
                }
                
              
                
                
         }
         });
    
    }
    else{
       alert('Please Fill  required  columns');
      
         //console.log('form not valid');   
      
        return false;
    }
}
function editContactForm(){
            $("#sbutton").show();
            $("#ebutton").hide();
            $("#scast").removeAttr("disabled");
            $("#idist").removeAttr("disabled");
            $("#istate").removeAttr("disabled");
            $("#iauth").removeAttr("disabled");
}
   var id = $('#sauth').val();
       var scat = $('#scategory').val();
    if (id == '1' && (scat.match(/obc.*/) || scat.match(/OBC.*/))) {
$('#myModalobc').modal({backdrop: 'static', keyboard: false}); // to fix modal to show always 
    }


 
 
  

</script>