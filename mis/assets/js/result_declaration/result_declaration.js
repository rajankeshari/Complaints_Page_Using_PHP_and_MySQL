/* result declaration process
 * Copyright (c) ISM dhanbad * 
 * @category   phpExcel
 * @package    exam_tabulation
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #26/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */

// Module to save  declaraed result though ajax only
 function  save_resut_declaration(){  
    if (confirm("Are you Sure?")){             
        actionCaption = "Result Published";           
        //ajax call                   
       $('#fyears').validate({
       rules: {
        b_date: {            
            required: true             
           },                   
          messages: {                     
              b_date: {
                required: "Please provide Date of Publish",               
            },           
          },
        invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                 setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        }    
   });
       if ($("#fyears").valid()) {      
       var postdata = { 
                        'hsyear': $('#hsyear').val(),
                        'hsess': $('#hsess').val(),
                        'hetype': $('#hetype').val(),
                        'hdeptid': $('#hdeptid').val(),
                        'hsec_name': $('#hsec_name').val(),
                        'hcid': $('#hcid').val(),
                        'hbid': $('#hbid').val(),
                        'hsem': $('#hsem').val(),
                        'b_date': $('#b_date').val(),
                        'crs_name':$('#crs_name').val(),
                        'brs_name':$('#brs_name').val(),
                        'deptname':$('#deptname').val()
                    }                    
              $.ajax({
                        url: site_url('result_declaration/result_declaration_drside/save_data'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,     
                        beforeSend: function () {
                   $("#loading-image").show();
                },
                        success: function (jsonObj) {
                            $("#loading-image").hide();
                           // console.log(jsobObj);
                            //  var jsonObj = $.parseJSON(data);                                         
                             $('#viewreport').modal('hide');                             
                             $("#msg").show();                                    
                              $("#msg").html("");                                                                                
                            if (jsonObj.result === "Successfully") {                                                                                                                                                                                     
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for candidates </strong>for <b>Session_yr:</b>" + $('#hsyear').val() + ",<b>Session:</b>" + $('#hsess').val() + ",<b>Exam type:</b>" +  $('#hetype').val() + ",<b>Department:</b>" + $('#dptname').val() + " " + (($('#hsec_name').val()) ? ",<b>Section:</b>" + $('#hsec_name').val() + "" : "") + ",<b>Course:</b>" + $('#crs_name').val() + ",<b>Branch:</b>" +  $('#brn_name').val() + ",<b>Semseter:</b>" +  $('#hsem').val()  + "" );                                                                
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for candidates </strong>for <b>Session_yr:</b>" + $('#hsyear').val() + ",<b>Session:</b>" + $('#hsess').val() + ",<b>Exam type:</b>" +  $('#hetype').val() + ",<b>Department:</b>" + $('#dptname').val() + " " + (($('#hsec_name').val()) ? ",<b>Section:</b>" + $('#hsec_name').val() + "" : "") + ",<b>Course:</b>" + $('#crs_name').val() + ",<b>Branch:</b>" +  $('#brn_name').val() + ",<b>Semseter:</b>" +  $('#hsem').val() + ",<p>"+ jsonObj.error +"</p>");
                            }                                                                                              
                                d = new Date();
                                var timestamp = d.timestamp();                                   
                                $("#msg").append(" on ");
                                $("#msg").append(timestamp); 
                                $("#msg").append(" By DR-Exam ");                                
                                setTimeout(function (){
                                if (jsonObj.result === "Successfully"){  
                                  window.location.href = site_url('result_declaration/result_declaration_drside/show_details/'+$('#hsyear').val()+'/'+$('#hsess').val()+'/'+$('#hetype').val()+'/'+$('#hdeptid').val()+'/F/'+$('#hsec_name').val());                                                       
                                 }                             
                                $("#msg").hide();                                  
                            }, 5000);                            
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
           Date.prototype.timestamp = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            var h = this.getHours().toString();
            var m = this.getMinutes().toString();
            var s = this.getSeconds().toString();
            return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h-12 : h) + ":" + (m[1]?m:"0"+m[0]) + ":" + (s[1]?s:"0"+s[0]) +" "+((h > 12) ? "PM" : "AM");
        };
      }     
   
  }
   return false;  
 }