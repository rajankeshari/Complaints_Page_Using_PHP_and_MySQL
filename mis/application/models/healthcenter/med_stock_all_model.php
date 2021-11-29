<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Med_stock_all_model extends CI_Model {

    var $table = 'hc_medicine';
    var $manuf = 'hc_manufacturer';
    var $m_receive = 'hc_medi_receive';
    var $t_po = 'hc_pur_order';
    var $hc = 'hc_indent_description';
    var $hc_mstore = 'hc_mainstore_stock';
    var $hc_mexpdt = 'hc_medi_expdate';
    var $hc_med_gr = 'hc_med_group';

    public function __construct() {
        parent::__construct();
    }

    function get_opening_stock() {

        $query = $this->db->query("SELECT * FROM `hc_medicine` INNER JOIN `hc_mainstore_stock` ON `hc_medicine`.`m_id` =
                `hc_mainstore_stock`.`m_id`");
                //  echo $this->db->last_query(); die();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_counter_stock_details() {

        $sql = "SELECT a.m_id,b.m_name,a.cs_qty FROM hc_counter_master a
INNER JOIN hc_medicine b ON b.m_id=a.m_id ORDER BY a.m_id";

        $query = $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_counter_stock($mid) {
        $this->db->select_sum('cs_qty'); // from counter 
        $this->db->from('hc_counter_master');
        $this->db->where('m_id', $mid);
        $query = $this->db->get();
        if ($query->num_rows() > 0) { // 
            return $query->row();
        } else {
            return false;
        }
    }
    function get_mainstock_batch_number_details($mid){
        $sql = "SELECT concat(a.batchno,' (',a.qty,' )')AS batchno FROM hc_medi_expdate a WHERE a.m_id=?";

        $query = $this->db->query($sql,array($mid));


        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_mainstock_tot_qty_batch_number_details($mid){
        $sql = "SELECT sum(a.qty) as tot_batch_qty FROM hc_medi_expdate a WHERE a.m_id=?";
        $query = $this->db->query($sql,array($mid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_batch_number_details($mid){
        $sql = "SELECT concat(a.batch_no,' (',a.qty,' )')AS batchno FROM hc_counter_batch_no_detail a WHERE a.m_id=?";

        $query = $this->db->query($sql,array($mid));


        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_tot_qty_batch_number_details($mid){
        $sql = "SELECT sum(a.qty) as tot_batch_qty FROM hc_counter_batch_no_detail a WHERE a.m_id=?";
        $query = $this->db->query($sql,array($mid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_emergency_batch_number_details($mid){
        $sql = "SELECT concat(a.batch_no,' (',a.qty,' )')AS batch_no FROM hc_emergency_batch_no_detail a WHERE a.m_id=?";

        $query = $this->db->query($sql,array($mid));


        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_emergency_tot_qty_batch_number_details($mid){
        $sql = "SELECT sum(a.qty) as emergency_tot_batch_qty FROM hc_emergency_batch_no_detail a WHERE a.m_id=?";
        $query = $this->db->query($sql,array($mid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_emergency_master_tot_qty($mid){
        $sql = "SELECT sum(a.em_qty) as emergency_tot_qty FROM hc_emergency_counter_master a WHERE a.m_id=?";
        $query = $this->db->query($sql,array($mid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    function get_tot_qty_issued_to_patient($mid){
        $sql = "SELECT sum(a.qty) as tot_batch_qty FROM hc_counter_batch_no_detail a WHERE a.m_id=?";
        $query = $this->db->query($sql,array($mid));

        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
        
    }
    

}