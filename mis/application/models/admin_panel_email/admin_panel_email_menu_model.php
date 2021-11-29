<?php
class Admin_panel_email_menu_model extends CI_Model{
    
    /*
        Description : This function returns the menu associative array containing the controller url.
    */
    function getMenu(){
        /*
            i)	$menu:- (datatype: associative array) Contains the key labeled as “email_admin” along with corresponding value being another associative array with key “Admin Panel” with corresponding value being the url linking to the appropriate controller.
The “email_admin” over here is the auth type. The auth type specifies the user who will be able access and use the Email Admin Panel. “Admin Panel” is the title received by the menu in the panel.

        */
        $menu = [
            "email_admin"=>
            [
                "Admin Panel"=>site_url('admin_panel_email/admin_panel_email'),
            ],
        ];
        return $menu;
    }
    
}
?>