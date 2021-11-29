<?php

class Information_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu($auth='')
	{
		$menu=array();
		//auth ==> emp
		/*$menu['emp']=array();
		$menu['emp']["View Notice, Circular or Minutes"] = array();
		$menu['emp']["View Notice, Circular or Minutes"]['Notice'] = site_url('information/view_notice');
		$menu['emp']["View Notice, Circular or Minutes"]['Circular'] = site_url('information/view_circular');
		$menu['emp']["View Notice, Circular or Minutes"]['Minutes'] = site_url('information/view_minute');*/
		
		//auth ==> stu
	/*	$menu['stu']=array();
		$menu['stu']["View Notice, Circular or Minutes"] = array();
		$menu['stu']["View Notice, Circular or Minutes"]['Notice'] = site_url('information/view_notice');
		$menu['stu']["View Notice, Circular or Minutes"]['Circular'] = site_url('information/view_circular');
		$menu['stu']["View Notice, Circular or Minutes"]['Minutes'] = site_url('information/view_minute');
		*/
		
		//auth ==> tpo
		$menu['tpo']=array();
		$menu['tpo']['Notices, Circulars or Minutes']=array();
		$menu['tpo']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['tpo']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/tpo');
	//	$menu['tpo']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/tpo');
	//	$menu['tpo']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/tpo');
		
		$menu['tpo']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['tpo']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/tpo');
	//	$menu['tpo']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/tpo');
	//	$menu['tpo']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/tpo');

		$menu['tpo']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['tpo']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
	//	$menu['tpo']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
	//	$menu['tpo']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		
		//auth ==> est_ar
		$menu['est_ar']=array();
		$menu['est_ar']['Notices, Circulars or Minutes']=array();
		$menu['est_ar']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['est_ar']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/est_ar');
		$menu['est_ar']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/est_ar');
		$menu['est_ar']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/est_ar');
		
		$menu['est_ar']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['est_ar']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/est_ar');
		$menu['est_ar']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/est_ar');
		$menu['est_ar']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/est_ar');

		$menu['est_ar']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['est_ar']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['est_ar']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['est_ar']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		//auth ==> hod
	/*	$menu['hod']=array();
		$menu['hod']['Notices, Circulars or Minutes']=array();
		$menu['hod']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['hod']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/hod');
		$menu['hod']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/hod');
		$menu['hod']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/hod');
		            
		$menu['hod']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['hod']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/hod');
		$menu['hod']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/hod');
		$menu['hod']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/hod');
                                
		$menu['hod']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['hod']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['hod']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['hod']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		*/
		
		
		
		//auth ==> admin_exam
		/*$menu['admin_exam']=array();
		$menu['admin_exam']['Notices, Circulars or Minutes']=array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/admin_exam');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/admin_exam');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/admin_exam');
		            
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/admin_exam');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/admin_exam');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/admin_exam');
                                
		$menu['admin_exam']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		
		
		//auth ==> admin
		$menu['admin']=array();

		$menu['admin']['Notices, Circulars or Minutes']=array();
		$menu['admin']['Notices, Circulars or Minutes']["Global Group"]=array();
		$menu['admin']['Notices, Circulars or Minutes']["Global Group"]["Create"]=site_url('information/group_global/create_group_global');
		$menu['admin']['Notices, Circulars or Minutes']["Global Group"]["Edit"]=site_url('information/group_global/edit_group_global');
		$menu['admin']['Notices, Circulars or Minutes']["Global Group"]["Delete"]=site_url('information/group_global/delete_group_global');

		$menu['admin']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['admin']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/admin');
		$menu['admin']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/admin');
		$menu['admin']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/admin');
		            
		$menu['admin']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['admin']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/admin');
		$menu['admin']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/admin');
		$menu['admin']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/admin');
                                
		$menu['admin']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['admin']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['admin']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['admin']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		//auth ==> hos
		$menu['hos']=array();
		$menu['hos']['Notices, Circulars or Minutes']=array();
		$menu['hos']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['hos']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/hos');
		$menu['hos']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/hos');
		//$menu['hos']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/hos');
		            
		$menu['hos']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['hos']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/hos');
		$menu['hos']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/hos');
		//$menu['hos']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/hos');
                                
		$menu['hos']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['hos']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['hos']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['hos']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		
		//auth ==> exam_dr
	/*	$menu['exam_dr']=array();
		$menu['exam_dr']['Notices, Circulars or Minutes']=array();
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/exam_dr');
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/exam_dr');
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/exam_dr');
		                
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/exam_dr');
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/exam_dr');
		$menu['exam_dr']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/exam_dr');
                                                        
		$menu['exam_dr']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['exam_dr']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['exam_dr']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['exam_dr']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
*/
		
		//auth ==> dt
		/*
		$menu['dt']=array();
		$menu['dt']['Notices, Circulars or Minutes']=array();
		$menu['dt']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['dt']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/dt');
		$menu['dt']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/dt');
		$menu['dt']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/dt');
		           
		$menu['dt']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['dt']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/dt');
		$menu['dt']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/dt');
		$menu['dt']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/dt');
                   
		$menu['dt']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['dt']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['dt']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['dt']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		
		//auth ==> dsw
		$menu['dsw']=array();
		$menu['dsw']['Notices, Circulars or Minutes']=array();
		$menu['dsw']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['dsw']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/dsw');
		$menu['dsw']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/dsw');
		//$menu['dsw']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/dsw');
		            
		$menu['dsw']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['dsw']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/dsw');
		$menu['dsw']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/dsw');
		//$menu['dsw']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/dsw');
                    
		$menu['dsw']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['dsw']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['dsw']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['dsw']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		
		//auth ==> deo
		/*
		$menu['deo']=array();
		$menu['deo']["View Notice, Circular or Minutes"] = array();
		$menu['deo']["View Notice, Circular or Minutes"]['Notice'] = site_url('information/view_notice');
		$menu['deo']["View Notice, Circular or Minutes"]['Circular'] = site_url('information/view_circular');
		$menu['deo']["View Notice, Circular or Minutes"]['Minutes'] = site_url('information/view_minute');
		*/
		
		//auth ==> deo
		$menu['deo']=array();
		$menu['deo']['Notices, Circulars or Minutes']=array();
		$menu['deo']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['deo']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/'.$auth);
		$menu['deo']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/'.$auth);
		//$menu['deo']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/'.$auth);
		            
		$menu['deo']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['deo']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/'.$auth);
		$menu['deo']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/'.$auth);
		//$menu['deo']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/'.$auth);
       	/*                         
		$menu['da1']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['da1']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['da1']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['da1']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/

		//auth ==> ----da1
		
	/*	$menu['da1']=array();
		$menu['da1']['Notices, Circulars or Minutes']=array();
		$menu['da1']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['da1']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/'.$auth);
		$menu['da1']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/'.$auth);
		$menu['da1']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/'.$auth);
		            
		$menu['da1']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['da1']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/'.$auth);
		$menu['da1']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/'.$auth);
		$menu['da1']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/'.$auth);
                                
		$menu['da1']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['da1']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['da1']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['da1']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		//auth ==> ----info
		/*
		$menu['info']=array();
		$menu['info']['Notices, Circulars or Minutes']=array();
		$menu['info']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['info']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/'.$auth);
		$menu['info']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/'.$auth);
		$menu['info']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/'.$auth);
		            
		$menu['info']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['info']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/'.$auth);
		$menu['info']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/'.$auth);
		$menu['info']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/'.$auth);
                                
		$menu['info']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['info']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['info']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['info']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		//auth ==> ----info
		
		$menu['info']=array();
		$menu['info']['Notices, Circulars or Minutes']=array();
		$menu['info']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['info']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/info');
		$menu['info']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/info');
		//$menu['info']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/info');
		            
		$menu['info']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['info']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/info');
		$menu['info']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/info');
		//$menu['info']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/info');
                                
		$menu['info']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['info']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['info']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['info']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		
		//for Director
		$menu['dt']=array();

		$menu['dt']['Notices, Circulars or Minutes']=array();
		$menu['dt']['Notices, Circulars or Minutes']["Global Group"]=array();
		$menu['dt']['Notices, Circulars or Minutes']["Global Group"]["Create"]=site_url('information/group_global/create_group_global');
		$menu['dt']['Notices, Circulars or Minutes']["Global Group"]["Edit"]=site_url('information/group_global/edit_group_global');
		$menu['dt']['Notices, Circulars or Minutes']["Global Group"]["Delete"]=site_url('information/group_global/delete_group_global');

		$menu['dt']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['dt']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/dt');
		$menu['dt']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/dt');
		//$menu['dt']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/dt');
		            
		$menu['dt']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['dt']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/dt');
		$menu['dt']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/dt');
		//$menu['dt']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/dt');
                                
		$menu['dt']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['dt']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['dt']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['dt']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		
		//Deans DSW aleady given up of page
		
		/*$menu['dean_acad']=array();
		$menu['dean_acad']['Notices, Circulars or Minutes']=array();
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/dean_acad');
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/dean_acad');
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/dean_acad');
		            
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/dean_acad');
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/dean_acad');
		$menu['dean_acad']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/dean_acad');
                    
		$menu['dean_acad']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['dean_acad']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['dean_acad']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['dean_acad']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		$menu['dean_fnp']=array();
		$menu['dean_fnp']['Notices, Circulars or Minutes']=array();
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/dean_fnp');
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/dean_fnp');
		//$menu['dean_fnp']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/dean_fnp');
		            
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/dean_fnp');
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/dean_fnp');
		//$menu['dean_fnp']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/dean_fnp');
                    
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['dean_fnp']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['dean_fnp']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		$menu['dean_iraa']=array();
		$menu['dean_iraa']['Notices, Circulars or Minutes']=array();
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/dean_iraa');
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/dean_iraa');
		//$menu['dean_iraa']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/dean_iraa');
		            
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/dean_iraa');
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/dean_iraa');
		//$menu['dean_iraa']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/dean_iraa');
                    
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['dean_iraa']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['dean_iraa']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		
		$menu['dean_rnd']=array();
		$menu['dean_rnd']['Notices, Circulars or Minutes']=array();
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/dean_rnd');
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/dean_rnd');
		//$menu['dean_rnd']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/dean_rnd');
		            
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/dean_rnd');
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/dean_rnd');
		//$menu['dean_rnd']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/dean_rnd');
                    
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['dean_rnd']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['dean_rnd']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//1
		$menu['adaa']=array();
		$menu['adaa']['Notices, Circulars or Minutes']=array();
		$menu['adaa']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adaa']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adaa');
		$menu['adaa']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adaa');
		//$menu['adaa']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adaa');
		            
		$menu['adaa']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adaa']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adaa');
		$menu['adaa']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adaa');
		//$menu['adaa']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adaa');
                    
		$menu['adaa']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adaa']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adaa']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adaa']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//2
		$menu['adcm']=array();
		$menu['adcm']['Notices, Circulars or Minutes']=array();
		$menu['adcm']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adcm']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adcm');
		$menu['adcm']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adcm');
		//$menu['adcm']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adcm');
		            
		$menu['adcm']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adcm']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adcm');
		$menu['adcm']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adcm');
		//$menu['adcm']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adcm');
                    
		$menu['adcm']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adcm']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adcm']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adcm']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//3
		$menu['adhm']=array();
		$menu['adhm']['Notices, Circulars or Minutes']=array();
		$menu['adhm']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adhm']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adhm');
		$menu['adhm']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adhm');
		//$menu['adhm']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adhm');
		            
		$menu['adhm']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adhm']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adhm');
		$menu['adhm']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adhm');
		//$menu['adhm']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adhm');
                    
		$menu['adhm']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adhm']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adhm']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adhm']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//4
		$menu['adii']=array();
		$menu['adii']['Notices, Circulars or Minutes']=array();
		$menu['adii']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adii']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adii');
		$menu['adii']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adii');
		//$menu['adii']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adii');
		            
		$menu['adii']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adii']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adii');
		$menu['adii']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adii');
		//$menu['adii']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adii');
                    
		$menu['adii']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adii']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adii']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adii']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//5
		/*$menu['adis']=array();
		$menu['adis']['Notices, Circulars or Minutes']=array();
		$menu['adis']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adis']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adis');
		$menu['adis']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adis');
		$menu['adis']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adis');
		            
		$menu['adis']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adis']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adis');
		$menu['adis']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adis');
		$menu['adis']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adis');
                    
		$menu['adis']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adis']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adis']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['adis']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		//6
		$menu['adop']=array();
		$menu['adop']['Notices, Circulars or Minutes']=array();
		$menu['adop']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adop']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adop');
		$menu['adop']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adop');
		//$menu['adop']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adop');
		            
		$menu['adop']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adop']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adop');
		$menu['adop']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adop');
		//$menu['adop']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adop');
                    
		$menu['adop']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adop']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adop']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adop']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//7
		/*$menu['adpg']=array();
		$menu['adpg']['Notices, Circulars or Minutes']=array();
		$menu['adpg']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adpg']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adpg');
		$menu['adpg']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adpg');
		$menu['adpg']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adpg');
		            
		$menu['adpg']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adpg']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adpg');
		$menu['adpg']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adpg');
		$menu['adpg']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adpg');
                    
		$menu['adpg']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adpg']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adpg']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['adpg']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		//8
		$menu['adpp']=array();
		$menu['adpp']['Notices, Circulars or Minutes']=array();
		$menu['adpp']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adpp']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adpp');
		$menu['adpp']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adpp');
		//$menu['adpp']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adpp');
		            
		$menu['adpp']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adpp']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adpp');
		$menu['adpp']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adpp');
		//$menu['adpp']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adpp');
                    
		$menu['adpp']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adpp']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adpp']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adpp']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//9
		$menu['adrec']=array();
		$menu['adrec']['Notices, Circulars or Minutes']=array();
		$menu['adrec']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adrec']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adrec');
		$menu['adrec']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adrec');
		//$menu['adrec']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adrec');
		            
		$menu['adrec']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adrec']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adrec');
		$menu['adrec']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adrec');
		//$menu['adrec']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adrec');
                    
		$menu['adrec']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adrec']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adrec']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adrec']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//10
		$menu['adsa']=array();
		$menu['adsa']['Notices, Circulars or Minutes']=array();
		$menu['adsa']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adsa']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adsa');
		$menu['adsa']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adsa');
		//$menu['adsa']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adsa');
		            
		$menu['adsa']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adsa']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adsa');
		$menu['adsa']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adsa');
		//$menu['adsa']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adsa');
                    
		$menu['adsa']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adsa']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adsa']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adsa']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//11
		$menu['adsr']=array();
		$menu['adsr']['Notices, Circulars or Minutes']=array();
		$menu['adsr']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adsr']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adsr');
		$menu['adsr']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adsr');
		//$menu['adsr']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adsr');
		            
		$menu['adsr']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adsr']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adsr');
		$menu['adsr']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adsr');
		//$menu['adsr']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adsr');
                    
		$menu['adsr']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adsr']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adsr']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adsr']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//12
		$menu['adsw']=array();
		$menu['adsw']['Notices, Circulars or Minutes']=array();
		$menu['adsw']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adsw']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adsw');
		$menu['adsw']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adsw');
		//$menu['adsw']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adsw');
		            
		$menu['adsw']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adsw']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adsw');
		$menu['adsw']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adsw');
		//$menu['adsw']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adsw');
                    
		$menu['adsw']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adsw']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adsw']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		//$menu['adsw']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		//13
		/*$menu['adug']=array();
		$menu['adug']['Notices, Circulars or Minutes']=array();
		$menu['adug']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adug']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adug');
		$menu['adug']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adug');
		$menu['adug']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adug');
		            
		$menu['adug']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adug']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adug');
		$menu['adug']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adug');
		$menu['adug']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adug');
                    
		$menu['adug']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adug']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adug']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['adug']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		//admin_exam
		/*$menu['admin_exam']=array();
		$menu['admin_exam']['Notices, Circulars or Minutes']=array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/admin_exam');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/admin_exam');
		$menu['admin_exam']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['admin_exam']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		
		
		$menu['adac']=array();
		$menu['adac']['Notices, Circulars or Minutes']=array();
		$menu['adac']["Notices, Circulars or Minutes"]["Post"]=array();
		$menu['adac']["Notices, Circulars or Minutes"]["Post"]['Notice'] = site_url('information/post_notice/index/adac');
		$menu['adac']["Notices, Circulars or Minutes"]["Post"]['Circular'] = site_url('information/post_circular/index/adac');
		$menu['adac']["Notices, Circulars or Minutes"]["Post"]['Minutes'] = site_url('information/post_minute/index/adac');
		            
		$menu['adac']["Notices, Circulars or Minutes"]["Edit"]=array();
		$menu['adac']["Notices, Circulars or Minutes"]["Edit"]['Notice'] = site_url('information/edit_notice/index/adac');
		$menu['adac']["Notices, Circulars or Minutes"]["Edit"]['Circular'] = site_url('information/edit_circular/index/adac');
		$menu['adac']["Notices, Circulars or Minutes"]["Edit"]['Minutes'] = site_url('information/edit_minute/index/adac');
                    
		$menu['adac']["Notices, Circulars or Minutes"]["View"] = array();
		$menu['adac']["Notices, Circulars or Minutes"]["View"]['Notice'] = site_url('information/view_notice');
		$menu['adac']["Notices, Circulars or Minutes"]["View"]['Circular'] = site_url('information/view_circular');
		$menu['adac']["Notices, Circulars or Minutes"]["View"]['Minutes'] = site_url('information/view_minute');
		*/
		return $menu;
	}
}
/* End of file information_menu.php */
/* Location: mis/application/models/information/information_menu.php */