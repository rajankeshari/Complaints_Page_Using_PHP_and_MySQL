$(document).ready(function(){

    var isValidFormData = function () {
      var type = $("select [name='type']").val();
      var departmentName = $('#department_name').val();
      var designation = $('#designation').val();
      var empName = $('#emp_name').val();
      var leaveStartDate = $('#leave_start_date').val();
      var leaveEndDate = $('#leave_end_date').val();

      if (type === 'Select' || departmentName === 'Select' || designation === 'Select' || empName === 'Select') {
        //alert("Fields can't be set to 'Select'");
        return false;
      }

      if (leaveStartDate === '' || leaveEndDate === '') {
          //alert('Date fields can not be empty');
          return false;
      }

      return true;
    };

    var getLeaveHistory = function () {
        $.ajax({
            url : site_url("leave/leave_ajax/get_leave_by_emp_id/"+$('#emp_name').val()+"/"+$('#leave_start_date').val()+"/"+$('#leave_end_date').val()),
            success : function (result) {
                $('#leave_details').html(result);
            }
        });
    };
         /* commmented  and new  module below as per req.  28/9/15.   Requirement needs change of type selection (academic/nonacademic/other) & on change it should load  hos/hos/other higer deginitories  of  whole ISM*/
	/*$("select[name='type']").on('change', function() {
		$.ajax({url : site_url("leave/leave_ajax/get_dept/"+this.value),
				success : function (result) {
					$('#department_name').html(result);
				}});
	});
        */
       
        $("select[name='type']").on('change', function() {
		$.ajax({url : site_url("leave/leave_ajax/get_hod_hos/"+this.value+"/"+( $('#req_emp_id').val()==""?null:$('#req_emp_id').val())),
				success : function (result) {
					$('#emp_name').html(result);
				}});
	});
        

	$('#department_name').on('change', function() {
		$.ajax({url : site_url("leave/leave_ajax/get_designation/"+this.value),
				success : function (result) {
					$('#designation').html(result);
				}});
	});

	$('#designation').on('change', function() {
		$.ajax({url : site_url("leave/leave_ajax/get_emp_name/"+$(this).val()+"/"+$('#department_name').val()),
				success : function (result) {
					$('#emp_name').html(result);
				}});	
	});

    $('#emp_name,#leave_start_date,#leave_end_date').on('change', function () {
        if (isValidFormData()) {
            getLeaveHistory();
        }
    });

    //$('#leave_start_date').on('change', function () {
     //   if (isValidFormData()) {
     //       getLeaveHistory();
     //   }
    //});
    //
    //$('#leave_end_date').on('change', function() {
     //   if (isValidFormData()) {
     //       getLeaveHistory();
     //   }
	//});
});
var casual_id,restricted_id,button_id ,earned_id, update_on,HPleave_id,vacation_id, update_by , current_emp_by;
function clickEventEdit(id , update , updated_by , emp_by , json_data){
    update_on = update;
    update_by = updated_by;
    current_emp_by = emp_by;
    casual_id = '#'+id+'_casual';
    restricted_id = '#'+id+'_restricted';
    earned_id = '#'+id+'_earned';
    vacation_id = '#'+id+'_vacation';
    HPleave_id = '#'+id+'_HPLeave';
    button_id = '#'+id+'_button';
    if($(button_id).val() == 'Edit') {
        $(casual_id).removeAttr('disabled');
        $(restricted_id).removeAttr('disabled');
        $(vacation_id).removeAttr('disabled');
        $(HPleave_id).removeAttr('disabled');
        if(update == '1')
            $(earned_id).removeAttr('disabled');
        $(button_id).html('Save').prop('value' , 'Save').removeClass('btn-warning').addClass('btn-primary');
    } else if($(button_id).val() == 'Save'){
        var valid = validateValue();
        if(valid === false) {
            var casual_bal = $(casual_id).val();
            var restricted_bal = $(restricted_id).val();
            var earned_bal = $(earned_id).val();
            var vacation_bal = $(vacation_id).val();
            var HPL_bal = $(HPleave_id).val();
            if(vacation_bal.length != 0)
                insertVacationLeaveBalance(id , vacation_bal , emp_by);
            if(HPL_bal.length !=0)
                insertHPLeaveBalance(id , HPL_bal , emp_by);
            if(restricted_bal.length == 0)
                restricted_bal = '0';
            if(casual_bal.length == 0)
                casual_bal = '0';
            insertLeaveBalance(id , casual_bal , restricted_bal);
            $(button_id).html('Edit').prop('value', 'Edit').removeClass('btn-primary').addClass('btn-warning');
            $(casual_id).attr('disabled', 'disabled');
            $(restricted_id).attr('disabled', 'disabled');
            $(vacation_id).attr('disabled' , 'disabled');
            $(HPleave_id).attr('disabled' , 'disabled');
            if(update == '1'){
                if(earned_bal.length != 0)
                    insertEarnedLeaveBalance(id ,earned_bal , emp_by);
                $(earned_id).attr('disabled' , 'disabled');
            }
            if(HPL_bal.length == 0)
                HPL_bal = '0';
            if(vacation_bal.length == 0)
                vacation_bal = '0';
            if(earned_bal.length == 0)
                earned_bal = '0';
            updateBalanceDetails(id, casual_bal , restricted_bal , earned_bal , vacation_bal , HPL_bal , json_data);
        } else{
            alert(valid);
        }
    }
}
function validateValue(){
    var casual_bal = $(casual_id).val();
    var restricted_bal = $(restricted_id).val();
    var earned_bal = $(earned_id).val();
    var vacation_bal = $(vacation_id).val();
    var err = "";
    if(casual_bal > 8){
        err = err+  "casual balance not valid\n";
    }
    if(restricted_bal > 2)
        err = err+ "Restricted balance not valid";
    if(earned_bal > 315 )
        err = err + " Earned balance not valid";
    if(vacation_bal > 60){
        err = err+" Vacation leave balance not valid."
    }
    if(err.length > 0){
        return err;
    }else
        return false;
}
function insertLeaveBalance(emp_id , casual , restricetd){
    $.ajax({
        type : "POST",
        url : site_url('leave/leave_admin/updateOrInsertLeaveBalance'),
        data : {
            emp_id : emp_id,
            casual : casual,
            restricted : restricetd
        }
    });
}

