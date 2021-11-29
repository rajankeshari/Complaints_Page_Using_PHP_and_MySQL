<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class rank_list_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
                        $menu['admin_exam']['Performance Sheet'] = site_url('rank_list/rank_main');
			 $menu['exam_da1']['Performance Sheet'] = site_url('rank_list/rank_main');
			 $menu['exam_da2']['Performance Sheet'] = site_url('rank_list/rank_main');
			 $menu['acad_ar']['Performance Sheet'] = site_url('rank_list/rank_main');
			$menu['exam_dr']['Performance Sheet'] = site_url('rank_list/rank_main');
			$menu['dsw']['Performance Sheet'] = site_url('rank_list/rank_main');
			$menu['adsw']['Performance Sheet'] = site_url('rank_list/rank_main');
                        
                   /*     $menu['exam_da1']['Performance Sheet Minor'] = site_url('rank_list/rank_list_minor');
			 $menu['acad_ar']['Performance Sheet Minor'] = site_url('rank_list/rank_list_minor');
			$menu['exam_dr']['Performance Sheet Minor'] = site_url('rank_list/rank_list_minor');
			$menu['dsw']['Performance Sheet Minor'] = site_url('rank_list/rank_list_minor');
			$menu['adsw']['Performance Sheet Minor'] = site_url('rank_list/rank_list_minor');*/
                        
                        
                     /*   $menu['acad_ar']['Rank List'] = site_url('rank_list/rank_list_new');
			 $menu['exam_dr']['Rank List'] = site_url('rank_list/rank_list_new');
			$menu['dsw']['Rank List'] = site_url('rank_list/rank_list_new');
			$menu['adsw']['Rank List'] = site_url('rank_list/rank_list_new');
                         $menu['hod']['Rank List'] = site_url('rank_list/rank_list_new');
                          $menu['tnp']['Rank List'] = site_url('rank_list/rank_list_new');
                          $menu['admin_exam']['Rank List'] = site_url('rank_list/rank_list_new');
			 $menu['exam_da1']['Rank List'] = site_url('rank_list/rank_list_new');*/
                         
            
            return $menu;
        }
        
}