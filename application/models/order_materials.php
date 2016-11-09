<?php
/**
 * Api_model Model
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    Api_Model.php
 * @package     Models
 * @author      Satya Raj.Ch <chsatyaraj93.ch@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/medicare/index.php/
 * @dateCreated 10/28/2015  MM/DD/YYYY
 * @dateUpdated 10/28/2015  MM/DD/YYYY 
 * @functions   01
 */
/**
 * Api_model.php
 *
 * @category Api_model.php
 * @package  Models
 * @author   Satya Raj.Ch <chsatyaraj93.ch@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://local.sureify.com/api_model
 */
class Order_materials extends CI_Model {

	protected $users = "users";
	protected $materials_master = "materials_master";
	protected $dates = "dates";
	protected $order_materials = "order_materials";
	protected $orders = "orders";
	protected $type_values = "type_values";
	protected $user_sessions = "user_sessions";
	protected $states = "states";
	protected $districts = "districts";
	protected $area = "area";

	/**
     * Construct
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */  
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
     * Truncates leaderboard cofederates table data
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function truncateTables()
	{
		    $date=date('Y-m-d');
            $this->db->query('DELETE FROM leaderboard_confederates_data WHERE DATE_FORMAT(captured_date,"%Y-%m-%d")="'.$date.'"');
	        //$this->db->query('truncate table leaderboard_confederates_timeslots_data');
	        
    }

    public function getOrderMaterials($order_number, $row_status = 1){
    	try{
    		$where_array['row_status'] = $row_status;
    		$where_array['order_number'] = $order_number;

			$this->db->select('material_id, material_qty, price, row_status');
    		//$this->db->where($where_array);
    		$this->db->from($this->order_materials);
    		$query = $this->db->get();
    		$result=array();
			$res = $query->result();
			$result=objectToArray($res);
			
			for($i = 0 ; $i < count($result); $i++){
				$ids['id'] = $result[$i]['material_id'];
				$this->load->model('Materials_Master');
    			$result[$i]['material_name'] = $this->Materials_Master->getAllMaterials($ids)[0]['name'];
			}
			//print_r($result);exit;
			if(count($result)>0)
				 return $result;
			else 
				return false;	

    	} catch(Exception $e){
    		return false;
    	}
    }

    public function insertOrderMaterials($order_data){
    	try {
    		$id = $this->db->insert_batch('order_materials', $order_data); 
    		if($id)
	    		return true;
	    	else
	    		return false;
    	} catch ( Exception $e) {
    		return false;
    	}
    }

    public function updateOrderMaterials($where_array,$what_array){
    	try {


    		$where_array['row_status'] = 1;
    		$where_array['order_number'] = $what_array[0]['order_number'];
    		//$what_array['row_status'] = 1;
    		//exit;
    		for($i = 0 ; $i < count($what_array); $i++) {
    			unset($what_array[$i]['order_number']);
				$where_array['material_id'] = $what_array[$i]['material_id'];
    			unset($what_array[$i]['material_id']);
    			//print_r($where_array);
    			//print_r($what_array);
    			$this->db->where($where_array);
				$this->db->update($this->order_materials, $what_array[$i]); 
				//echo $this->db->last_query().'\n';
    		}
    		
			return true;
		} catch(Exception $e){
			return false;
		}
    }

    public function deleteOrderMaterials($where_array){
    	try {


    		$where_array['row_status'] = 1;
    		//$what_array['row_status'] = 1;
    		//exit;
    		$what_array['row_status'] = 0;
    		$this->db->where($where_array);
			$this->db->update($this->order_materials, $what_array); 
			
    		
			return true;
		} catch(Exception $e){
			return false;
		}
    }
}
