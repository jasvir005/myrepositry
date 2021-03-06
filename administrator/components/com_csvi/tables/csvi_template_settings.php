<?php
/**
 * Template settings table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2012 RolandD Cyber Produksi
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvi_template_settings.php 1981 2012-04-29 12:11:30Z RolandD $
 */

// No direct access
defined('_JEXEC') or die;

/**
* @package CSVI
 */
class TableCsvi_template_settings extends JTable {
	/** @var int Primary key */
	var $id = 0;
	/** @var string The name of the template */
	var $name = null;
	/** @var string The template settings */
	var $settings = null;
	/** @var string The type of template (import/export) */
	var $type = null;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		parent::__construct('#__csvi_template_settings', 'id', $db );
	}
}
?>