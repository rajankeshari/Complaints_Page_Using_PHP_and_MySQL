<?php

class Course_structure_menu_model extends CI_Model
{
	function __construct()
	{
		// Calling the Model parent constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		/**
                 * faculy
                 */
        //        if($this->session->userdata('dept_id')){
          //          $menu['ft']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
            //    }else{
              //      $menu['ft']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
                //}
                //$menu['ft']["Course Structure"]["View Course Structure"] = site_url('course_structure/view');
                /**
                 * deo
                 */
				 
		//for Director
			$menu['dt']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse'); 	

			
		$menu['deo']=array();
		$menu['deo']['Course Structure']=array();
                $menu['deo']["Course Structure"]["Add a Course"] = site_url('course_structure/add_course');
		$menu['deo']["Course Structure"]["Add/Map a Branch"] = site_url('course_structure/add_branch');
		$menu['deo']["Course Structure"]["Delete Course/Branch"] = site_url('course_structure/delete');
		
		$menu['deo']["Course Structure"]['Add Course Structure']["Department Wise"] = site_url('course_structure/add');
		$menu['deo']["Course Structure"]["Add Course Structure"]['Common'] = site_url('course_structure/AddCS_Common');
		$menu['deo']["Course Structure"]["Add Course Structure"]['Preparatory'] = site_url('course_structure/AddCS_Prep');
                
                if($this->session->userdata('dept_id')){
                    $menu['deo']["Course Structure"]["Edit Course Structure"]['Department Wise'] = site_url('course_structure/edit/index/'.($this->session->userdata("dept_id")));
                }else{
                    $menu['deo']["Course Structure"]["Edit Course Structure"]['Department Wise'] = site_url('course_structure/edit/index/cse');
                }
		
                $menu['deo']["Course Structure"]["Edit Course Structure"]['Common'] = site_url('course_structure/edit/index/comm');
                $menu['deo']["Course Structure"]["Edit Course Structure"]['Preparatory'] = site_url('course_structure/edit/index/prep');
                
                if($this->session->userdata('dept_id')){
                    $menu['deo']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
                }else{
                    $menu['deo']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
                }
                
		
		$menu['deo']["Course Structure"]["Upload Syllabus"] = site_url('course_structure/upload_syllabus');
		
		$menu['deo']['Create a New Course'] = site_url('course_structure/add_course_main');
		$menu['deo']["Create a New Branch"] = site_url('course_structure/add_branch_main');
		
		
		
		/*$menu['hod']['Course Structure']=array();
		$userid = $this->session->userdata('id');
                if($this->session->userdata('dept_id')){
                    $menu['hod']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
                }else{
                    $menu['hod']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
                }*/
                //$menu['hod']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.$userid.'');
//$menu['hod']['Course Structure']["Offer Elective"] = site_url('course_structure/elective_offered_home/select_dept_common_electiv_page');
//$menu['hod']['Course Structure']["Offer Subject Summer"] = site_url('course_structure/summer_offered_home/select_dept_common_electiv_page');
		//$menu['hod']['Course Structure']["Offer Elective (Department)"] = site_url('course_structure/elective_offered_home');
               // $menu['hod']['Course Structure']["Offer Elective (Common)"] = site_url('course_structure/elective_offered_home_common');
		

			
		//$menu['hod']=array();
		//$menu['hod']['Choose Elective']=array();
		//$menu['hod']['Course Structure']["Offer Elective"] = site_url('course_structure/elective_offered_home');
		
	//	$menu['stu']=array();
	//	$menu['stu']['Course Structure']=array();
		//$menu['stu']["Course Structure"]["Edit Course Structure"] = site_url('course_structure/edit');
      //          if($this->session->userdata('dept_id')){
     //               $menu['stu']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
      //          }else{
      //              $menu['stu']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
     //           }
		//$menu['stu']["Course Structure"]["View Course Structure"] = site_url('course_structure/view');
		//auth acad_dr
                
             //   if($this->session->userdata('dept_id')){
            //        $menu['acad_dr']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
          //      }else{
          //          $menu['acad_dr']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
          //      }
		//$menu['acad_dr']["Course Structure"]["View Course Structure"] = site_url('course_structure/view');

		/*if($this->session->userdata('adug')){
                    $menu['adug']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
                }else{
                    $menu['adug']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
                }*/
				
			/*	if($this->session->userdata('adpg')){
                    $menu['adpg']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
                }else{
                    $menu['adpg']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
                }*/
				
				/*if($this->session->userdata('adac')){
                    $menu['adac']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/'.($this->session->userdata("dept_id")));
                }else{
                    $menu['adac']["Course Structure"]["View Course Structure"] = site_url('course_structure/view/index/cse');    
                }*/

		return $menu;
	}
}
/* End of file menu_model.php */
/* Location: mis/application/models/course_structure/menu_model.php */