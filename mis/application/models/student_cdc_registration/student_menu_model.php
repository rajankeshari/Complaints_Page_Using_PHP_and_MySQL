<?php

class Student_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		//auth ==> deo
		$menu['acad_da1']=array();
		$menu['acad_da1']['Manage Students']=array();
		$menu['dsw']=array();
		//$menu['acad_da1']["Manage Students"]["Add Student"] = site_url('student/student_add_deo');
		//$menu['acad_da1']["Manage Students"]["Quick Add Student"] = site_url('student/student_add_deo_short');
		$menu['acad_da1']['Manage Students']["Edit Student Details"] = site_url('student/student_edit');
		$menu['acad_da1']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		//$menu['acad_da1']["Manage Students"]["View Student"]["Specific"] = site_url('student/view');
		$menu['acad_da1']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['dsw']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['acad_da1']["Manage Students"]["View Rejected Students"] = site_url('student/student_rejected');
		$menu['acad_da1']["Manage Students"]["View Rejected Students Specific"] = site_url('student/validate');
		//$menu['exam_dr']["Manage Students"]["Quick Add Student"] = site_url('student/student_add_deo_short');
		//auth ==> emp
		$menu['emp']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		//$menu['emp']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');


		/* Testing below with separate student report auth which is stu_report for generating report only		*/
		$menu['stu_report']=array();
		$menu['stu_report']['Manage Students']=array();
		$menu['stu_report']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['stu_report']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');
		$menu['stu']["Course Weightage & Marks View"] = site_url('student/student_subject_weightage');
		/* upto this line*/



		//$auth ==> stu
		$menu['stu']=array();
		//$menu['stu']['Edit Your Details'] = site_url('student/student_editable_by_student');
    //    $menu['stu']['Edit Your Details'] = site_url('student/edit_self_details'); // student edit change on 21 Sep 2016 /// blocked on 21-7-2017- due category chnage issue open on 02-28-2017
            //    $menu['stu']['Edit Your Details'] = site_url('student/stu_edit_notice');  // to be opened
		$menu['stu']["View Your Details"] = site_url('student_view_report/view');

//menu for placement registration
	   $menu['stu']["CDC11 Registration"] = site_url('student_cdc_registration/placement_view');



		//$menu['stu']["Grade Sheet"] = site_url('student_grade_sheet/grade_sheet');//@18-7-18 as per direction CLOSED
	//	$menu['stu']["Grade Sheet"] = site_url('student_grade_sheet/grade_sheet_final_foil'); //TO BE OPENED NEW

		$menu['stu']["Set/Change Security Questions"] = site_url('sec_inside/sec_inside_controller/change_security_questions');
        $menu['stu']["Edit Hindi Name"] = site_url('student/student_ctl');
        //$menu['stu']["Hostel Information"] = site_url('student/student_ctl/stu_hostel_info');
		//$auth ==> est_ar
		$menu['acad_ar']=array();
		$menu['acad_ar']['Student Details']=array();
		//$menu['acad_ar']["Manage Students"]["Add Student"] = site_url('student/student_add_deo');
		//$menu['acad_ar']["Manage Students"]["Quick Add Student"] = site_url('student/student_add_deo_short');
		//$menu['acad_ar']['Student Details']['Validation Requests'] = site_url('student/student_validate');
		$menu['acad_ar']['Student Details']['Validation Requests Specific'] = site_url('student/validate');
		$menu['acad_ar']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');

		//$auth ==> acad_dr
		$menu['acad_dr']=array();
		//$menu['acad_dr']["Manage Students"]["Add Student"] = site_url('student/student_add_deo');
		//$menu['acad_dr']["Manage Students"]["Quick Add Student"] = site_url('student/student_add_deo_short');

		//For Dean Associate
		$menu['adug']=array();
		$menu['adug']['Manage Students']=array();
		$menu['adug']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['adug']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');

		$menu['adpg']=array();
		$menu['adpg']['Manage Students']=array();
		$menu['adpg']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['adpg']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');

		$menu['adsw']=array();
		$menu['adsw']['Manage Students']=array();
		$menu['adsw']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['adsw']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');

		$menu['adsa']=array();
		$menu['adsa']['Manage Students']=array();
		$menu['adsa']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['adsa']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');

		$menu['adhm']=array();
		$menu['adhm']['Manage Students']=array();
		$menu['adhm']["Manage Students"]["View Student"]["Details"] = site_url('student_view_report/view');
		$menu['adhm']["Manage Students"]["View Student"]["Report"] = site_url('student_view_report/reports');










		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */
