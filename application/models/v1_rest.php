<?php

/**
 * V1_Rest Model
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    V1_Rest.php
 * @package     Models
 * @author      Satya Raj.Ch <satyaraj@vendus.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/medicare/index.php/
 * @dateCreated 11/12/2015  MM/DD/YYYY
 * @dateUpdated 11/12/2015  MM/DD/YYYY 
 * @functions   01
 */

/**
 * V1_Rest.php
 *
 * @category V1_Rest.php
 * @package  Models
 * @author   Satya Raj.Ch <satyaraj@vendus.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://local.sureify.com/user
 */
class V1_Rest extends Base_model
{

    protected $table = 'quote_users';
    protected $users = 'users';
    protected $user_info = 'user_info';
    protected $user_steps = 'user_steps';
    protected $user_calories = 'user_calories';
    protected $user_sessions = 'user_sessions';
    protected $user_weights = 'user_weights';
    protected $devices = "devices_master";
    protected $policies = "policies";
    protected $user_savings = 'user_savings';
    protected $user_devices = "user_devices";
    protected $user_cards = "user_cards";


    /**
     * Construct
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */  
    public function __construct() 
    {

        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
    }

    /**
     * Returns user session in case if user is logged in
     * @return int
     */
    public function loggedIn() 
    {
        return $this->session->userdata('uid');
    }

    /**
     * Gets current user object
     * @return object
     */
    public function getCurrentUser() 
    {
        $uid = $this->session->userdata('uid');
        if ($uid) {
            return $this->getById($uid);
        }
        return false;
    }

    /**
     * Logs user out
     * @return boolean
     */
    public function logOut() 
    {
        return $this->session->unset_userdata('uid');
    }

    /**
     * Logs user out
     * @return boolean
     */
    public function adminLogOut() 
    {
        return $this->session->unset_userdata('admin');
    }

    /**
     * Detects admin session
     * @return boolean
     */
    public function isAdmin() 
    {
        $admin = $this->session->userdata('admin');
        if ($admin) {
            return true;
        }
        return false;
    }

    /**
     * Export the user details into the excel sheet or csv
     * @return void
     */
    public function exportusers() 
    {
        $this->db->select("name as 'Name', email as 'Email', age as Age, case gender WHEN 1 then 'Male' else 'Female' end as Gender, area_code as Zip, case smoker WHEN 0 then 'No' else 'Yes' end as Smoke, case health WHEN 1 then 'Excellent' WHEN 2 then 'Good' when 3 then 'Average' else 'Poor' end as Health, duration as Duration, created_at as 'Registered Date', origin as Origin", false);
        //$this->db->where('rec_status', '1');
        $this->db->from('users');
        $this->db->order_by("id asc");
        $query = $this->db->get();
        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Sureify Users.csv', $data);
        exit;
    }


    /**
     * Authentication
     * @param  $where_array, $group_by, $order_by
     * @return array
     */
    public function authentication($where_array = array(), $group_by = array(), $order_by = array()){

        $this->db->select('id, email, password');
        $this->db->from('users');
        

        if(count($where_array) > 0){
            $this->db->where($where_array);
            //return $where_array;
        }

        if (count($group_by) > 0) {
            foreach ($group_by as $gb) {
                $this->db->group_by($gb);
            }
        }

        if (count($order_by) > 0) {
            
        }
        $query = $this->db->get();

        if (!$query) {
            throw new Exception(); //throws exception if query not retrived
        }
        
        $result = array();
        //$result = $query->result_array(); //retrive result in array format
        
        $result = $query->row();

        return $result;
    }

    /**
     * Add user session
     * @param  array $user_session_data session data array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function addUserSession($user_session_data)
    {
        try {
            $this->db->trans_begin();
            $user_session_data['login_time']=date("Y-m-d H:i:s");
            $user_session_data['created_time'] = $user_session_data['access_token'] = date("Y-m-d H:i:s");
            $insert_user_session=$this->db->insert($this->user_sessions, $user_session_data);
            $user_session_id=$this->db->insert_id();
            $this->db->update($this->app_users,array('last_login' => $user_session_data['login_time']));
            
            //echo $this->db->last_query();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return array('user_session_id'=>$user_session_id);
            }
        } catch (Exception $e) {
            log_message('error', $this->db->_error_message());
            $this->db->trans_rollback();
            return 0;
        }
     
    }   

    /**
     * Checking Email exists or not
     * @param string $email User Email
     * @return true / false
     */
    public function checkEmailExist($email) 
    {
        $query = $this->db->get_where(
            'users', array(
            "email" => $email,
            "row_status" => 1
                )
        );
        //print_r($email1);exit;
        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checking authentication-token exists or not
     * @param string auth-token
     * @return true / false
     */
    public function checkAuthTokenExist($user_access_token) 
    {
        $this->db->select('us.logout_time', FALSE);
        $this->db->from($this->user_sessions . ' us');
        $this->db->where('us.access_token', $user_access_token);

        $query = $this->db->get();
        // $result = array();
        $result = $query->row();

        //print_r($email1);exit;
        if ($query->num_rows() > 0) {
            return $result;
        } else {
            return false;
        }
    }


    /**
     * Check for User Session Id
     * @param string user_session_id
     * @return true / false
     */
    public function checkUserSession($where_array){
        
        $this->db->select('id, logout_time', FALSE);
        $this->db->from($this->user_sessions);
        $this->db->where($where_array);

        $query = $this->db->get();
       // $result = array();
        $result = $query->row();

        if($result->logout_time != null)
            return false;

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Logging out
     * @param string user_session_id
     * @return true / false
     */
    public function removeUserSession($where_array){
        
        try {
            $this->db->trans_begin();
            
            $user_session_data['logout_time'] = date('Y-m-d H:i:s');
            
            $this->db->where($where_array);
            
            $this->db->update($this->user_sessions, $user_session_data); 
            //echo $this->db->last_query();exit();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }

        } catch (Exception $e) {
            log_message('error', $this->db->_error_message());
            $this->db->trans_rollback();
            return false;
        }
    }  
}
?>