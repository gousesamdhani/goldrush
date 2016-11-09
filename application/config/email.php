<?php
/**
 * Short description for file : Email sending configuration
 *
 * PHP version 5.5.9-1
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    Application
 * @package     Config:Email
 * @author      Saikrishna <saikrishna@vendus.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/medicare/index.php/admin/dashboard/
 * @dateCreated 09/13/2015  MM/DD/YYYY
 * @dateUpdated 09/13/2015  MM/DD/YYYY  
 */
?>
<?php


$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = true;
$config['mailtype'] = 'html';

if (ENVIRONMENT == 'production') {
    $config['smtp_host'] = 'localhost';
} elseif (ENVIRONMENT == 'testing' || ENVIRONMENT == 'staging') {
    $config['protocol'] = 'sendmail';
    $config['smtp_host'] = '';
} elseif (ENVIRONMENT == 'development') {
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.gmail.com';
    $config['smtp_port'] = '465';
    $config['smtp_user'] = 'satyaraj@vendus.com';
    $config['smtp_pass'] = 'raj@9966331933';
}
