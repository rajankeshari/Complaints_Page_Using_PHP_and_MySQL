<?php

class sem_date_open_close_menu_model extends CI_Model {

  

    function getMenu() {

        // For DR Exam
        $menu['acad_ar'] = array();
        $menu['acad_ar']['Registration/Exam Date Open Close'] = array();
        $menu['acad_ar']['Registration/Exam Date Open Close'] = site_url('sem_date_open_close/semester_date_open_close');
		
		$menu['exam_dr'] = array();
        $menu['exam_dr']['Registration/Exam Date Open Close'] = array();
        $menu['exam_dr']['Registration/Exam Date Open Close'] = site_url('sem_date_open_close/semester_date_open_close');
        
        return $menu;
    }

}

?>