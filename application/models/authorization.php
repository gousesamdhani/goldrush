<?php

/**
 * Authorization Model
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    Authorization.php
 * @package     Models
 * @author      Vijay.Ch <vijay.ch@vendus.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/cacsv2/index
 * @dateCreated 11/03/2015  MM/DD/YYYY
 * @dateUpdated 11/03/2015  MM/DD/YYYY 
 * @functions   2
 */

/**
 * Authorization Model
 *
 * @category    Authorization.php
 * @package     Models
 * @author      Vijay.Ch <vijay.ch@vendus.com>
 * @fileName    Authorization.php
 * @description Used for if user logged, and which role based access
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/cacsv2/index
 */
class authorization extends CI_Model {

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
     * Here checking admin auth in session varible
     * @return boolean
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function check_adminauth() {
        $userSession = $this->session->userdata;
        if (!empty($userSession) && isset($userSession["admin_session"]) && isset($userSession["admin_session"]["id"]) && $userSession["admin_session"]["id"] !="") {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Here checking carrier auth in session varible
     * @return boolean
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function check_carrierauth() {
        $userSession = $this->session->userdata;
        if (!empty($userSession) && isset($userSession["carrier_session"]) && isset($userSession["carrier_session"]["id"]) && $userSession["carrier_session"]["id"] !="") {
            return true;
        } else {
            return false;
        }
    }

}

?>