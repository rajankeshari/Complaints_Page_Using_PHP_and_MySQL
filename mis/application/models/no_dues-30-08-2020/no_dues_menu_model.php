<?php
/*
 * Author : Sourav Sinha
   Date: 08-May-2018
   Email: souravsinha2010@gmail.com
 */
class No_dues_menu_model extends CI_Model{

    function __construct()
    {
        // Calling the Model parent constructor
        parent::__construct();
    }

    function getMenu(){

        $menu = array();

        /*
		$menu['stu'] = array();
        $menu['stu']['No Dues']['No Dues'] = site_url('no_dues/no_dues_student_init/init_student');
        $menu['stu']['No Dues']['Print No Dues Form'] = site_url('no_dues/no_dues_student_init/print_dues');
        



        $menu['admin'] = array();
        $menu['admin']['No Dues'] = array();
        $menu['admin']['No Dues']['No Dues for Dropouts']=array();
        $menu['admin']['No Dues']['Normal No Dues']=array();
       // $menu['admin']['No Dues']['Normal No Dues']['Edit Departments'] = site_url('no_dues/no_dues_admin_edit/no_dues_dept_list');
        $menu['admin']['No Dues']['Normal No Dues']['Start No Dues']=array();
        $menu['admin']['No Dues']['Normal No Dues']['Start No Dues']['Start for admin'] = site_url('no_dues/no_dues_admin_edit/start_dues_admin');
        $menu['admin']['No Dues']['Normal No Dues']['Start No Dues']['Start for student'] = site_url('no_dues/no_dues_admin_edit/start_dues_student');
        $menu['admin']['No Dues']['Normal No Dues']['Stop No Dues']=array();
        $menu['admin']['No Dues']['Normal No Dues']['Stop No Dues']['Stop for admin'] = site_url("no_dues/no_dues_admin_edit/stop_dues_admin");
        $menu['admin']['No Dues']['Normal No Dues']['Stop No Dues']['Stop for student'] = site_url("no_dues/no_dues_admin_edit/stop_dues_student");
        $menu['admin']['No Dues']['Normal No Dues']['Edit No Dues Time']=array();
        $menu['admin']['No Dues']['Normal No Dues']['Edit No Dues Time']['Edit for admin'] = site_url("no_dues/no_dues_admin_edit/edit_no_dues_start_admin");
        $menu['admin']['No Dues']['Normal No Dues']['Edit No Dues Time']['Edit for student'] = site_url("no_dues/no_dues_admin_edit/edit_no_dues_start_student");
        $menu['admin']['No Dues']['Normal No Dues']['Verify receipt'] =site_url('no_dues/no_dues_admin_edit/verify_receipt_number');
        $menu['admin']['No Dues']['Normal No Dues']['History All'] = site_url('no_dues/no_dues_admin_edit/no_dues_history_all');

        $menu['admin']['No Dues']['No Dues for Dropouts']['Add Dropouts'] = site_url('no_dues/no_dues_admin_edit/add_dropouts');
       // $menu['admin']['No Dues']['No Dues for Dropouts']['Edit Departments'] = site_url('no_dues/no_dues_admin_edit/no_dues_dept_list');
        $menu['admin']['No Dues']['No Dues for Dropouts']['Start No Dues']=array();
        $menu['admin']['No Dues']['No Dues for Dropouts']['Start No Dues']['Start for admin'] = site_url('no_dues/no_dues_admin_edit/start_dues_admin_2');
        $menu['admin']['No Dues']['No Dues for Dropouts']['Start No Dues']['Start for student'] = site_url('no_dues/no_dues_admin_edit/start_dues_student_2');
        $menu['admin']['No Dues']['No Dues for Dropouts']['Stop No Dues']=array();
        $menu['admin']['No Dues']['No Dues for Dropouts']['Stop No Dues']['Stop for admin'] = site_url("no_dues/no_dues_admin_edit/stop_dues_admin_2");
        $menu['admin']['No Dues']['No Dues for Dropouts']['Stop No Dues']['Stop for student'] = site_url("no_dues/no_dues_admin_edit/stop_dues_student_2");
        $menu['admin']['No Dues']['No Dues for Dropouts']['Edit No Dues Time']=array();
        $menu['admin']['No Dues']['No Dues for Dropouts']['Edit No Dues Time']['Edit for admin'] = site_url("no_dues/no_dues_admin_edit/edit_no_dues_start_admin_2");
        $menu['admin']['No Dues']['No Dues for Dropouts']['Edit No Dues Time']['Edit for student'] = site_url("no_dues/no_dues_admin_edit/edit_no_dues_start_student_2");
        $menu['admin']['No Dues']['No Dues for Dropouts']['Verify receipt'] =site_url('no_dues/no_dues_admin_edit/verify_receipt_number_2');
         






       $menu['hod'] = array();
       $menu['hod']['No Dues'] = array();
       $menu['hod']['No Dues']['No Dues for Dropouts']=array();
       $menu['hod']['No Dues']['Normal No Dues']=array();
       $menu['hod']['No Dues']['Normal No Dues']['No dues Entry'] = site_url('no_dues/no_dues_admin_edit/no_dues_entry');
       $menu['hod']['No Dues']['Normal No Dues']['Edit No Dues']= site_url('no_dues/no_dues_admin_edit/no_dues_edit');
       $menu['hod']['No Dues']['Normal No Dues']['Delete No Dues ']= site_url('no_dues/no_dues_admin_edit/no_dues_delete');
       $menu['hod']['No Dues']['Normal No Dues']['history'] = site_url('no_dues/no_dues_admin_edit/no_dues_history');
       $menu['hod']['No Dues']['Normal No Dues']['View Dues Status'] = site_url('no_dues/no_dues_admin_edit/no_dues_rejected_list');
        $menu['hod']['No Dues']['Normal No Dues']['History All'] = site_url('no_dues/no_dues_admin_edit/no_dues_history_all');

       $menu['hod']['No Dues']['No Dues for Dropouts']['No dues Entry'] = site_url('no_dues/no_dues_admin_edit/no_dues_entry_2');
       $menu['hod']['No Dues']['No Dues for Dropouts']['Edit No Dues']= site_url('no_dues/no_dues_admin_edit/no_dues_edit_2');
       $menu['hod']['No Dues']['No Dues for Dropouts']['Delete No Dues ']= site_url('no_dues/no_dues_admin_edit/no_dues_delete_2');
       $menu['hod']['No Dues']['No Dues for Dropouts']['history'] = site_url('no_dues/no_dues_admin_edit/no_dues_history_2');
       $menu['hod']['No Dues']['No Dues for Dropouts']['View Dues Status'] = site_url('no_dues/no_dues_admin_edit/no_dues_rejected_list_2');        



        $menu['deo'] = array();
 	    $menu['deo']['No Dues'] = array();
        $menu['deo']['No Dues']['No Dues for Dropouts']=array();
        $menu['deo']['No Dues']['Normal No Dues']=array();
        $menu['deo']['No Dues']['Normal No Dues']['Edit Departments'] = site_url('no_dues/no_dues_admin_edit/no_dues_dept_list');
        $menu['deo']['No Dues']['Normal No Dues']['Edit No Dues Admins'] = site_url('no_dues/no_dues_admin_edit/no_dues_nda_list');
        $menu['deo']['No Dues']['Normal No Dues']['No dues Entry'] = site_url('no_dues/no_dues_admin_edit/no_dues_entry');
         $menu['deo']['No Dues']['Normal No Dues']['History All'] = site_url('no_dues/no_dues_admin_edit/no_dues_history_all');

        $menu['deo']['No Dues']['No Dues for Dropouts']['Edit Departments'] = site_url('no_dues/no_dues_admin_edit/no_dues_dept_list');
        $menu['deo']['No Dues']['No Dues for Dropouts']['Edit No Dues Admins'] = site_url('no_dues/no_dues_admin_edit/no_dues_nda_list');
        $menu['deo']['No Dues']['No Dues for Dropouts']['No dues Entry'] = site_url('no_dues/no_dues_admin_edit/no_dues_entry_2');



       $menu['nda'] = array();
       $menu['nda']['No Dues'] = array();
       $menu['nda']['No Dues']['No Dues for Dropouts']=array();
       $menu['nda']['No Dues']['Normal No Dues']=array();
       $menu['nda']['No Dues']['Normal No Dues']['No dues Entry'] = site_url('no_dues/no_dues_admin_edit/no_dues_entry');
       $menu['nda']['No Dues']['Normal No Dues']['Edit No Dues']= site_url('no_dues/no_dues_admin_edit/no_dues_edit');
       $menu['nda']['No Dues']['Normal No Dues']['Delete No Dues ']= site_url('no_dues/no_dues_admin_edit/no_dues_delete');
       $menu['nda']['No Dues']['Normal No Dues']['history'] = site_url('no_dues/no_dues_admin_edit/no_dues_history');
       $menu['nda']['No Dues']['Normal No Dues']['View Dues Status'] = site_url('no_dues/no_dues_admin_edit/no_dues_rejected_list');
       $menu['nda']['No Dues']['Normal No Dues']['History All'] = site_url('no_dues/no_dues_admin_edit/no_dues_history_all'); 

       $menu['nda']['No Dues']['No Dues for Dropouts']['No dues Entry'] = site_url('no_dues/no_dues_admin_edit/no_dues_entry_2');
       $menu['nda']['No Dues']['No Dues for Dropouts']['Edit No Dues']= site_url('no_dues/no_dues_admin_edit/no_dues_edit_2');
       $menu['nda']['No Dues']['No Dues for Dropouts']['Delete No Dues ']= site_url('no_dues/no_dues_admin_edit/no_dues_delete_2');
       $menu['nda']['No Dues']['No Dues for Dropouts']['history'] = site_url('no_dues/no_dues_admin_edit/no_dues_history_2');
       $menu['nda']['No Dues']['No Dues for Dropouts']['View Dues Status'] = site_url('no_dues/no_dues_admin_edit/no_dues_rejected_list_2');     

*/
   
        return $menu;
    }
}

