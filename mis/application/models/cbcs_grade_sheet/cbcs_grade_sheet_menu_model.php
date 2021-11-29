<?php

class Cbcs_grade_sheet_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {


        //$menu['exam_dr']['Restrictions'] = array();
        //$menu['exam_dr']["Restrictions"] = site_url('exam_absent_record/exam_absent');

        $menu = array();
		
        $menu['stu'] = array();
        //$menu['stu']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/grade_sheet_final_foil');
        //$menu['stu']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/cbcs_grade_sheet_final_foil');
		$menu['stu']["Grade Sheet"] = site_url('cbcs_grade_sheet/cbcs_grade_sheet_final_foil/get_grade_report');

		/*$menu['exam_dr'] = array();
		$menu['exam_dr']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		$menu['exam_dr']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet');  
		$menu['exam_dr']["Grade Sheet"]["Bunch Print"] = site_url('student_grade_sheet/bunch_print_gradesheet');
        $menu['exam_dr']["Grade Sheet"]["Remove Restriction"] = site_url('student_grade_sheet/dr_gs_remove_rest');
        $menu['exam_dr']["Grade Sheet"]["Feedback Fine"] = site_url('student_grade_sheet/dr_gs_remove_feedback');
		
		$menu['exam_da1'] = array();
		$menu['exam_da1']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		$menu['exam_da1']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet');  
		$menu['exam_da1']["Grade Sheet"]["Feedback Fine"] = site_url('student_grade_sheet/dr_gs_remove_feedback');
		$menu['exam_da1']["Grade Sheet"]["Bunch Print"] = site_url('student_grade_sheet/bunch_print_gradesheet');
		
		$menu['exam_da2'] = array();
		$menu['exam_da2']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		$menu['exam_da2']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet');  
		
		$menu['admin_exam'] = array();
		$menu['admin_exam']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		$menu['admin_exam']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet');  
		*/
		
		//$menu['tpo'] = array();
		//$menu['tpo']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		//$menu['tpo']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet');  
		
		//$menu['adug'] = array();
		//$menu['adug']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		//$menu['adug']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet');  
		
		//$menu['adpg'] = array();
		//$menu['adpg']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		//$menu['adpg']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet'); 
		
		/*$menu['adsw'] = array();
		$menu['adsw']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		$menu['adsw']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet'); 
		
		$menu['adsa'] = array();
		$menu['adsa']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		$menu['adsa']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet'); 
		
		$menu['adhm'] = array();
		$menu['adhm']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		$menu['adhm']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet'); 
		*/
		//$menu['dean_acad'] = array();
		//$menu['dean_acad']["Grade Sheet"]["OLD"] = site_url('student_grade_sheet/dr_grade_sheet');   
		//$menu['dean_acad']["Grade Sheet"]["CBCS"] = site_url('cbcs_grade_sheet/dr_grade_sheet'); 
		$menu['dean_acad'] = array();
		$menu['dean_acad']["Grade Sheet"]["Bunch Grade Sheet"] = site_url('cbcs_grade_sheet/cbcs_grade_sheet_bunch');
		$menu['exam_res'] = array();
		$menu['exam_res']["Grade Sheet"]["Bunch Grade Sheet"] = site_url('cbcs_grade_sheet/cbcs_grade_sheet_bunch');
        return $menu;
    }

}

/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */
