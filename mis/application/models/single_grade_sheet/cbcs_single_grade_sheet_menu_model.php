<?php

class Cbcs_single_grade_sheet_menu_model extends CI_Model {

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
		
		$menu['dean_acad']["Grade Sheet"]["Single Grade Sheet"] = site_url('single_grade_sheet/cbcs_single_grade_sheet_bunch');
		$menu['exam_res'] = array();
		$menu['exam_res']["Grade Sheet"]["Single Grade Sheet"] = site_url('single_grade_sheet/cbcs_single_grade_sheet_bunch');
        return $menu;
    }

}

/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */
