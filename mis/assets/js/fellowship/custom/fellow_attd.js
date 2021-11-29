/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


 $(function() {
     
         $('.sdate').datepicker();
      $('.banned').each(function(){$(this).popover()});
        $('.info').each(function(){$(this).popover()});
          $("#fw_yr").change(function(){
            if ($(this).val()!='') {                            
                window.location.href=$(this).val();
            } 
        }); 
     
     
       $("#my_id").change(function(){
            if ($(this).val()!='') {
            
                window.location.href=$(this).val(); 
            } 
        });        
        $("#at_slot").change(function(){
            if ($(this).val()!='') {                
                window.location.href=$(this).val(); 
            } 
        });        
          $("#yr").change(function(){
            if ($(this).val()!='') {                
                window.location.href=$("#mon").val()+"/"+$(this).val()+"/"+$("#ac_bl").val(); 
            } 
        }); 
           $("#mon").change(function(){
            if ($(this).val()!='') {     
                window.location.href=$(this).val()+"/"+$("#yr").val()+"/"+$("#ac_bl").val(); 
            } 
        });        
        
           $("#yr1").change(function(){
            if ($(this).val()!='') {                
                window.location.href=$("#mon1").val()+"/"+$(this).val(); 
            } 
        }); 
           $("#mon1").change(function(){
            if ($(this).val()!='') {     
                window.location.href=$(this).val()+"/"+$("#yr1").val(); 
            } 
        });        
        
        $("#yr2").change(function(){
            if ($(this).val()!='') {                
                window.location.href=$("#mon2").val()+"/"+$(this).val()+"/"+$("#bts").val(); 
            } 
        }); 
           $("#mon2").change(function(){
            if ($(this).val()!='') {     
                window.location.href=$(this).val()+"/"+$("#yr2").val()+"/"+$("#bts").val(); 
            } 
        });        
        $("#pm").change(function(){            
            if ($(this).val()=='1'){                
                $("#ref_caption").text("Cheque Number");
                $("#payorder_ref_no").val("");
            }
            else if ($(this).val()=='2'){                
                $("#ref_caption").text("Cash Voucher Number");
                $("#payorder_ref_no").val("");
            }
            else if ($(this).val()=='3') {               
                $("#ref_caption").text("Demand Draft Number");
                $("#payorder_ref_no").val("");
            } 
            else if ($(this).val()=='4')  {              
                $("#ref_caption").text("RTGS/NIFT Transaction Number");
                $("#payorder_ref_no").val("");
            } 
            
        });      
       
   
        
          
    });
     function getLeaveBal(per_leave,id,fellowship_amt,leave_balanace,leave_taken){
        if($("#status_label"+id).text()!=null){
        leave_balanace=$("#fix_bal_leave"+id).val();    
         $("#leave_taken_"+id).val(leave_taken);       
        }
        $("#bl_"+id).text((Number(leave_balanace==0?30:leave_balanace)-Number(leave_taken)));
        $("#bal_leave"+id).val((Number(leave_balanace==0?30:leave_balanace)-Number(leave_taken)));
        if(((Number(leave_balanace==0?30:leave_balanace)-Number(leave_taken)))<0){          
         $("#bl_"+id).css({'color': 'red', 'font-size': '150%' });
         recovery_amt= ((Number(leave_taken) -Number(per_leave)) * (Number(fellowship_amt) / Number(per_leave)));        
         net_amt_payable=Number(fellowship_amt)- Number(recovery_amt);
         $("#rc_amt_"+id).text(Math.round(recovery_amt * 100) / 100);
         $("#rc_amount"+id).val(Math.round(recovery_amt* 100) / 100);
         $("#net_amt_"+id).text(Math.round(net_amt_payable* 100) / 100);
         $("#net_amount"+id).val(Math.round(net_amt_payable* 100) / 100);
       }
       else{        
         $("#bl_"+id).removeAttr('style');
         $("#rc_amt_"+id).text('0');
         $("#rc_amount"+id).val('0');
         $("#net_amt_"+id).text(fellowship_amt);
         $("#net_amount"+id).val(Number(fellowship_amt)); 
      }
    }
       /* function getLeaveBal(per_leave,id,fellowship_amt,leave_balanace,leave_taken){
        if($("#status_label"+id).text()!=null){
        leave_balanace=$("#fix_bal_leave"+id).val();    
         $("#leave_taken_"+id).val(leave_taken);       
        }
        $("#bl_"+id).text((Number(leave_balanace==0?30:leave_balanace)-Number(leave_taken)));
        $("#bal_leave"+id).val((Number(leave_balanace==0?30:leave_balanace)-Number(leave_taken)));
        if(((Number(leave_balanace==0?30:leave_balanace)-Number(leave_taken)))<0){          
         $("#bl_"+id).css({'color': 'red', 'font-size': '150%' });
         recovery_amt= ((Number(leave_taken) -Number(per_leave)) * (Number(fellowship_amt) / Number(per_leave)));        
         net_amt_payable=Number(fellowship_amt)- Number(recovery_amt);
         $("#rc_amt_"+id).text(Math.round(recovery_amt * 100) / 100);
         $("#rc_amount"+id).val(Math.round(recovery_amt* 100) / 100);
         $("#net_amt_"+id).text(Math.round(net_amt_payable* 100) / 100);
         $("#net_amount"+id).val(Math.round(net_amt_payable* 100) / 100);
       }
       else{        
         $("#bl_"+id).removeAttr('style');
         $("#rc_amt_"+id).text('0');
         $("#rc_amount"+id).val('0');
         $("#net_amt_"+id).text(fellowship_amt);
         $("#net_amount"+id).val(Number(fellowship_amt)); 
      }
    }
    */
   function get_days( month,year) {
    var isLeap = ((year % 4) == 0 && ((year % 100) != 0 || (year % 400) == 0));
    return [31, (isLeap ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month-1];
}
   
   
   
   // if absentee
   
  function getDynamicCalOnAbsentee(chosen_yr,chosen_mon,id,fellowship_amt,custom_fellowship_amt,custom_fellowship_rcovery_amt,leave_balanace,per_leave,absent){
         var net_amt_payable=0;
          var recovery_amt=0;
          console.log("absent:"+absent);
          if(absent>0){
           $("#absent_"+id).css({'color': 'red' });
           recovery_amt=  (((Number(absent)) * (Number(fellowship_amt) / get_days(Number(chosen_mon),Number(chosen_yr)))));   
           //net_amt_payable= (Number(fellowship_amt)-recovery_amt);
          // console.log(recovery_amt+"+"+net_amt_payable); 
         $("#rc_amt_"+id).text(   Math.round(number_format(  (Number($("#rc_amount"+id).val()) + Number(recovery_amt)),1, '.', '')));
         $("#rc_amount"+id).val(  Math.round(number_format( (Number($("#rc_amount"+id).val())  + Number(recovery_amt)),1, '.', '')));
         $("#net_amt_"+id).text(  Math.round(number_format( (Number($("#net_amount"+id).val()) - Number(recovery_amt)),1, '.', '')));      
         $("#net_amount"+id).val( Math.round(number_format( (Number($("#net_amount"+id).val()) - Number(recovery_amt)),1, '.', '')));
          if(Number($("#net_amount"+id).val())<0)
           $("#net_amt_"+id).css({'color': 'red', 'font-size': '150%' });
        }else{             
          $("#absent_"+id).removeAttr('style');             
          $("#leave_taken_"+id).val("0");        
          $("#bl_"+id).text((leave_balanace==0?Number(per_leave):leave_balanace));
          $("#bal_leave"+id).val((leave_balanace==0?Number(per_leave):leave_balanace));
         $("#bl_"+id).removeAttr('style');            
         $("#rc_amt_"+id).text(custom_fellowship_rcovery_amt);
         $("#rc_amount"+id).val(custom_fellowship_rcovery_amt);
         $("#net_amt_"+id).text(custom_fellowship_amt);        
         $("#net_amount"+id).val(custom_fellowship_amt);
         $("#net_amt_"+id).removeAttr('style');            
          }
  }
                                    
   
      function getDynamicCal(adm_yr,adm_mon,adm_day,chosen_yr,chosen_mon,per_leave,id,fellowship_amt,custom_fellowship_amt,custom_fellowship_rcovery_amt,leave_balanace,leave_taken){
          var net_amt_payable=0;
          var recovery_amt=0;
          console.log('lb:'+Number(leave_balanace));
        // checking first month of admission
     /*     if(Number(adm_yr)==Number(chosen_yr) && Number(adm_mon)==Number(chosen_mon))
         {
            net_amt_payable = (get_days(Number(chosen_mon),Number(chosen_yr))-(Number(adm_day)-1)) *  (Number(fellowship_amt)/get_days(Number(chosen_mon),Number(chosen_yr)));
            recovery_amt=  fellowship_amt- net_amt_payable;
          }else{
              net_amt_payable=fellowship_amt;
              recovery_amt=0;
         }*/
         // checking balance leave if greater than threshold
        console.log(Number(leave_balanace)+"+"+Number(leave_taken)+"+"+Number(per_leave));
        if(((Number(leave_balanace)-Number(leave_taken)))<0){          
          $("#bl_"+id).css({'color': 'red', 'font-size': '150%' });
          recovery_amt=((Number(leave_balanace)-Number(leave_taken)) * (Number(fellowship_amt) / Number(per_leave)));        
          //net_amt_payable=(Number(fellowship_amt)- Number(recovery_amt));                               
         //console.log(recovery_amt+"+"+net_amt_payable);
         $("#bl_"+id).text( (Number(leave_balanace)-Number(leave_taken)));
         $("#bal_leave"+id).val((Number(leave_balanace)-Number(leave_taken)));        
         $("#rc_amt_"+id).text(   Math.round(number_format(  (Number($("#rc_amount"+id).val()) + Math.abs(Number(recovery_amt))),1, '.', '')));
         $("#rc_amount"+id).val(  Math.round(number_format( (Number($("#rc_amount"+id).val())  + Math.abs(Number(recovery_amt))),1, '.', '')));
         $("#net_amt_"+id).text(  Math.round(number_format( (Number($("#net_amount"+id).val()) - Math.abs(Number(recovery_amt))),1, '.', '')));        
         $("#net_amount"+id).val( Math.round(number_format( (Number($("#net_amount"+id).val()) - Math.abs(Number(recovery_amt))),1, '.', '')));
        if(Number($("#net_amount"+id).val())<0)
           $("#net_amt_"+id).css({'color': 'red', 'font-size': '150%' });
       }
       else{ 
            console.log(Number(leave_balanace));
         $("#absent_"+id).removeAttr('style');      
         $("#absent_"+id).val('0');               
         $("#bl_"+id).text( (Number(leave_balanace)-Number(leave_taken)));
         $("#bal_leave"+id).val((Number(leave_balanace)-Number(leave_taken)));        
         $("#rc_amt_"+id).text(custom_fellowship_rcovery_amt);
         $("#rc_amount"+id).val(custom_fellowship_rcovery_amt);
         $("#net_amt_"+id).text(custom_fellowship_amt);        
         $("#net_amount"+id).val(custom_fellowship_amt);
         $("#bl_"+id).removeAttr('style');  
         $("#net_amt_"+id).removeAttr('style');            
      }
       
    }
function clearAttd(id,custom_fellowship_amt,custom_fellowship_rcovery_amt) {    
     if (confirm("Are you Sure?")) {              
          $("#remark_td" + id).show();            
          $("#leave_taken_"+id).attr("disabled",false);                         
          $("#absent_"+id).attr("disabled",false);                         
          $("#bal_leave"+id).val(Number(Number($("#leave_taken_"+id).val())+ Number($("#bal_leave"+id).val())));
          $("#fix_bal_leave"+id).val(Number($("#bal_leave"+id).val()));
          $("#bl_"+id).text($("#bal_leave"+id).val());
          $("#leave_taken_"+id).val('0');
          $("#absent_"+id).val('0');
          $("#rc_amt_"+id).text(custom_fellowship_rcovery_amt);
          $("#rc_amount"+id).val(custom_fellowship_rcovery_amt);
          $("#net_amt_"+id).text(custom_fellowship_amt);        
          $("#net_amount"+id).val(custom_fellowship_amt);
          $("#clear"+id).hide();
          $("#modify"+id).show();
     }
}

function process(id,target_degree,mon,yr, action) { //dda
    if (confirm("Are you Sure?")) {      
            if (action === "forward") {  
                         var actionCaption = "Attendance forwarding to HOD";
                   }   
                  else if (action === "modify") {

                        var actionCaption = "Attendance Modification & forwarding to HOD";
                   }
        $("#fellowship_process_form").removeData('validator'); // removing  All  form's rule   
        $("#fellowship_process_form").validate();
        
        $("#leave_taken_" + id).rules('add', {
            required: true,
            messages: {
                required: 'Please Enter No. of days of leave for the current month',
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
        });         
        $("#attd_remark" + id).rules('add', {
            required: function (element) {
                if (action === "modify")
                {
                    return true;
                } else {
                    return false;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
              /*setTimeout(function () {
                    $("#msg").hide();
                }, 5000);*/
            }
        });
    
        if ($("#fellowship_process_form").valid()) {                    
                      //ajax call                    
                  var postdata = {stud_reg_no: id, action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr,present_yr_fellowship:$("#p_yr"+ id).val(), days_absent:$("#absent_" + id).val(),admissible_leave:$("#leave_taken_" + id).val(),leave_balance:$("#bal_leave"+id).val(),recovery_amt:$("#rc_amount"+id).val(),net_amt_payable:$("#net_amount"+id).val(), remark:$("#attd_remark" + id).val()};
                    $.ajax({
                        url: site_url('fellowship/fellowshipProcess/attd_action_by_dda'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                 
                                   $("#modify" + id).hide();      
                                   $("#clear" + id).show();   
                                   $("#leave_taken_"+id).attr("disabled",true);   
                                   $("#absent_"+id).attr("disabled",true);   
                                   $("#remark_td" + id).hide();        
                                if (jsonObj.action === "forward") {                                                                                                           
                                $("#process" + id).hide();                                                       
                                $("#status_label" + id).show();                         
                                    
                                }                               
                                else if(jsonObj.action === "modify"){
                                      $("#remark_td" + id).hide(); 
                                      $("#log"+id).html('<a role="button" class="btn btn-primary btn-xs"  href="javascript:void(0);" onclick="getAttdEditHistory(\'' + id + '\',\'' + mon + '\',\'' + yr + '\')"  data-toggle="modal" data-target="#attd_edit_modal"  data-whatever="@twbootstrap" title="action logs for the current  month"><i class="fa fa-history"></i>&nbsp;Action Logs</a>');                                                                        
                                }
                                $("#status_label" + id).removeClass().addClass('label label-success');
                                $("#status_label" + id).text("Forwarded");  
                                      // US common date timestamp
                                 d = new Date();
                                 var timestamp = d.timestamp();                                    
                                $("#dt_label" + id).text(timestamp);
                                $("#rejectby_label" + id).html("");
                                $("#fellow" + id).removeClass().addClass("success");                                                    
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                            } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + ",<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
                }
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
          return false;
        }  
        

function getAttdEditHistory(id,mon,yr) {
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/getAttdEditHistoryList'),
        type: "POST",
        dataType: "json",
        data: {stud_reg_no: id,processing_mon: mon,processing_yr: yr},
        success: function (jsonObj) {

            var resultHtml = "<div class='panel panel-info'>" +
                    "<div class='panel-heading'><i class='fa fa-history'></i>&nbsp;Attendance Modification History(Accomplished by Deptt. Dealing Asstt.)</div>" +
                    "<div class='table-responsive'><table class='table table-condensed'  id ='printview'>" +
                    "<thead>" +
                    "<tr>" +
                    "<th>Sl. No.</th>" +
                    "<th>Date</th>" +
                    "<th>Processing<p>Month & Year</p></th>" +
                    "<th>Forwarding Date</th>" +
                    "<th>Days Absent</th>" +
                    "<th>Leave Balanace</th>" +
                    "<th>Recovery Amt.<p>(if Any)</p></th>" +
                    "<th>Net Amt.Payable</th>" +                                        
                    "<th>Remark</th>" +
                    "</tr>" +
                    "</thead>" +
                    "<tbody>";

            if (jsonObj.result != 'Failed') {
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                        resultHtml += "<tr>" +
                                "<td>" + Number((i + 1)) + "</td>" +
                                "<td>" + (jsonObj[i].created != null ? jsonObj[i].created : "N/A") + "</td>" +
                                "<td>" +  jsonObj[i].processing_mon+ "/"+jsonObj[i].processing_yr+ "</td>" +
                                "<td>" + (jsonObj[i].attd_fd_dt != null ? jsonObj[i].attd_fd_dt : "N/A") + "</td>" +
                                "<td>" +  jsonObj[i].days_absent + "</td>" +
                                "<td>" + jsonObj[i].leave_balance+ "</td>" +
                                "<td>" + (jsonObj[i].recovery_amt != "" ? jsonObj[i].recovery_amt : "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].net_amt_payable != "" ? jsonObj[i].net_amt_payable : "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].remark != "" ? jsonObj[i].remark: "N/A") + "</td>" +                                                                
                                "</tr>";
                    }
                resultHtml += "</table>";
                resultHtml += "</tbody>";                 
                }
                
            }
            else {
                if (jsonObj.error === "No Record Found") {
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<div  class='" + pclass + "'><a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div>";
            }
            resultHtml += "</div></div>";
            $('#attd_history_Header').text('Action Logs[#' + id + ']');
            $('#attd_history_Content').html(resultHtml);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    });

}


function process1(id,target_degree,mon,yr, action) { //gd action
  if (confirm("Are you Sure?")) {      
     if (action === "approve") {  
         var actionCaption = "Attendance Approval";
      }   
      else if (action === "suspend") {
          var actionCaption = "Attendance Suspension";
           $("#remark_td" + id).show(); 
      }
   
  $("#fellowship_process_gd_form").removeData('validator'); // removing  All  form's rule   
  $("#fellowship_process_gd_form").validate();           
  $("#attd_remark_gd" + id).rules('add', {
            required: function (element) {
                if (action === "approve")
                {
                    return false;
                } else {
                    return true;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
            /*  setTimeout(function () {
                    $("#msg").hide();
                }, 5000);*/
            }
   });   
  if ($("#fellowship_process_gd_form").valid()) {      
            //ajax call                   
       var postdata = {stud_reg_no: id, action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr, remark: $("#attd_remark_gd" + id).val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/attd_action_by_gd'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "approve") {                                                                                                           
                                    $("#remark_td" + id).hide(); 
                                    $("#process" + id).hide(); 
                                    $("#suspend" + id).hide();           
                                    $("#status_label" + id).show();
                                    $("#status_label" + id).removeClass().addClass('label label-success');
                                    $("#status_label" + id).text("Approved");                                                                   
                                    $("#fellow" + id).removeClass().addClass("success");                                                    
                                 }                               
                                else if(jsonObj.action === "suspend"){
                                       $("#remark_td" + id).hide(); 
                                       $("#process" + id).hide(); 
                                       $("#suspend" + id).hide();                                 
                                       $("#status_label" + id).show();
                                       $("#status_label" + id).removeClass().addClass('label label-danger');
                                       $("#status_label" + id).text("Suspended");                                                                                                                                                                                                                                
                                       
                                      $("#log"+id).html('<a role="button" class="btn btn-primary btn-xs"  href="javascript:void(0);" onclick="getAttd_action_log(\'' + id + '\',\'' + mon + '\',\'' + yr + '\',\'gd\')"  data-toggle="modal" data-target="#attd_edit_modal"  data-whatever="@twbootstrap" title="action logs for the current  month"><i class="fa fa-history"></i>&nbsp;Action Logs</a>');                                
                                                              
                                    
                                       $("#fellow" + id).removeClass().addClass("danger");       
                                     
                                 }
                                    d = new Date();
                                    var timestamp = d.timestamp();
                                    $("#dt_label" + id).text(timestamp);
                                    $("#rejectby_label" + id).html("");
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + ",<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
                }
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
          return false;
        }  
        
     function getAttd_action_log(id,mon,yr,actor) {
      
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/getAttd_action_log'),
        type: "POST",
        dataType: "json",
        data: {stud_reg_no: id,processing_mon: mon,processing_yr: yr,actor:actor},
        success: function (jsonObj) {
         var actor_desig="";
        
         if( actor=='gd') {      
            actor_desig="Guide";
        }  
        else if( actor=='fic') {      
           actor_desig="Faculty In-charge";        
       }  
        else if( actor=='hod') {          
           actor_desig="HOD";          
        }  
        else if( actor=='acad_da') {          
           actor_desig="Academic Dealing Asstt. ";          
        }  
        else if( actor=='acad_ar') {          
           actor_desig="Academic Asstt. Registrar ";          
        }  
        else if( actor=='acc_da') {          
           actor_desig="A/c Dealing Asstt. ";          
        }  
         else if( actor=='acc_ar') {          
           actor_desig="A/c  Asstt. Regstrar";          
        }  
            var resultHtml = "<div class='panel panel-info'>" +
                    "<div class='panel-heading'><i class='fa fa-history'></i>&nbsp;Action Log(Action taken by "+actor_desig+")</div>" +
                    "<div class='table-responsive'><table class='table table-condensed'  id ='printview'>" +
                    "<thead>" +
                    "<tr>" +
                    "<th>Sl. No.</th>" +
                    "<th>Date</th>" +
                    "<th>Processing<p>Month & Year</p></th>" +                   
                    "<th>Action status</th>" +
                    "<th>Action Date</th>" +
                    "<th>Remark</th>" +
                    "</tr>" +
                    "</thead>" +
                    "<tbody>";

            if (jsonObj.result != 'Failed') {
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                          if( actor=='gd') {
         
          action_status= jsonObj[i].attd_approval_by_gd != "" ? jsonObj[i].attd_approval_by_gd == "Y" ? "<b style='color:green'>" + jsonObj[i].attd_approval_by_gd + "</b>" : jsonObj[i].attd_approval_by_gd == "S" ? "<b style='color:red'>" + jsonObj[i].attd_approval_by_gd + "</b>" : "<b style='color:orange'>" + jsonObj[i].attd_approval_by_gd + "</b>" : "N/A";
          action_time=jsonObj[i].attd_action_dt != null ? jsonObj[i].attd_action_dt : "N/A";
      }  
      else if( actor=='fic') {
        
          action_status= jsonObj[i].attd_forward_by_fic != "" ? jsonObj[i].attd_forward_by_fic == "Y" ? "<b style='color:green'>" + jsonObj[i].attd_forward_by_fic + "</b>" : jsonObj[i].attd_forward_by_fic == "R" ? "<b style='color:red'>" + jsonObj[i].attd_forward_by_fic + "</b>" : "<b style='color:orange'>" + jsonObj[i].attd_forward_by_fic + "</b>" : "N/A";
          action_time=jsonObj[i].attd_action_dt != null ? jsonObj[i].attd_action_dt : "N/A";
      }  
      else if( actor=='hod') {
        
           action_status= jsonObj[i].attd_approval_by_hod != "" ? jsonObj[i].attd_approval_by_hod == "Y" ? "<b style='color:green'>" + jsonObj[i].attd_approval_by_hod + "</b>" : jsonObj[i].attd_approval_by_hod == "R" ? "<b style='color:red'>" + jsonObj[i].attd_approval_by_hod + "</b>" : "<b style='color:orange'>" + jsonObj[i].attd_approval_by_hod + "</b>" : "N/A";
           action_time=jsonObj[i].attd_action_dt != null ? jsonObj[i].attd_action_dt : "N/A";
      }  
            
    else if( actor=='acad_da') {
        
           action_status= jsonObj[i].attd_forwarding_by_acad_da != "" ? jsonObj[i].attd_forwarding_by_acad_da == "Y" ? "<b style='color:green'>" + jsonObj[i].attd_forwarding_by_acad_da + "</b>" : jsonObj[i].attd_forwarding_by_acad_da == "R" ? "<b style='color:red'>" + jsonObj[i].attd_forwarding_by_acad_da + "</b>" : "<b style='color:orange'>" + jsonObj[i].attd_forwarding_by_acad_da + "</b>" : "N/A";
           action_time=jsonObj[i].attd_action_dt != null ? jsonObj[i].attd_action_dt : "N/A";
      }  
      else if( actor=='acad_ar') {
        
           action_status= jsonObj[i].attd_approval_by_acad_ar != "" ? jsonObj[i].attd_approval_by_acad_ar == "Y" ? "<b style='color:green'>" + jsonObj[i].attd_approval_by_acad_ar + "</b>" : jsonObj[i].attd_approval_by_acad_ar == "R" ? "<b style='color:red'>" + jsonObj[i].attd_approval_by_acad_ar + "</b>" : "<b style='color:orange'>" + jsonObj[i].attd_approval_by_acad_ar + "</b>" : "N/A";
           action_time=jsonObj[i].attd_action_dt != null ? jsonObj[i].attd_action_dt : "N/A";
      }  
      else if( actor=='acc_da') {
        
           action_status= jsonObj[i].attd_forwarding_by_acc_da != "" ? jsonObj[i].attd_forwarding_by_acc_da == "Y" ? "<b style='color:green'>" + jsonObj[i].attd_forwarding_by_acc_da + "</b>" : jsonObj[i].attd_forwarding_by_acc_da == "R" ? "<b style='color:red'>" + jsonObj[i].attd_forwarding_by_acc_da + "</b>" : "<b style='color:orange'>" + jsonObj[i].attd_forwarding_by_acc_da + "</b>" : "N/A";
           action_time=jsonObj[i].attd_action_dt != null ? jsonObj[i].attd_action_dt : "N/A";
      }   
         else if( actor=='acc_ar') {
        
           action_status= jsonObj[i].attd_approval_by_acc_ar != "" ? jsonObj[i].attd_approval_by_acc_ar == "Y" ? "<b style='color:green'>" + jsonObj[i].attd_approval_by_acc_ar + "</b>" : jsonObj[i].attd_approval_by_acc_ar == "R" ? "<b style='color:red'>" + jsonObj[i].attd_approval_by_acc_ar + "</b>" : "<b style='color:orange'>" + jsonObj[i].attd_approval_by_acc_ar + "</b>" : "N/A";
           action_time=jsonObj[i].attd_action_dt != null ? jsonObj[i].attd_action_dt : "N/A";
      }   
     
                        resultHtml += "<tr>" +
                                "<td>" + Number((i + 1)) + "</td>" +
                                "<td>" + (jsonObj[i].created != null ? jsonObj[i].created : "N/A") + "</td>" +
                                "<td>" +  jsonObj[i].processing_mon+ "/"+jsonObj[i].processing_yr+ "</td>" +
                                "<td>" + action_status+"</td>"+
                                "<td>" + action_time + "</td>" +                                
                                "<td>" + (jsonObj[i].remark != "" ? jsonObj[i].remark: "N/A") + "</td>" +                                                                
                                "</tr>";
                    }
                     resultHtml += "</table>";
                      resultHtml += "</tbody>"+
                                  "</div"+
                                "</div>"+
                                "<div><i class='fa fa-hand-o-right'></i> &nbsp; S => Suspended , R=>Rejected , N => Pending , Y => Forwarded/Approved , C => Auto Cancelled , N/A => Not Applicable </div>";
                 }
                       
            }
            else {
                if (jsonObj.error === "No Record Found") {
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<div  class='" + pclass + "'><a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div>";
            }
            
            $('#attd_history_Header').text('Action Logs[#' + id + ']');
            $('#attd_history_Content').html(resultHtml);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    });

}   

function generic_forward(id,target_degree,mon,yr, action,action_by) {   //Forwrad Action  Where ever rqd.
 
  if (confirm("Are you Sure?")) {      
     if (action === "forward") {  
         var actionCaption = "Attendance Forwarding";
         $("#remark_td" + id).hide(); 
      }         
      else if (action === "reject") {
           var actionCaption = "Attendance Rejection";
           $("#remark_td" + id).show(); 
      }   
  $("#fellowship_process_"+action_by+"_form").removeData('validator'); // removing  All  form's rule   
  $("#fellowship_process_"+action_by+"_form").validate();           
  $("#attd_remark" + id).rules('add', {
            required: function (element) {
                if (action === "forward")
                {
                    return false;
                } else {
                    return true;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
              /*setTimeout(function () {
                    $("#msg").hide();
                }, 5000);*/
            }
   });   
  if ($("#fellowship_process_"+action_by+"_form").valid()) {      
            //ajax call                   
       var postdata = {stud_reg_no: id, action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr, remark: $("#attd_remark" + id).val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/attd_action_by_'+action_by+''),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "forward") {
                                    actionCaption = "Attendance Forwarding";
                                    $("#remark_td" + id).hide(); 
                                    $("#process" + id).hide(); 
                                    $("#reject" + id).hide();           
                                    $("#status_label" + id).show();
                                    $("#status_label" + id).removeClass().addClass('label label-success');
                                    $("#status_label" + id).text("Forwarded");       
                                     $("#fellow" + id).removeClass().addClass("success");  
                                 }                               
                                else if(jsonObj.action === "reject"){
                                     actionCaption = "Attendance Rejection";
                                       $("#remark_td" + id).hide(); 
                                       $("#process" + id).hide();   
                                        $("#reject" + id).hide();       
                                       $("#status_label" + id).show();
                                       $("#status_label" + id).removeClass().addClass('label label-danger');
                                       $("#status_label" + id).text("Rejected");                                                                                                                                                           
                                                                   
                                                              
                                    $("#log"+id).html('<a role="button" class="btn btn-primary btn-xs"  href="javascript:void(0);" onclick="getAttd_action_log(\'' + id + '\',\'' + mon + '\',\'' + yr + '\',\''+action_by+'\')"  data-toggle="modal" data-target="#attd_edit_modal"  data-whatever="@twbootstrap" title="action logs for the current  month"><i class="fa fa-history"></i>&nbsp;Action Logs</a>');
                                    $("#fellow" + id).removeClass().addClass("danger");       
                                     
                                 }
                                      d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label" + id).text(timestamp);
                                $("#rejectby_label" + id).html("");
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + ",<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
                }
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
          return false;
        }  
   

   function approve_reg(degree,mon,yr){     
       if (confirm("Are you Sure?")) {      
       var actionCaption = "Attendance approval for All the fellows listed above";
       var postdata = {processing_mon: mon,processing_yr: yr};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/attd_action_by_rg'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "approve") {   
                                    actionCaption = "Attendance approval for All the fellows listed above";
                                    $("#remark_td" + id).hide(); 
                                    $("#process" + id).hide(); 
                                    $("#reject" + id).hide();           
                                    $("#status_label" + id).show();
                                    $("#status_label" + id).removeClass().addClass('label label-success');
                                    $("#status_label" + id).text("Approved");     
                                    $("#fellow" + id).removeClass().addClass("success");                                                    
                                 }                                                           
                                d = new Date();
                                var timestamp = d.timestamp();                                   
                                $("#dt_label" + id).text(timestamp);
                                $("#rejectby_label" + id).html("");
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + ",<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    });
                             Date.prototype.timestamp = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            var h = this.getHours().toString();
            var m = this.getMinutes().toString();
            var s = this.getSeconds().toString();
            return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h-12 : h) + ":" + (m[1]?m:"0"+m[0]) + ":" + (s[1]?s:"0"+s[0]) +" "+((h > 12) ? "PM" : "AM");
            }
        }          
          return false;
        }  
        
        
  function generic_approval(id,target_degree,mon,yr, action,action_by) {   //Approval Action  Where ever rqd.
  if (confirm("Are you Sure?")) {      
     if (action === "approve") {  
         var actionCaption = "Attendance Approval";
      }         
      else if (action === "reject") {
           var actionCaption = "Attendance Rejection";
           $("#remark_td" + id).show(); 
      }   
  $("#fellowship_process_"+action_by+"_form").removeData('validator'); // removing  All  form's rule   
  $("#fellowship_process_"+action_by+"_form").validate();           
  $("#attd_remark" + id).rules('add', {
            required: function (element) {
                if (action === "approve")
                {
                    return false;
                } else {
                    return true;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
              /*setTimeout(function () {
                    $("#msg").hide();
                }, 5000);*/
            }
   });   
  if ($("#fellowship_process_"+action_by+"_form").valid()) {      
            //ajax call                   
       var postdata = {stud_reg_no: id, action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr, remark: $("#attd_remark" + id).val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/attd_action_by_'+action_by+''),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "approve") {
                                    actionCaption = "Attendance Approval";
                                    $("#remark_td" + id).hide(); 
                                    $("#process" + id).hide(); 
                                    $("#reject" + id).hide();           
                                    $("#status_label" + id).show();
                                    $("#status_label" + id).removeClass().addClass('label label-success');
                                    $("#status_label" + id).text("Approved");   
                                    $("#fellow" + id).removeClass().addClass("success");                                                    
                                 }                               
                                else if(jsonObj.action === "reject"){
                                    actionCaption = "Attendance Rejection";
                                       $("#remark_td" + id).hide(); 
                                       $("#process" + id).hide(); 
                                       $("#reject" + id).hide();           
                                       $("#status_label" + id).show();
                                       $("#status_label" + id).removeClass().addClass('label label-danger');
                                       $("#status_label" + id).text("Rejected");                                                                                                                       
                                    
                                     $("#log"+id).html('<a role="button" class="btn btn-primary btn-xs"  href="javascript:void(0);" onclick="getAttd_action_log(\'' + id + '\',\'' + mon + '\',\'' + yr + '\',\''+action_by+'\')"  data-toggle="modal" data-target="#attd_edit_modal"  data-whatever="@twbootstrap" title="action logs for the current  month"><i class="fa fa-history"></i>&nbsp;Action Logs</a>');                         
                                   
                                       $("#fellow" + id).removeClass().addClass("danger");       
                                     
                                 }
                                      d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label" + id).text(timestamp);
                                $("#rejectby_label" + id).html("");
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + ",<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
                }
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
          return false;
        }  
  function   attd_list_grp_approve(target_degree,mon,yr,action,action_by){
         if (confirm("Are you Sure?")) {      
     if (action === "approve") {  
         var actionCaption = "Attendance approval";                  
      }               
              //ajax call                   
       var postdata = { action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr,slot:$("#bts").val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/attd_grp_action_by_'+action_by+''),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "approve") {
                                    actionCaption = "Attendance approval";      
                                    $("#process_final").hide();                                     
                                    $("#status_label_final").show();
                                    $("#status_label_final").removeClass().addClass('label label-success');
                                    $("#status_label_final").text("Approved");                                           
                                 }                                                         
                                  d = new Date();
                                  var timestamp = d.timestamp();                                   
                                 $("#dt_label_final").text(" By Registrar on ");                               
                                 $("#dt_label_final").append(timestamp);                                   
                                $("#dt_label_final").text(timestamp);                               
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for all fellows listed below." );
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for all fellows listed below. ,<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
                }
             Date.prototype.timestamp = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            var h = this.getHours().toString();
            var m = this.getMinutes().toString();
            var s = this.getSeconds().toString();
            return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h-12 : h) + ":" + (m[1]?m:"0"+m[0]) + ":" + (s[1]?s:"0"+s[0]) +" "+((h > 12) ? "PM" : "AM");
        };
          
          
       
          return false;  
  }
 function  attd_list_grp_forward(target_degree,mon,yr,action,action_by){
    if (confirm("Are you Sure?")) {      
     if (action === "forward") {  
         var actionCaption = "Attendance Approval";         
        // if(action_by=='acc_ar_ga')actionCaption+= " to Registrar ";
      }               
              //ajax call                   
       var postdata = { action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/attd_grp_action_by_'+action_by+''),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "forward") {    
                                    actionCaption = "Attendance Approval";         
                                    $("#process_final").hide();                                     
                                    $("#status_label_final" ).show();
                                    $("#status_label_final" ).removeClass().addClass('label label-success');
                                    $("#status_label_final" ).text("Approved");                                                                             
                                 }                                                         
                                  d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label_final").text(" By Asstt. Registrar(A/c) on ");                               
                                $("#dt_label_final").append(timestamp);
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for all fellows listed below." );
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for all fellows listed below. ,<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
                }
             Date.prototype.timestamp = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            var h = this.getHours().toString();
            var m = this.getMinutes().toString();
            var s = this.getSeconds().toString();
            return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h-12 : h) + ":" + (m[1]?m:"0"+m[0]) + ":" + (s[1]?s:"0"+s[0]) +" "+((h > 12) ? "PM" : "AM");
        };
          
          
       
          return false;  
 }
 
 
 function  bill_forward(target_degree,mon,yr,bill_amt,action,action_by){
    if (confirm("Are you Sure?")) {      
     if (action === "forward") {  
         var actionCaption = "Bill Forwarding";         
         if(action_by=='acc_da')actionCaption+= " to Asstt.Registrar(A/c) ";
         else if(action_by=='acc_ar_ga')actionCaption+= " to Registrar ";
      }               
              //ajax call                   
       var postdata = { action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr,bill_amt:bill_amt,slot:$("#bts").val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/bill_forward_by_'+action_by+''),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "forward") {      
                                    
                                    actionCaption = "Bill Forwarding";         
                                    $("#process_bill").hide();                                     
                                    $("#status_label_final" ).show();
                                    $("#status_label_final" ).removeClass().addClass('label label-success');
                                    $("#status_label_final" ).text("Forwarded");                                                                            
                                 }                                                         
                                  d = new Date();
                                   var timestamp = d.timestamp();
                                 if(action_by=='acc_da') $("#dt_label_final").text(" By Dealing Asstt.(A/c) on ");
                                 else if(action_by=='acc_ar_ga')$("#dt_label_final").text(" By  Asstt.Registrar (A/c) on ");                               
                                $("#dt_label_final").append(timestamp);                               
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for all fellows listed below." );
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for all fellows listed below. ,<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
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
      return false;  
 }
 
 
 function  bill_approve(target_degree,mon,yr,bill_amt,action,action_by){
   
         if (confirm("Are you Sure?")) {      
     if (action === "approve") {  
         var actionCaption = "Bill approval";                  
      }               
              //ajax call                   
       var postdata = { action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr,bill_amt:bill_amt,slot:$("#bts").val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/bill_approval_by_'+action_by+''),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "approve") {  
                                    actionCaption = "Bill approval";          
                                    $("#process_bill").hide();                                     
                                    $("#status_label_final").show();
                                    $("#status_label_final").removeClass().addClass('label label-success');
                                    $("#status_label_final").text("Approved");                                           
                                 }                                                         
                                  d = new Date();
                                   var timestamp = d.timestamp();   
                                 $("#dt_label_final").text(" By Registrar on ");
                                $("#dt_label_final").append(timestamp);                                                                   
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for all fellows listed below." );
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for all fellows listed below. ,<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
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
     return false;  
  }
  
  
  function  payorder_forward(target_degree,mon,yr,action){  
    if (confirm("Are you Sure?")){      
       if (action === "forward") {            
           actionCaption = "Pay-Order generation & Forwarding";         
           actionCaption+= " to Asstt.Registrar(A/c) ";
           $("#remark_td").hide(); 
       }                     
      else if (action === "modify") {
             actionCaption = "Pay-Order Modification";           
            $("#remark_td" ).show(); 
       }   
              //ajax call                   
       $('#fellowship_payorder_acc_da_form').validate({
      rules: {
     
         payorder_remark: {
            minlength: 5,
            maxlength: 200,
         
           required:  function (element) {
                        if (action=='forward')
                          {
                           return false;
                         } else {
                           return true;
                       } 
                     } 
        },
        
          messages: {
          
       
             payorder_remark: {
                required: "Please put reason against the rejection",
                minlength: "Your #reason must be at least 5 characters long.",
                maxlength: "Your reason must be  max. 200 characters long."
            },
          
        },
        invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
            /*  setTimeout(function () {
                    $("#msg").hide();
                }, 5000);*/
            }
        }    
   });
       if ($("#fellowship_payorder_acc_da_form").valid()) {      
       var postdata = { action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr,ref_no:$("#payorder_ref_no").val(),ref_no_date:$("#ref_no_date").val(),payment_mode:$("#pm").val(),debit_head_type:$("#dh").val(),slot:$("#slot").val(),remark:$("#payorder_remark").val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/payorder_forward_by_acc_da5'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "forward") { 
                                       actionCaption = "Pay-Order generation & Forwarding";         
                                       actionCaption+= " to Asstt.Registrar(A/c) ";
                                    $("#gen_payorder").hide();   
                                    $("#remark_td" ).hide(); 
                                    $("#status_label_final" ).show();
                                    $("#status_label_final" ).removeClass().addClass('label label-success');
                                    $("#status_label_final" ).text(" Generated & Forwarded ");  
                                    $("#pm").attr("disabled",true);
                                    $("#dh").attr("disabled",true);
                                    $("#payorder_ref_no").attr("disabled",true);
                                                                                      
                                  d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label_final").text(" on ");
                                $("#dt_label_final").append(timestamp); 
                                $("#dt_label_final").append(" By Dealing Asstt.(A/c) ");
                               } 
                               else if (jsonObj.action === "modify") {  
                                    actionCaption = "Pay-Order Modification";   
                                    $("#gen_payorder").hide();     
                                    $("#gen_payorder_edit").hide();     
                                    $("#clr").show();     
                                    $("#remark_td").hide(); 
                                    $("#status_label_final" ).show();
                                    $("#status_label_final" ).removeClass().addClass('label label-success');
                                    $("#status_label_final" ).text(" Modified & Forwarded ");  
                                    $("#pm").attr("disabled",true);
                                    $("#dh").attr("disabled",true);
                                    $("#payorder_ref_no").attr("disabled",true);      
                                        d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label_final").text(" on ");
                                $("#dt_label_final").append(timestamp); 
                                $("#dt_label_final").append(" By Dealing Asstt.(A/c) ");
                               }     
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for all fellows listed below." );
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for all fellows listed below. ,<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
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
 
 
 
 function  payorder_approval(target_degree,mon,yr,action){  
    if (confirm("Are you Sure?")){      
       if (action === "approve") {  
             actionCaption = "Pay-Order Approval";                    
            $("#remark_td" ).hide(); 
       }                     
      else if (action === "reject") {            
           actionCaption = "Pay-Order Rejection";
             $("#remark_td" ).show(); 
            }             
       
          
              //ajax call                   
       $('#fellowship_payorder_acc_ar_ga_form').validate({
      rules: {
    
         payorder_remark: {
            minlength: 5,
            maxlength: 200,
         
           required:  function (element) {
                        if (action === "approve")
                          {
                           return false;
                         } else {
                           return true;
                       } 
                     } 
        },
        
          messages: {
          
     
             payorder_remark: {
                required: "Please put reason against the rejection",
                minlength: "Your #reason must be at least 5 characters long.",
                maxlength: "Your reason must be  max. 200 characters long."
            },
          
        },
        invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
            /*  setTimeout(function () {
                    $("#msg").hide();
                }, 5000);*/
            }
        }    
   });
       if ($("#fellowship_payorder_acc_ar_ga_form").valid()) {      
       var postdata = { action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr,ref_no:$("#payorder_ref_no").val(),ref_no_date:$("#ref_no_date").val(),payment_mode:$("#pm").val(),debit_head_type:$("#dh").val(),slot:$("#slot").val(),remark:$("#payorder_remark").val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/payorder_approved_by_acc_ar_ga'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "approve") {  
                                     actionCaption = "Pay-Order Approval";           
                                    $("#gen_payorder").hide();                                        
                                    $("#reject").hide();   
                                     $("#remark_td").hide(); 
                                    $("#status_label_final" ).show();
                                    $("#status_label_final" ).removeClass().addClass('label label-success');
                                    $("#status_label_final" ).text(" Approved ");  
                                    $("#pm").attr("disabled",true);
                                    $("#dh").attr("disabled",true);
                                    $("#payorder_ref_no").attr("disabled",true);
                                                                                      
                                  d = new Date();
                                  var timestamp = d.timestamp();                                   
                                $("#dt_label_final").text(" on ");
                                $("#dt_label_final").append(timestamp); 
                                $("#dt_label_final").append(" By Asstt. Registrar(A/c)"); 
                               } 
                               else if (jsonObj.action === "reject") {     
                                      var actionCaption = "Pay-Order Rejection";
                                    $("#gen_payorder").show();   
                                    $("#reject").show();   
                                    $("#remark_td").hide(); 
                                    $("#status_label_final_next").show();
                                    $("#status_label_final_next").removeClass().addClass('label label-danger');
                                    $("#status_label_final_next").text(" Rejected ");  
                                    $("#pm").attr("disabled",true);
                                    $("#dh").attr("disabled",true);
                                    $("#payorder_ref_no").attr("disabled",true);
                                                                                      
                                  d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label_final_next").text(" on ");
                                $("#dt_label_final_next").append(timestamp);                                                                                                            
                                $("#dt_label_final_next").append(" By Asstt. Registrar(A/c)");
                               }     
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for all fellows listed below." );
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for all fellows listed below. ,<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
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
 
  
  function  payorder_append(target_degree,mon,yr,action){  
    if (confirm("Are you Sure?")){      
       if (action === "append") {            
           actionCaption = "Pay-Order Filling";                    
           $("#remark_td").hide(); 
       }                     
      else if (action === "modify") {
             actionCaption = "Pay-Order Modification";           
            $("#remark_td" ).show(); 
       }   
              //ajax call                   
       $('#fellowship_payorder_acc_da4_form').validate({
      rules: {
        payorder_ref_no: {
            minlength: 2,
            maxlength: 50,
            required: true             
           },
          ref_no_date: {            
            required: true             
           },
         payorder_remark: {
            minlength: 5,
            maxlength: 200,
         
           required:  function (element) {
                        if (action === "append")
                          {
                           return false;
                         } else {
                           return true;
                       } 
                     } 
        },
        
          messages: {
          
           payorder_ref_no: {
                required: "Please provide #Ref. No. against the payment",
                minlength: "Your #Ref. No. must be at least 2 characters long.",
                maxlength: "Your #Ref. No. must be  max. 50 characters long."
            },
              ref_no_date: {
                required: "Please provide #Ref. No. Date  against the payment",               
            },
             payorder_remark: {
                required: "Please put reason against the rejection",
                minlength: "Your #reason must be at least 5 characters long.",
                maxlength: "Your reason must be  max. 200 characters long."
            },
          
        },
        invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
            /*  setTimeout(function () {
                    $("#msg").hide();
                }, 5000);*/
            }
        }    
   });
       if ($("#fellowship_payorder_acc_da4_form").valid()) {      
       var postdata = { action: action, target_degree: target_degree, processing_mon: mon,processing_yr: yr,ref_no:$("#payorder_ref_no").val(),ref_no_date:$("#ref_no_date").val(),payment_mode:$("#pm").val(),debit_head_type:$("#dh").val(),slot:$("#slot").val(),remark:$("#payorder_remark").val()};
              $.ajax({
                        url: site_url('fellowship/fellowshipProcess/payorder_append_by_acc_da4'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();                        
                            if (jsonObj.result === "Successfully") {                                                                                                 
                                if(jsonObj.action === "append") { 
                                       actionCaption = "Pay-Order Filling";                                               
                                    $("#gen_payorder").hide();   
                                    $("#remark_td" ).hide(); 
                                    $("#status_label_final_next_next" ).show();
                                    $("#status_label_final_next_next" ).removeClass().addClass('label label-success');
                                    $("#status_label_final_next_next" ).text(" Filled ");  
                                    $("#pm").attr("disabled",true);
                                    $("#dh").attr("disabled",true);
                                    $("#payorder_ref_no").attr("disabled",true);
                                                                                      
                                  d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label_final_next_next").text(" on ");
                                $("#dt_label_final_next_next").append(timestamp); 
                                $("#dt_label_final_next_next").append(" By Dealing Asstt.(Cash) ");                                
                               } 
                               else if (jsonObj.action === "modify") {  
                                    actionCaption = "Pay-Order Modification";   
                                    $("#gen_payorder").hide();     
                                    $("#gen_payorder_edit").hide();     
                                    $("#clr").show();     
                                    $("#remark_td").hide(); 
                                    $("#status_label_final_next_next" ).show();
                                    $("#status_label_final_next_next" ).removeClass().addClass('label label-success');
                                    $("#status_label_final_next_next" ).text(" Modified & Filled ");  
                                    $("#pm").attr("disabled",true);
                                    $("#dh").attr("disabled",true);
                                    $("#payorder_ref_no").attr("disabled",true);      
                                        d = new Date();
                                   var timestamp = d.timestamp();                                   
                                $("#dt_label_final_next_next").text(" on ");
                                $("#dt_label_final_next_next").append(timestamp); 
                                $("#dt_label_final_next_next").append(" By Dealing Asstt.(Cash) ");                                
                               }     
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for all fellows listed below." );
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for all fellows listed below. ,<p>"+ jsonObj.error +"</p>");
                            }
                            setTimeout(function () {
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
 
 
 
 
 function clear_payorder() {    
     if (confirm("Are you Sure?")) {                                  
             $("#dh").attr("disabled",false);             
             $("#clr").hide();
             $("#gen_payorder_edit").show();             
     }
}
function clear_payorder_by_acc_da4() {    
     if (confirm("Are you Sure?")) {                     
             $("#pm").attr("disabled",false);             
             $("#payorder_ref_no").attr("disabled",false);                                                                                      
             $("#payorder_ref_no").val("");
             $("#clr").hide();
             $("#gen_payorder_edit").show();             
     }
}

function getpayorder_print(name,ref_no,ref_no_date,debit_head_name,acc_da_dt,acc_ar_dt,c_da_dt,payorder_fwd_by_acc_da,payorder_appd_by_acc_ar,payorder_append_by_c_da,bill_amt,txt_amt){
    switch (payorder_append_by_c_da) {
        case "N":
            switch (payorder_appd_by_acc_ar) {
                case "N":
                    switch (payorder_fwd_by_acc_da) {
                        case "Y":
                            prev_on = " On ";
                            prev_by = " By Dealing Asstt.(A/c) ";
                            status_final_prev = "Generated & Forwarded";
                            status_label_final_prev = "label label-success";
                            next_on = "";
                            next_by = "";
                            status_final_next = "";
                            status_label_final_next = "";
                            on = "";
                            by = "";
                            status_final = "";
                            status_label_final = "";
                            break;
                    }
                    break;
                case "Y" :
                    switch (payorder_fwd_by_acc_da) {
                        case "Y":
                            prev_on = " On ";
                            prev_by = " By Dealing Asstt.(A/c) ";
                            status_final_prev = "Generated & Forwarded";
                            status_label_final_prev = "label label-success";
                            next_on = "";
                            next_by = "";
                            status_final_next = "";
                            status_label_final_next = "";
                            on = " On ";
                            by = " By Asstt. Registrar(A/c) ";
                            status_final = "Approved";
                            status_label_final = "label label-success";

                            break;
                    }
                    break;


            }
            break;
        case "Y":
            switch (payorder_appd_by_acc_ar) {
                case "N":
                    switch (payorder_fwd_by_acc_da) {
                        case "Y":
                            prev_on = " On ";
                            prev_by = " By Dealing Asstt.(A/c) ";
                            status_final_prev = "Generated & Forwarded";
                            status_label_final_prev = "label label-success";
                            next_on = "";
                            next_by = "";
                            status_final_next = "";
                            status_label_final_next = "";
                            on = "";
                            by = "";
                            status_final = "";
                            status_label_final = "";
                            break;
                    }
                    break;
                case "Y" :
                    switch (payorder_fwd_by_acc_da) {
                        case "Y":
                            prev_on = " On ";
                            prev_by = " By Dealing Asstt.(A/c) ";
                            status_final_prev = "Generated & Forwarded";
                            status_label_final_prev = "label label-success";
                            next_on = " On ";
                            next_by = " By Dealing Asst.(Cash Section (A/c))";
                            status_final_next = "Processed";
                            status_label_final_next = "label label-success";
                            on = " On ";
                            by = " By Asstt. Registrar(A/c) ";
                            status_final = "Approved";
                            status_label_final = "label label-success";

                            break;
                    }
                    break;
            }
            break;
    }
      Date.prototype.timestamp = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            var h = this.getHours().toString();
            var m = this.getMinutes().toString();
            var s = this.getSeconds().toString();
            return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h-12 : h) + ":" + (m[1]?m:"0"+m[0]) + ":" + (s[1]?s:"0"+s[0]) +" "+((h > 12) ? "PM" : "AM");
        };    
            d = new Date();
            var timestamp = d.timestamp();      

 var resultHtml =                   
                  "<div> <b>Debit Head:</b>"+debit_head_name+" </div>"+
                  "<fieldset style='border:solid 1px;'>"+
                  "<div align ='center'><u>PAY ORDER</u></div>"+
                  "<hr style='border:solid 1px;'></hr><br/><br/>"+
                  "<div align='center'>Pass for Payment of <b>(&#8377)"+ bill_amt + "/-(" +txt_amt+ ")</b></div><br/><br/>"+                  
                      "<div>"+
                         "<span id='status_label_final' class='" + status_label_final_prev + "'  >" + status_final_prev + " </span>"+
                         "<span id='dt_label_final' class='btn-xs'> " +prev_on+ "" +acc_da_dt+""+prev_by+ "</span>"+
                    "</div>"+                  
                 "<div  ><br/>"+
                 "<span id='status_label_final_next' class='"+  status_label_final +"' >" + status_final + "</span>"+
                     "<span  id='dt_label_final_next' class='btn-xs'> "+on+ "" +acc_ar_dt+ "" +by+ " </span>"+
                 "</div><br/>"+ 
                 "<div>"+
                         "<span id='status_label_final' class='" + status_label_final_next + "'  >" + status_final_next + " </span>"+
                         "<span id='dt_label_final_' class='btn-xs'> " +next_on+ "" +c_da_dt+""+next_by+ "</span>"+
                    "</div>"+                  
                 "<div  ><br/>"+
              "<hr style='border:solid 1px;'></hr><div align ='center'><u>Cash Section</u></div>"+      
              "<div><b>Payment released vide:</b>&nbsp;<span id='ref_caption'><b>"+ name +"&nbsp; No.</span>:</b><span id='ref_val'><u> "+ref_no+"</u></span>&nbsp;<span id='dt'>,&nbsp;<b>Dated:</b><u>"+ref_no_date+"</u></span></div>"+
             "</fieldset>";
             
            $('#payorder_view_Header').text('PAY-ORDER');
            $('#payorder_view_Content').html(resultHtml);
            

}



 function getPayorder_action_log(target_degree,mon,yr,slot,actor) {
     var actor_desig="" ,action="";
      if( actor=='acc_da') {      
          actor_desig=" Dealing Asstt.(A/c) ";         
          action="R=>Rejected , N => Pending , Y => Forwarded ,  N/A => Not Applicable"; 
      }  
      else if( actor=='acc_ar_ga') {      
          actor_desig=" Asstt. Registrar(A/c) ";        
          
           action="R=>Rejected , N => Pending , Y => Approved ,  N/A => Not Applicable"; 
      }  
      
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/getPayorder_action_log'),
        type: "POST",
        dataType: "json",
        data: {target_degree: target_degree,processing_mon: mon,processing_yr: yr,slot:slot,actor:actor},
        success: function (jsonObj) {

            var resultHtml = "<div class='panel panel-info'>" +
                    "<div class='panel-heading'><i class='fa fa-history'></i>&nbsp;Action Log(Action taken by "+actor_desig+")</div>" +
                    "<div class='table-responsive'><table class='table table-condensed'  id ='printview'>" +
                    "<thead>" +
                    "<tr>" +
                    "<th>Sl. No.</th>" +
                    "<th>Date</th>" +
                    "<th>Processing<p>Month & Year</p></th>" + 
                    "<th>Amt</th>" +                    
                    "<th>Action status</th>" +
                    "<th>Action Date</th>" +
                    "<th>payemnt_mode</th>" +                                                                      
                    "<th>Ref: No.</th>" +                                                                      
                    "<th>Debit Head</th>" +                                                                      
                    "<th>Slot</th>" +                                                                                
                    "<th>Remark</th>" +
                    "</tr>" +
                    "</thead>" +
                    "<tbody>";

            if (jsonObj.result != 'Failed') {
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                   
           action_status= jsonObj[i].status != "" ? jsonObj[i].status == "Y" ? "<b style='color:green'>" + jsonObj[i].status + "</b>" : jsonObj[i].status == "R" ? "<b style='color:red'>" + jsonObj[i].status + "</b>" : "<b style='color:orange'>" + jsonObj[i].status + "</b>" : "N/A";
            action_time=jsonObj[i].action_dt != null ? jsonObj[i].action_dt : "N/A";
      
    
                        resultHtml += "<tr>" +
                                "<td>" + Number((i + 1)) + "</td>" +
                                "<td>" + (jsonObj[i].created != null ? jsonObj[i].created : "N/A") + "</td>" +
                                "<td>" +  jsonObj[i].processing_mon+ "/"+jsonObj[i].processing_yr+ "</td>" +
                                "<td>" + (jsonObj[i].bill_amt!=null?jsonObj[i].bill_amt:"0.0")+"</td>"+
                                "<td>" + action_status+"</td>"+
                                "<td>" + action_time + "</td>" +     
                                "<td>" + jsonObj[i].name+"</td>"+
                                "<td>" + jsonObj[i].name+ " No.- " + jsonObj[i].ref_no+"<p>Dated : " + (jsonObj[i].ref_no_date != null ? jsonObj[i].ref_no_date : "N/A" )+"</td>"+
                                "<td>" + jsonObj[i].debit_head_name+"</td>"+
                                "<td>" + jsonObj[i].slot+"</td>"+
                                "<td>" + (jsonObj[i].remark != "" ? jsonObj[i].remark: "N/A") + "</td>" +                                                                
                                "</tr>";
                    }
                     resultHtml += "</table>";
                      resultHtml += "</tbody>"+
                                  "</div"+
                                "</div>"+
                                "<div><i class='fa fa-hand-o-right'></i> &nbsp;"+action+"</div>";
                 }
                       
            }
            else {
                if (jsonObj.error === "No Record Found") {
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<div  class='" + pclass + "'><a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div>";
            }
            
            $('#payorder_log_Header').text('Action Logs');
            $('#payorder_log_Content').html(resultHtml);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    });

}   



 function get_fellows_list_slotwise(target_degree,mon,yr,slot,actor) {     
        
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/get_eligible_fellow_bill_list_slotwise'),
        type: "POST",
        dataType: "jsonp",
        data: {target_degree: target_degree,processing_mon: mon,processing_yr: yr,slot:slot,actor:actor},
        success: function (jsonObj) {
           

            var resultHtml = "<div class='panel panel-info'>" +
                    "<div class='panel-heading'><i class='fa fa-users'></i>&nbsp;Fellows List(Billing Processing done for month of -"+mon+", Yr-"+yr+", slot-"+slot+")</div>" +
                    "<div class='table-responsive'><table class='table table-condensed'  id ='printview'>" +
                    "<thead>" +
                    "<tr>" +
                    "<th>Sl. No.</th>" +
                    "<th>Regd. No.</th>" +
                    "<th>Name of Reaserch Scholar</p></th>" + 
                    "<th>Department</th>" +                    
                    "<th>SBI Bank Account No.</th>" +
                    "<th>Net Payable Amt.(&#8377)</th>" +                    
                    "</tr>" +
                    "</thead>" +
                    "<tbody>";

            if (jsonObj.result != 'Failed') {
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {                                 
                        resultHtml += "<tr>" +
                                "<td>" + Number((i + 1)) + "</td>" +
                                "<td>" + (jsonObj[i].stud_reg_no != null ? jsonObj[i].stud_reg_no : "N/A") + "</td>" +
                                "<td>" +  jsonObj[i].fellow_name+ "</td>" +                                
                                "<td>" +  jsonObj[i].dept_id+ "</td>" +
                                "<td>" +  jsonObj[i].account_no+ "</td>" +                                
                                "<td>" + (jsonObj[i].net_amt_payable!=null?jsonObj[i].net_amt_payable:"0.0")+"</td>"+                             
                                "</tr>";
                    }
                     resultHtml += "</table>";
                      resultHtml += "</tbody>"+
                                  "</div"+
                                "</div>";                                
                 }
                       
            }
            else {
                if (jsonObj.error === "No Record Found") {
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<div  class='" + pclass + "'><a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div>";
            }
            
            $('#fellow_slot_Header').text('Fellow List');
            $('#fellow_slot_Content').html(resultHtml);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    });

}   