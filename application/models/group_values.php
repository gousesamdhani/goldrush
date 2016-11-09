<?php

/**
 * GroupValues Model
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    GroupValues.php
 * @package     Models
 * @author      Vijay.Ch <vijay.ch@vendus.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/cacsv2/index
 * @dateCreated 11/09/2015  MM/DD/YYYY
 * @dateUpdated 11/09/2015  MM/DD/YYYY 
 * @functions   2
 */

/**
 * GroupValues Model
 *
 * @category    GroupValues.php
 * @package     Models
 * @author      Vijay.Ch <vijay.ch@vendus.com>
 * @fileName    GroupValues.php
 * @description Used for if user logged, and which role based access
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/cacsv2/index
 */
class group_values extends CI_Model {

    /**
     * Construct
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function __construct() {
        parent::__construct();
    }

    
    /**
     * Fetching the group values data based on group id
     * @param Int $groupId Group id
     * @return boolean
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getGroupValuesById($groupId) {
        $this->db->select('id,name,description');
        $this->db->where(array(
            'row_status' => 1,
            'group_id' => $groupId
        ));
        $query = $this->db->get('group_values');
        if ($query->num_rows() > 0) {
           $result = $query->result_array();
           $temp=array(''=>"Select a Type");
           foreach ($result as $key => $value) {
               $temp[$value['id']] = ucwords($value['name']);
           }
           return $temp;
        } else {
            return array();
        }
    }

}

?>