function insertEarnedLeaveBalance(emp_id , bal , emp_by){
    $.ajax({
        type : "POST",
        url : site_url('leave/leave_admin/updateOrInsertEarnedLeaveBalance'),
        data : {
            'emp_id' : emp_id,
            'balance' : bal,
            'emp_by' : emp_by
        }
    });
}
function insertVacationLeaveBalance(emp_id , bal , emp_by){
    $.ajax({
        type : "POST",
        url : site_url('leave/leave_admin/updateOrInsertVacationLeaveBalance'),
        data : {
            'emp_id' : emp_id,
            'balance' : bal,
            'emp_by' : emp_by
        }
    });
}
function insertHPLeaveBalance(emp_id , bal , emp_by){
    $.ajax({type : "POST", url : site_url('leave/leave_admin/updateOrInsertHPLeaveBalance'), data : {'emp_id' : emp_id, 'balance' : bal, 'emp_by' : emp_by}});
}
function updateBalanceDetails(emp_id , casual , restricted , earned , vaction , hpl , data){
    $.ajax({type : "POST" , url : site_url('leave/leave_employee/leaveBalanceUpdateDetails') , data : {'emp_id' : emp_id , 'casual' : casual , 'restricted' : restricted , 'earned' : earned , 'vacation':vaction , 'hpl':hpl , 'data':data}});
}
var edit_id , delete_id , start_date_id , end_date_id , reason_id;
function editDateVacation(id){
    edit_id = "#edit_btn_"+id;
    start_date_id = "#start_date_"+id;
    end_date_id = "#end_date_"+id;
    reason_id = "#reason_"+id;
    if($(edit_id).val() == 'Edit'){
        $(start_date_id).removeAttr('disabled');
        $(end_date_id).removeAttr('disabled');
        $(reason_id).removeAttr('disabled');
        $(edit_id).html('Save').prop('value' , 'Save').removeClass('btn-warning').addClass('btn-primary');
    } else if($(edit_id).val() == 'Save'){
        var start_date , end_date;
        start_date = $(start_date_id).val();
        end_date = $(end_date_id).val();
        if(dateCompare(start_date , end_date)){
            editvacationDate(id);
            $(reason_id).attr('disabled' , 'disabled');
            $(start_date_id).attr('disabled' , 'disabled');
            $(end_date_id).attr('disabled' , 'disabled');
            $(edit_id).html('Edit').prop('value', 'Edit').removeClass('btn-primary').addClass('btn-warning');
        } else{
            alert('Please select valid start date');
        }
    }
}

function deleteDateVacation(id){
    var check = confirm('Are you sure you want to delete this date');
    if(check){
        deleteVacationDate(id);
        $('#row_'+id).remove();
    }
}
function editvacationDate(id){
    $.ajax({
        type : "POST",
        url : site_url('leave/leave_admin/editVacationDates'),
        data : {
            'id' : id,
            'start_date' : $(start_date_id).val(),
            'end_date' : $(end_date_id).val(),
            'reason' : $(reason_id).val()
        }
    });
}
function deleteVacationDate(id){
    $.ajax({
        type : "POST",
        url : site_url('leave/leave_admin/deleteVacationDates'),
        data : {
            'id' : id
        }
    });
}
function dateCompare(start_date , end_date){
    start_date = start_date.split('-');
    end_date = end_date.split('-');
    start_date = new Date(start_date[2] , start_date[1]-1 , start_date[0]);
    end_date = new Date(end_date[2] , end_date[1]-1 , end_date[0]);
    if(start_date > end_date){
        return false;
    } else return true;
}
var date_id;
function editDateRestricted(id){
    edit_id = "#edit_btn_restricted_"+id;
    date_id = "#date_"+id;
    reason_id = "#reason_restricted_"+id;
    if($(edit_id).val() == 'Edit'){
        $(date_id).removeAttr('disabled');
        $(reason_id).removeAttr('disabled');
        $(edit_id).html('Save').prop('value' , 'Save').removeClass('btn-warning').addClass('btn-primary');
    } else if($(edit_id).val() == 'Save'){
        var start_date , end_date;
        start_date = $(date_id).val();
        editRestrictedDates(id);
        $(reason_id).attr('disabled' , 'disabled');
        $(date_id).attr('disabled' , 'disabled');
        $(edit_id).html('Edit').prop('value', 'Edit').removeClass('btn-primary').addClass('btn-warning');
    }
}
function deleteDateRestricted(id){
    var check = confirm('Are you sure you want to delete this date');
    if(check){
        deleteRestrictedDates(id);
        $('#row_restricted_'+id).remove();
    }
}
function editRestrictedDates(id){
    $.ajax({
        type : "POST",
        url : site_url('leave/leave_admin/editRestrictedDates'),
        data : {
            'id' : id,
            'start_date' : $(date_id).val(),
            'reason' : $(reason_id).val()
        }
    });
}
function deleteRestrictedDates(id){
    $.ajax({
        type : "POST",
        url : site_url('leave/leave_admin/deleteRestrictedDates'),
        data : {
            'id' : id
        }
    });
}