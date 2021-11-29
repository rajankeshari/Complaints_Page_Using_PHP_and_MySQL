<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Complaint_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();
		//auth ==> emp
		$menu['emp']=array();
		$menu['emp']['Complaint General'] = array();
		$menu['emp']['Complaint General']['Register your Complaint'] = site_url('complaint/register_complaint');
		$menu['emp']['Complaint General']['View all your Complaint'] = site_url('complaint/view_own_complaint');
		
		$menu['emp']['Complaint MIS'] = array();
		//$menu['emp']['Complaint MIS']['Register your Complaint'] = site_url('complaint/register_mis_complaint');
		$menu['emp']['Complaint MIS']['View all your Complaint'] = site_url('complaint/view_own_mis_complaint');
		
		//auth ==> stu
		$menu['stu']=array();
		$menu['stu']['Complaint General'] = array();
		$menu['stu']['Complaint General']['Register your Complaint'] = site_url('complaint/register_complaint');
		$menu['stu']['Complaint General']['View all your Complaint'] = site_url('complaint/view_own_complaint');
		
		//$menu['stu']['Complaint MIS'] = array();
		//$menu['stu']['Complaint MIS']['Register your Complaint'] = site_url('complaint/register_mis_complaint');
		//$menu['stu']['Complaint MIS']['View all your Complaint'] = site_url('complaint/view_own_mis_complaint');
		
		
		$menu['spvr_cc']=array();
		$menu['spvr_cc']['Online Complaint'] = site_url('complaint/supervisor/view_complaints/Internet');
		$menu['spvr_cc']['Complaint on behalf of complainant'] = site_url('complaint/complain_on_behalf_of_student');//complaint on behalf of complainant menu
        $menu['spvr_cc']['Report'] = site_url('complaint/report/index/cc');
                
		
		$menu['spvr_civil']=array();
		$menu['spvr_civil']['Online Complaint'] = site_url('complaint/supervisor/view_complaints/Civil');
		$menu['spvr_civil']['Complaint on behalf of complainant'] = site_url('complaint/complain_on_behalf_of_student');//complaint on behalf of complainant menu
        $menu['spvr_civil']['Report'] = site_url('complaint/report/index/civ');

		$menu['spvr_ee']=array();
		$menu['spvr_ee']['Online Complaint'] = site_url('complaint/supervisor/view_complaints/Electrical');
		$menu['spvr_ee']['Complaint on behalf of complainant'] = site_url('complaint/complain_on_behalf_of_student');//complaint on behalf of complainant menu
        $menu['spvr_ee']['Report'] = site_url('complaint/report/index/ee');
                
		
		$menu['spvr_mess']=array();
		$menu['spvr_mess']['Online Complaint'] = site_url('complaint/supervisor/view_complaints/Mess');
		$menu['spvr_mess']['Complaint on behalf of complainant'] = site_url('complaint/complain_on_behalf_of_student');//complaint on behalf of complainant menu
        $menu['spvr_mess']['Report'] = site_url('complaint/report/index/mess');
		
		$menu['spvr_snt']=array();
		$menu['spvr_snt']['Online Complaint'] = site_url('complaint/supervisor/view_complaints/Sanitary');
		$menu['spvr_snt']['Complaint on behalf of complainant'] = site_url('complaint/complain_on_behalf_of_student');//complaint on behalf of complainant menu
        $menu['spvr_snt']['Report'] = site_url('complaint/report/index/snt');
		
                
        $menu['spvr_phone']=array();
		$menu['spvr_phone']['Online Complaint'] = site_url('complaint/supervisor/view_complaints/Telephone');
		$menu['spvr_phone']['Complaint on behalf of complainant'] = site_url('complaint/complain_on_behalf_of_student');//complaint on behalf of complainant menu
        $menu['spvr_phone']['Report'] = site_url('complaint/report/index/phone');
                
         //MIS ADMIN MENU LIST STARTS
        $menu['mis_admin']=array();
		$menu['mis_admin']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/All');
		$menu['mis_admin']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['mis_admin']['Report'] = site_url('complaint/mis_report/index/All');


        $menu['dev_att']=array();
		$menu['dev_att']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Attendance');
		$menu['dev_att']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_att']['Report'] = site_url('complaint/mis_report/index/Attendance');

        $menu['dev_feed']=array();
		$menu['dev_feed']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Feedback');
		$menu['dev_feed']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_feed']['Report'] = site_url('complaint/mis_report/index/Feedback');

        $menu['dev_grade']=array();
		$menu['dev_grade']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Grade_Sheet');
		$menu['dev_grade']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_grade']['Report'] = site_url('complaint/mis_report/index/Grade_Sheet');

        $menu['dev_hall']=array();
		$menu['dev_hall']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Hall_Ticket');
		$menu['dev_hall']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_hall']['Report'] = site_url('complaint/mis_report/index/Hall_Ticket');

        $menu['dev_info']=array();
		$menu['dev_info']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Personal_Details');
		$menu['dev_info']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_info']['Report'] = site_url('complaint/mis_report/index/Personal_Details');

        $menu['dev_login']=array();
		$menu['dev_login']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Login');
		$menu['dev_login']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_login']['Report'] = site_url('complaint/mis_report/index/Login');

        $menu['dev_other']=array();
		$menu['dev_other']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Others');
		$menu['dev_other']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_other']['Report'] = site_url('complaint/mis_report/index/Others');

        $menu['dev_semreg']=array();
		$menu['dev_semreg']['MIS Complaint'] = site_url('complaint/supervisor/view_mis_complaints/Semester_Registration');
		$menu['dev_semreg']['Complaint on behalf of complainant'] = site_url('complaint/mis_complain_on_behalf_of_student');
        $menu['dev_semreg']['Report'] = site_url('complaint/mis_report/index/Semester_Registration');
        
        
        
		return $menu;
	}
}
/* End of file employee_menu.php */
/* Location: mis/application/models/employee/employee_menu.php */