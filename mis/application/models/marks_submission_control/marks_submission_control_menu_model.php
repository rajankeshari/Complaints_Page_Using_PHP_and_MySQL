<?php

class Marks_submission_control_menu_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getMenu() {

        // For DR Exam
		
		
        $menu['exam_dr'] = array();
        $menu['exam_dr']['Marks Submission Control'] = array();
         $menu['exam_dr']['Marks Submission Control'] = site_url('marks_submission_control/marks_submission_control_file');
		 
        
       // $menu['exam_dr']['Marks Submission Control']['Open-Close Date'] = site_url('marks_submission_control/marks_submission_control_file');
       // $menu['exam_dr']['Marks Submission Control']['Edit Open-Close Date'] = site_url('marks_submission_control/marks_submission_control_file_edit');

	//	$menu['exam_dr']["Exam Control"]['marks Finalization Revert'] = site_url('exam_control/examControl'); anuj
      //  $menu['exam_dr']["Exam Control"]['Defaulter Control'] = site_url('exam_control/examControl/defaulterControl');
	   
	   
	   /*
	   
	   $menu['acad_ar'] = array();
        $menu['acad_ar']['Marks Submission Control'] = array();
         $menu['acad_ar']['Marks Submission Control'] = site_url('marks_submission_control/marks_submission_control_file');
        */
       
	   
        return $menu;
    }

}

?>