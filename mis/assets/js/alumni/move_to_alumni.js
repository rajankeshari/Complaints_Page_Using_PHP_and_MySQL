/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


 /* moving data to alumini using Ajax Call*/
    function move_data(passing_yr, div_id) {
        //   var divIdHtml = $("#"+div_id).html();
        str = "'";
        var admn_list = new Array();
        var i = 0;
        $('input.apprv:checkbox:checked').each(function () {
            admn_list[i++] = $(this).val();
        });
        // alert(passing_yr);
        //alert(admn_list.toString() );       
        $.ajax({
            //url: site_url("alumni/move_to_alumni/move_data/"+str+ admn_list.toString()+str+"/" + passing_yr),
            url: site_url("alumni/move_to_alumni/move_data/"),
            type: "POST",
            //  async: false ,
            dataType: "json",
            data: {passing_yr: passing_yr, admn_list: admn_list.toString()},
            beforeSend: function () {
                $("#loading-image").show();
            },
            success: function (jsonObj) {
                //  alert(jsonObj.list);
                //  $("#"+div_id).html(divIdHtml);
                $("#loading-image").hide();
                $("#msg").show();
                $("#msg").html("");
                $("#msg1").show();
                $("#msg1").html("");
                d = new Date();
                var timestamp = d.timestamp();
                window.location = site_url(jsonObj.link);
                if (jsonObj.list != '' && jsonObj.list != null && jsonObj.list != 'No_record_selected') {
                    $("#msg").removeClass().addClass("alert alert-success");
                    $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>Alumni making done sucessfully </strong>for candidates[" + jsonObj.list + "] ");
                    $("#msg").append(" on ");
                    $("#msg").append(timestamp);
                    $("#msg").append(" By Admin ");
                    $("#msg").show();
                }
                if (jsonObj.prob_list != '' && jsonObj.prob_list != null) {
                    $("#msg1").removeClass().addClass("alert alert-danger");
                    $("#msg1").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>Alumni making failed </strong>for candidates[" + (jsonObj.prob_list == 'No_record_selected' ? 'due not selecting  any candiadte' : jsonObj.prob_list) + "] ");
                    $("#msg1").append(" on ");
                    $("#msg1").append(timestamp);
                    $("#msg1").append(" By Admin ");
                    $("#msg1").show();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.statusText);
                alert(thrownError);
            },
        });
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
    /*end*/
    /* Selecting all checkbox using single check*/
    jQuery(document).ready(function ($) {
        $('input.all').on('ifToggled', function (event) {
            var chkToggle;
            $(this).is(':checked') ? chkToggle = "check" : chkToggle = "uncheck";
            $('input.selector:not(.all)').iCheck(chkToggle);
        });
    });
    /*end*/