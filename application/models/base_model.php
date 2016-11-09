<?php
/**
 * Base_model Model
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    Base_Model.php
 * @package     Models
 * @author      Vijay.Ch <vijay.ch@vendus.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/medicare/index.php/
 * @dateCreated 10/28/2015  MM/DD/YYYY
 * @dateUpdated 10/28/2015  MM/DD/YYYY 
 * @functions   01
 */

/*
  // Using this model

  class New_model extends Base_model {

  protected $table = 'new_models_table_name';

  //specify primary key in case its other than "id"
  protected $id = 'unique_id';

  public function __construct() {

  parent::__construct();
  $this->load->database();
  }

  // Your own functions go here...
  }
 */
/**
 * Base_model.php
 *
 * @category Base_Model.php
 * @package  Models
 * @author   Vijay.ch <vijay.ch@vendus.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://local.sureify.com/user
 */

class Base_Model extends CI_Model
{

    // var $table = $this->table;
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

        // default primary key is "id"
        if (empty($this->id)) {
            $this->id = 'id';
        }
    }

    // common methods can be used for any model (start)
     /**
     * Method to create a table row.
     * @param array $record records array
     * @return int
     */
    public function create($record) 
    {
        try {
            $insert = $this->db->insert($this->table, $record);
            //echo $this->db->last_query();exit;
            if ($insert) {
                $insert_id = $this->db->insert_id();
                return $insert_id;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Method to clone a table row.
     * @param array $where where array
     * @return int 
     */
    public function cloneRow($where = null) 
    {
        if ($where) {
            $this->db->where($where);
        }

        try {
            $query = $this->db->get($this->table);
            $result = $query->result();

            foreach ($result as $row) {
                $array = get_object_vars($row);
                unset($array['id']);
                $this->db->insert($this->table, $array);
            }
            $insert_id = $this->db->insert_id();
            return $insert_id;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Method to create multiple records
     * @param array $records multiple records array
     * @return boolean 
     */
    public function createMultiple($records) 
    {
        try {
            $this->db->insert_batch($this->table, $records);
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
     /**
     * Method to update records
     * @param array $record records array
     * @return void 
     */
    public function update($record) 
    {
        $record_id = $record[$this->id];
        unset($record[$this->id]);
        try {
            $this->db->update($this->table, $record, array($this->id => $record_id));
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
     /**
     * Method to update records by where condition
     * @param array $record records array
     * @param array $where  where array
     * @return void 
     */
    public function updateWhere($record, $where) 
    {
        try {
            $this->db->update($this->table, $record, $where);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
     /**
     * Method to get user values by id
     * @param int $id user id
     * @return object 
     */
    public function getById($id) 
    {
        $this->db->where($this->id, $id);

        try {
            $query = $this->db->get($this->table);
            $row = $query->row();
            return $row;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Gets a single record by column
     * @param  string     $column column name
     * @param  string/int $value  value
     * @return object - result set
     */
    public function getByColumn($column, $value) 
    {
        $this->db->where($column, $value);

        try {
            $query = $this->db->get($this->table);
            $row = $query->row();
            return $row;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }   
     /**
     * Method to get records
     * @param string $limit limit condition
     * @return object 
     */
    public function getAll($limit = null) 
    {
        if ($limit) {
            $this->db->limit($limit);
        }

        try {
            $query = $this->db->get($this->table);
            $result = $query->result_object();
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
    /**
     * Method to get records
     * @param array  $where where array 
     * @param string $limit limit condition
     * @param array  $order order array
     * @return object 
     */
    public function get($where = null, $limit = null, $order = array()) 
    {
        if ($where) {
            $this->db->where($where);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        if (count($order) > 0) {
            $this->db->order_by($order[0], $order[1]);
        }

        try {
            $query = $this->db->get($this->table);
            $result = $query->result_object();
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Gets single row
     * @param  array/string $where where array
     * @param  array        $order order array
     * @return object      
     */
    public function getRow($where = null, $order = array()) 
    {
        if ($where) {
            $this->db->where($where);
        }
        if (count($order) > 0) {
            $this->db->order_by($order[0], $order[1]);
        }

        try {
            $query = $this->db->get($this->table);
            $row = $query->row();
            return $row;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
    /**
     * Method to get records
     * @param array $opts options array
     * @return object 
     */
    public function getOnly($opts) 
    {
        $where = $opts['where'];
        $limit = $opts['limit'];
        $order = $opts['order'];
        $select = $opts['select'];

        if ($select) {
            $this->db->select($select);
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        if (count($order) > 0) {
            $this->db->order_by($order[0], $order[1]);
        }

        try {
            $query = $this->db->get($this->table);
            $result = $query->result_object();
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
    /**
     * Method to get records by where in condition
     * @param array  $field    field array
     * @param array  $where_in where in data
     * @param string $limit    limit condition
     * @return object 
     */
    public function whereIn($field, $where_in = array(), $limit = null) 
    {
        if (count($where_in) > 0) {
            $this->db->where_in($field, $where_in);
        }
        if ($limit) {
            $this->db->limit($limit);
        }

        try {
            $query = $this->db->get($this->table);
            $result = $query->result_object();
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
    /**
     * Method to delete records
     * @param int $id record id
     * @return boolean 
     */
    public function delete($id) 
    {
        if (is_array($id)) {
            $where = $id;
            try {
                $this->db->where($where)->delete($this->table);
                return true;
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        } else if ($id) {
            try {
                $this->db->where(array($this->id => $id))->delete($this->table);
                return true;
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
    /**
     * Method to check record exists
     * @param array $options options array
     * @return boolean 
     */
    public function exists($options = array()) 
    {
        $this->db->where($options);
        try {
            $query = $this->db->get($this->table);
            $result = $query->result_object();
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
    /**
     * Method to count records based on where condition
     * @param array $options options array
     * @return int 
     */
    public function count($options = array()) 
    {
        $this->db->select('count(*) AS count');
        if (count($options) > 0) {
            $this->db->where($options);
        }
        $this->db->from($this->table);

        try {
            $query = $this->db->get();
            $result = $query->result_object();
            $count = (int) $result[0]->count;
            return $count;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    // common methods can be used for any model (end)
}
