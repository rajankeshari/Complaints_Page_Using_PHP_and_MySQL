/**
 * edited and revised Prateek Kumar 2013JE0164
 * @param {type} param
 */
$(document).ready(function () {

    $add_course_form = $("#add_course_form");
    //$form_table = $("#form_table");
    $box_form = $("#box_form");
    $dept_selection = $('#dept_selection');
    $table_content = $('#refresh_add_list_courses');

    var year_option = "";
    var n = new Date().getFullYear();
    for (r = n; r >= 1926; r--) {
        year_option += "<option value=\"" + r + "_" + (r + 1) + "\">" + r + "-" + (r + 1) + "</option>";
    }

    $duration = 1;
    /**
     * this function runs ajax to bring all courses, branches, years and semester's range
     */
    add_course();
    function add_course() {
        if ($dept_selection.find(':selected').val() === 'prep') {
            alert("To add for Preparatory please visit CourseStructure->add course structure->Preparatory");
            return false;
        }
        $box_form.showLoading();
        $.ajax({url: site_url("course_structure/add/json_add_with_valid_range/" + $dept_selection.find(':selected').val()),
            success: function (data_res) {

                var loop_var;
                var table_ajax_content = "";
                /**
                 * color control vars
                 * @type String
                 */
                var pre_col = 'some_unknow_value';
                var is_white = false;
                var data = data_res[0];
                for (loop_var = 0; loop_var < data.length; loop_var++) {
                    /**
                     * all range
                     * @type String
                     */
                    var all_range = "<select class='form-control' id='select_all_" + loop_var + "'>";
                    all_range += year_option;
                    /**
                     * existing range
                     * @type String
                     */
                    var exist_range = "";
                    for (loop_y = 0; loop_y < data_res[1].length; loop_y++) {
                        for (loop_y_y = 0; loop_y_y < data_res[1][loop_y].length; loop_y_y++) {
                            if (data_res[1][loop_y][loop_y_y]['id_of_course'] === data[loop_var]['course_id'] && data_res[1][loop_y][loop_y_y]['id_of_branch'] === data[loop_var]['branch_id']) {
                                exist_range += "" + data_res[1][loop_y][loop_y_y]['start_yr'] + "-" + data_res[1][loop_y][loop_y_y]['end_yr'] + "<br/>";
                            }
                        }
                    }
                    /*
                     * sem content
                     * @type String
                     */
                    var semester_options = "<select class='form-control' id='select_alternative_" + loop_var + "'>";
                    semester_options +="<option value='$' disabled selected>Select</option>"
                    if (data[loop_var]['course_id'] === "honour" || data[loop_var]['course_id'] === "minor")
                    {
                        for (counter = 5; counter <= 8; counter++) {
                            semester_options += "<option value=\"" + counter + "\">" + counter + "</option>";
                        }
                    }
                    else if (data[loop_var]['course_id'] === 'prep') {
                        semester_options += "<option value=\"-1\">1</option>";
                        semester_options += "<option value=\"0\">2</option>";
                    }
                    else
                    {

							if (data[loop_var]['duration'] < 4) {

									for (counter = 1; counter <= 2 * data[loop_var]['duration']; counter++) {
										semester_options += "<option value=\"" + counter + "\">" + counter + "</option>";
									}
								}
								else {
									
									if (data[loop_var]['id']=='pe' && data[loop_var]['course_id']=='be' ){
										 for (counter = 1; counter <= 2 * data[loop_var]['duration']; counter++) {
										semester_options += "<option value=\"" + counter + "\">" + counter + "</option>";
									}
										
									}else{

									for (counter = 3; counter <= 2 * data[loop_var]['duration']; counter++) {
										semester_options += "<option value=\"" + counter + "\">" + counter + "</option>";
									}
								}

								}
                      /*  if (data[loop_var]['duration'] < 4) {

                            for (counter = 1; counter <= 2 * data[loop_var]['duration']; counter++) {
                                semester_options += "<option value=\"" + counter + "\">" + counter + "</option>";
                            }
                        }
                        else {

                            for (counter = 3; counter <= 2 * data[loop_var]['duration']; counter++) {
                                semester_options += "<option value=\"" + counter + "\">" + counter + "</option>";
                            }

                        }*/
                    }
                    semester_options += "</select>";
                    /*
                     * other content
                     */
                    if (pre_col !== data[loop_var]['course_id']) {
                        pre_col = data[loop_var]['course_id'];
                        is_white = !is_white;
                    }
                    if (is_white === true) {
                        table_ajax_content += "<tr style='background-color: lightgrey;'>";
                    } else {
                        table_ajax_content += "<tr style='background-color: white;'>";
                    }
                    //table courses
                    table_ajax_content += "<td>" + data[loop_var]['course_name'] + "</td>";
                    //table branches
                    table_ajax_content += "<td>" + data[loop_var]['branch_name'] + "</td>";
                    //existing range
                    table_ajax_content += "<td>" + exist_range + "</td>";
                    //all years
                    table_ajax_content += "<td>" + all_range + "</td>";
                    //semester options
                    var d_id = data[loop_var]['id'];//dept_id
                    var c_id = data[loop_var]['course_id'];//course_id
                    var b_id = data[loop_var]['branch_id'];//branch_id
//                    
                    table_ajax_content += "<td><div class='input-group col-md-12'>" + semester_options + "<div class='input-group-btn'><input type='submit' value='Add' class=' btn btn-success' onclick=action_course_structure('" + d_id + "','" + c_id + "','" + b_id + "','" + loop_var + "')></input></div></div></td>";
                    table_ajax_content += "</tr>";

                }

                $table_content.html(table_ajax_content);
                $box_form.hideLoading();
            },
            type: "POST",
            //data :JSON.stringify({course:$course_selection.find(':selected').val()}),
            dataType: "json",
            fail: function (error) {
                console.log(error);
                $box_form.hideLoading();
            }
        });
    }
    //
    //
    //

    $dept_selection.change(function () {
        add_course();
    });



});

function action_course_structure(dept_id, course_id, branch_id, loop_var) {
    /**
     * value Semester
     * 
     * @type @exp;document@call;getElementById|@exp;document@call;getElementById
     */
    var e = document.getElementById("select_alternative_" + loop_var);
    var strSEM = e.options[e.selectedIndex].value;
    if(strSEM === '$'){
        alert('Select Semester');
        return false;
    }
    /**
     * value Session
     * @type @exp;document@call;getElementById|@exp;document@call;getElementById|@exp;document@call;getElementById|@exp;document@call;getElementById
     */
    var e = document.getElementById("select_all_" + loop_var);
    var strALL = e.options[e.selectedIndex].value;
    
    var url = 'add/EnterNumberOfSubjects';
    
    var form = $('<form action="' + url + '" method="post">' +
            '<input type="hidden" name="dept" value="' + dept_id + '" />' +
            '<input type="hidden" name="course" value="' + course_id + '" />' +
            '<input type="hidden" name="branch" value="' + branch_id + '" />' +
            '<input type="hidden" name="session" value="' + strALL + '" />' +
            '<input type="hidden" name="sem" value="' + strSEM + '" />' +
            '</form>');
    $('body').append(form);
    form.submit();

}