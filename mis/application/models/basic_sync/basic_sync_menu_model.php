<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of basic_syn_menu_model
 *
 * @author Your Name <Ritu Raj @ ISM DHANBAD>
 *//* Category  Data Import  
 * Copyright (c) ISM dhanbad * 
 * @category    phpExcel
 * @package    Basic_sync
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #28/9/16#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
*/
 
class Basic_sync_menu_model extends CI_Model
{
		
        function getMenu()
	{
		//$menu=array();		
                $menu['admin']=array();
		//$menu['admin']["Sync"] = site_url('exam_sync_master/exam_sync_master');		
                $menu['admin']["basic_sync"] = site_url('basic_sync/basic_sync');		
		return $menu;                                                
	}
}