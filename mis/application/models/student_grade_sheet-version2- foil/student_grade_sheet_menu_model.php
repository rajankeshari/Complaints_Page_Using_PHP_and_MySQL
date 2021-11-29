<?php

class Student_grade_sheet_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {
        

        $menu['exam_dr']['Restrictions'] = array();
        $menu['exam_dr']["Restrictions"] = site_url('exam_absent_record/exam_absent');

        return $menu;
    }

}

/* End of file menu_model.php */
/* Location: mis/application/models/employee/menu_model.php */