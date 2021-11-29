<?php

class  Stu_rank_menu_model extends CI_Model
{
    function getMenu()
	{
		//$menu=array();
        //        auth ==> acad_ar,
		$menu['acad_ar']=array();
		$menu['acad_ar']["Student Rank"] = site_url('stu_rank/main');
		
		$menu['exam_dr']=array();
		$menu['exam_dr']["Student Rank"] = site_url('stu_rank/main');
		
		//$menu['exam_da1']=array();
		//$menu['exam_da1']["Student Rank"] = site_url('stu_rank/main');
		
		                
                return $menu;
        }
}
