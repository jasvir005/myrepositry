<?php
/**
 * Settings table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2012 RolandD Cyber Produksi
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: settings.php 1924 2012-03-02 11:32:38Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 */
class TableSettings extends JTable {

	/**
	 * Method Description
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		database A database connector object
	 * @return
	 * @since 		4.0
	 */
	public function __construct($db) {
		parent::__construct('#__csvi_settings', 'id', $db );
	}
}
?>