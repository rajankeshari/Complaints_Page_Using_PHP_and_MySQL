<div class="alert alert-success">
    <center><a href="<?php echo base_url(); ?>assets/telephone_directory/TD.pdf" target="_blank"> Click here to know Office Ext./Mobile Number/Email-ID of IIT(ISM) Faculty and Others</a> </center>
</div>


<?php $ui = new UI(); ?>
    <body class="skin-blue">
        <div class="center">
<?php
        $errorHead = "";  //"Error"
        $uiType = "error";
        $errorMessage = "An error occured while logging in. Please try again.";
        if($error_code == 1) {
            $errorMessage = "Invalid username or password. Please try again.";
        }
        else if($error_code == 2) {
            $errorMessage = "You do not have access to that location.";
        }
        else if($error_code == 0) {
            $errorHead = "";  //"Login";
            $uiType = "info";
            $errorMessage = "Please enter your username and password";
        }
        else if($error_code == 4) {
            $errorHead = "Your Password has been Changed.";
            $errorMessage = "Please login again to continue.";
            $uiType = "info";
        }
        else if($error_code == 5) {
            $errorHead = ""; "Account created";
            $errorMessage = "Your account has been created. Please enter your username and password to login.";
            $uiType = "info";
        }
        else if($error_code == 6) {
            $errorHead = "Your Hints have been Changed.";
            $errorMessage = "Please Remember those to change your password in future.";
            $uiType = "info";
        }

        $logoImg = '<img class="big-logo" src="'.base_url().'assets/images/mis-logo-big.png" height="40" style="padding-right: 5px"/>';
        $formBox = $ui->box()->title($logoImg . " Please login to continue")->containerClasses("form-box")->open();
            $ui->callout()
              ->uiType($uiType)
              ->title($errorHead)
              ->desc($errorMessage)
              ->show();
            $form = $ui->form()->id("login")->action("login/login_user")->open();
                $username = $ui->input()
                              ->type("text")
                              ->name("username")
                              ->placeholder("Username")
                              ->required()
                              ->label("Username");

                $password = $ui->input()
                              ->type("password")
                              ->name("password")
                              ->placeholder("Password")
                              ->required()
                              ->label("Password");
                              
                if($error_code == 1) {
                        $password->uiType("error");
                }
                
                $username->show();
                $password->show();
            
                $ui->button()
                  ->type("submit")
                  ->value("Login")
                  ->icon($ui->icon("sign-in"))
                  ->uiType("primary")
                  ->id("submit")
                  ->block()
                  ->show();

        $form->close();
?>
                <hr />
                <center>
                
                <button id="link"> Forgot Password </button> <!--&bull;-->
                <!-- <a href="#">Online Help</a> &bull;
                <a href="#">Wiki</a> &bull;
                <a href="#">Developers</a>-->
                </center>
<?
        
            
        $formBox->close();

?>

    </div>

    <?php
    $ui=new UI();
        $test_row = $ui->row()->classes('modal fade')->id('reset_dialog_fade')->open();
        $test_col = $ui->col()->classes('modal-dialog')->id('reset_dialog_dialog')->open();
        $test_box = $ui->box()->classes('modal-content')->id('reset_dialog_content')->open();
?>
    <div class="modal-header" id="header_style">
        <i class="fa fa-hand-o-right"></i>
        <b>Enter Your User Id / Admission Number</b>
    </div>
    <div class="modal-body"><?php
       // $form=$ui->form()->action("reset_pass_controller/check_adm")->multipart()->open();
        // <!-- <form action="reset_pass_controller/open_form">  -->
             
                $ui->input()
                    ->type("success")
                    //->label("enter here")
                    //->placeholder("2013je0780")
                    ->name('adm_no')
                    ->id('adm_no')
                    //->value("0")
                    ->required()
                    ->show(); //var_dump($load_error);
            ?>         
        <div id="error_message">
        <?php 
            $box = $ui->alert()
                  //->title("...")
                  ->desc("No Such User Id / Admission Number in our Database")
                  ->uiType("warning")
                  ->show();

        ?>
    </div> 
    <div class="modal-footer">
        <button class="btn btn-default btn-flat" id="proceed" >
            Proceed 
            <i class="fa fa-caret-right"></i>
         </button> <!-- </form> --> 
         <a href="<?php echo site_url('reset_password/reset_pass_controller/back_to_login') ?>">
          <button class="btn btn-default btn-flat" id="go_back">
                <i class="fa fa-caret-left"></i>
                Go back 
          </button> </a>
         
    </div>

<?php
    $test_box->close();
    $test_col->close();
    $test_row->close();
?>
</body>
</html>

<style>
#go_back{
        float: left;
    }
#header_style{
    background-color: #3C8DBC;
    color: rgba(255,255,255,0.8);
    text-shadow: #333 3px 3px 5px;
    font-family: sans-serif;
  }
    #link{
        background: none;
        border: none;
        color: #337ab7;
        font-family: sans-serif;
        font-size: 12px;
    }
    #link:hover{
        color: #3c8dbc;
    }
</style>

<script>
    $(document).ready(function () {
        
        
    $('#link').on('click', function () {
        $('#reset_dialog_fade').modal('show');
      });
    });

$(document).ready(function () {
    $('#error_message').hide();
        $('#proceed').on('click', function () {
          $.ajax({
            url: site_url("reset_password/reset_pass_controller/check_adm/"),
            type: 'POST',
            data:{id:$('#adm_no').val()},
            success : function(result)
            {
              if(result ==0){
                      $('#error_message').show();
              }else{
                  //alert("<?php echo base_url() ?>");
                  window.location="<?php echo base_url() ?>index.php/reset_password/reset_pass_controller/to_required_page/"+result;
              }
           }
          });  
           
        });
     });

</script>