/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {
    //$('#to').datepicker( "setDate", new Date());$('#from').datepicker( "setDate", new Date());  // load  current Date
    $('#to').datepicker();
    $('#from').datepicker();
    $('#po_show').dataTable();
    $('#submit').click(function () {

    });
    $('#print_btn').click(function () {
        edept = $("#dept").val();
        //alert(edept);alert(edesig);alert(efaculty);alert(ecategory);alert(edtfrom);alert(edtto);alert(estate);alert(enwork);alert(egender);
        $.ajax({
            url: site_url('employee/emp_mgt/print_data'),
            type: "POST",
            data: {"seldept": edept, "from": $("#from").val(), "to": $("#to").val()},
            success: function (data)
            {
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Report_" + new Date() + ".pdf";
                link.click();
            }
        });
    });

    /* var divContents = $("#dvContainer").html();
     var printWindow = window.open('', '', 'height=400,width=800');
     printWindow.document.write('<html><head><title>Employee Details</title>');
     printWindow.document.write('</head><body >');
     printWindow.document.write(divContents);
     printWindow.document.write('</body></html>');
     printWindow.document.close();
     printWindow.print();*/


});
function my_fun_view(id)
{
    //alert("Dear User Please Remember Employee Id "+id);
    window.location = site_url('employee/emp_mgt/load_view/' + id);

}

// Handling Action  taken by admin to initiate retirement 
function retirement_activation(emp_id,emp_name){
   if (confirm("Are you sure to activate retirement to"  +emp_name +"?")) {                  
            $.ajax({
                url: site_url('employee/emp_mgt/retirement_activation'),
                type: "POST",
                dataType: "json",
                data:{emp_id: emp_id},
                success: function (jsonObj) {                    
                    var actionCaption = "Retirement Activated";
                    if (jsonObj.result === "Successfully") {                                                
                            $("#p_row_" + emp_id).removeClass().addClass("alert alert-success");                          
                            $('#forward_butt_cont' + id).css({'display': 'none'});
                        }
                        d = new Date();
                        var timestamp = d.timestamp();
                        $("#appv_dt_label" + id).text(timestamp);
                        $("#msg").removeClass().addClass("alert alert-success");
                        $("input[name=fac_radio_" + id + "]").prop('disabled', true);
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.statusText);
                    alert(thrownError);
                },
            })
            Date.prototype.timestamp = function () {
                var yyyy = this.getFullYear().toString();
                var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
                var dd = this.getDate().toString();
                var h = this.getHours().toString();
                var m = this.getMinutes().toString();
                var s = this.getSeconds().toString();
                return (dd[1] ? dd : "0" + dd[0]) + "/" + (mm[1] ? mm : "0" + mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h - 12 : h) + ":" + (m[1] ? m : "0" + m[0]) + ":" + (s[1] ? s : "0" + s[0]) + " " + ((h > 12) ? "PM" : "AM");
            };
        }
        }




//$(function () {});

