<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Fee_upload_download_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMenu() {
        $menu = array();


		$menu['exam_dr'] = array();
        $menu['exam_dr']['Fee Upload Download'] = array();
        //$menu['exam_dr']['Fee Upload Download']['Upload Fee Structure'] = site_url('fee_upload_download/fee_upload_excel_all');
        //$menu['exam_dr']['Fee Upload Download']['Upload Fee Waiver'] = site_url('fee_upload_download/fee_waiver_list');
        //$menu['exam_dr']['Fee Upload Download']['Upload Extra List'] = site_url('fee_upload_download/extra_student_list');
        //$menu['exam_dr']['Fee Upload Download']['Download Final Report'] = site_url('fee_upload_download/fee_download_excel');
        //$menu['exam_dr']['Fee Upload Download']['Download Student List'] = site_url('fee_upload_download/fee_student_list_download_excel');
        //$menu['exam_dr']['Fee Upload Download']['Delete'] = site_url('fee_upload_download/fee_delete_db');
       /* $menu['exam_dr']['Fee Upload Download']['Registered Student List'] =  site_url('fee_upload_download/fee_get_reg_stu_list');  
        $menu['exam_dr']['Fee Upload Download']['Upload Fee Structure All'] = site_url('fee_upload_download/fee_upload_excel_all');
        $menu['exam_dr']['Fee Upload Download']['Download Fee Report/Format'] = site_url('fee_upload_download/fee_structure_excel');
        $menu['exam_dr']['Fee Upload Download']['Post Fee  List'] = site_url('fee_upload_download/fee_student_list_download_excel');
        $menu['exam_dr']['Fee Upload Download']['Download Final Report'] = site_url('fee_upload_download/fee_download_excel');
        $menu['exam_dr']['Fee Upload Download']['Initialize Fee tables'] =  site_url('fee_upload_download/fee_delete_db');
        $menu['exam_dr']['Fee Upload Download']['Open/Close Accounts'] =  site_url('fee_upload_download/fee_openclose_ac');
        $menu['exam_dr']['Fee Upload Download']['Fee Report'] =  site_url('fee_upload_download/fee_list');
*/
        $menu['acad_da2'] = array();
        $menu['acad_da2']['Fee Upload Download'] = array();
        //$menu['acad_da2']['Fee Upload Download']['Upload Fee Structure'] = site_url('fee_upload_download/fee_upload_excel_all');
        //$menu['acad_da2']['Fee Upload Download']['Upload Fee Waiver'] = site_url('fee_upload_download/fee_waiver_list');
        //$menu['acad_da2']['Fee Upload Download']['Upload Extra List'] = site_url('fee_upload_download/extra_student_list');
        //$menu['acad_da2']['Fee Upload Download']['Download Final Report'] = site_url('fee_upload_download/fee_download_excel');
        //$menu['acad_da2']['Fee Upload Download']['Download Student List'] = site_url('fee_upload_download/fee_student_list_download_excel');
        //$menu['acad_da2']['Fee Upload Download']['Delete'] = site_url('fee_upload_download/fee_delete_db');
       /* $menu['acad_da2']['Fee Upload Download']['Registered Student List'] =  site_url('fee_upload_download/fee_get_reg_stu_list');  
        $menu['acad_da2']['Fee Upload Download']['Upload Fee Structure All'] = site_url('fee_upload_download/fee_upload_excel_all');
        $menu['acad_da2']['Fee Upload Download']['Download Fee Report/Format'] = site_url('fee_upload_download/fee_structure_excel');
        $menu['acad_da2']['Fee Upload Download']['Post Fee  List'] = site_url('fee_upload_download/fee_student_list_download_excel');
        $menu['acad_da2']['Fee Upload Download']['Download Final Report'] = site_url('fee_upload_download/fee_download_excel');
        $menu['acad_da2']['Fee Upload Download']['Initialize Fee tables'] =  site_url('fee_upload_download/fee_delete_db');
        $menu['acad_da2']['Fee Upload Download']['Open/Close Accounts'] =  site_url('fee_upload_download/fee_openclose_ac');
        $menu['acad_da2']['Fee Upload Download']['Fee Report'] =  site_url('fee_upload_download/fee_list');
        */
        
        
        $menu['admin_exam'] = array();
        $menu['admin_exam']['Fee Upload Download'] = array();
        $menu['admin_exam']['Fee Upload Download']['Registered Student List'] =  site_url('fee_upload_download/fee_get_reg_stu_list');
        $menu['admin_exam']['Fee Upload Download']['Upload Fee Structure All x'] = site_url('fee_upload_download/fee_upload_excel_all');
        //$menu['admin_exam']['Fee Upload Download']['Upload Fee Structure'] = site_url('fee_upload_download/fee_upload_excel');
        //$menu['admin_exam']['Fee Upload Download']['Upload Fee Waiver'] = site_url('fee_upload_download/fee_waiver_list');
        //$menu['admin_exam']['Fee Upload Download']['Upload Extra List'] = site_url('fee_upload_download/extra_student_list');
               
	    $menu['admin_exam']['Fee Upload Download']['Download Fee Report/Format'] = site_url('fee_upload_download/fee_structure_excel');
        $menu['admin_exam']['Fee Upload Download']['Post Fee  List'] = site_url('fee_upload_download/fee_student_list_download_excel');
        $menu['admin_exam']['Fee Upload Download']['Download Final Report'] = site_url('fee_upload_download/fee_download_excel');
        $menu['admin_exam']['Fee Upload Download']['Initialize Fee tables'] =  site_url('fee_upload_download/fee_delete_db');
        $menu['admin_exam']['Fee Upload Download']['Open/Close Accounts'] =  site_url('fee_upload_download/fee_openclose_ac');
        $menu['admin_exam']['Fee Upload Download']['Fee Report x'] =  site_url('fee_upload_download/fee_list');
        //menu['admin_exam']['Fee Upload Download']['Delete']['Initialize Regular'] = site_url('fee_upload_download/fee_delete_db');
       //menu['admin_exam']['Fee Upload Download']['Delete']['Initialize Extra'] = site_url('fee_upload_download/fee_delete_db_extra');
       //menu['admin_exam']['Fee Upload Download']['Delete']['Initialize by number'] = site_url('fee_upload_download/fee_delete_db_number');
       //menu['admin_exam']['Fee Upload Download']['Delete']['Initialize all'] = site_url('fee_upload_download/fee_delete_db_All');
        
        


        

        return $menu;
    }

}
