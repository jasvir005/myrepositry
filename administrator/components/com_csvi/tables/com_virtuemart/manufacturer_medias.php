<?php
/**
 * Virtuemart manufacturer medias table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2012 RolandD Cyber Produksi
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: category_medias.php 1924 2012-03-02 11:32:38Z RolandD $
 */

// No direct access
defined('_JEXEC') or die;

/**
 * @package CSVI
 */
class TableManufacturer_medias extends JTable {

	/**
	 * Table constructor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	public function __construct($db) {
		parent::__construct('#__virtuemart_manufacturer_medias', 'id', $db);
	}

	/**
	 * Check if a media file reference already exists
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$media	the name of the file URL
	 * @return 		true if media reference exists, false if media reference does not exist
	 * @since 		4.0
	 */
	public function check() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($this->_tbl_key);
		$query->from($this->_tbl);
		$query->where('virtuemart_manufacturer_id = '.$db->Quote($this->virtuemart_manufacturer_id));
		$query->where('virtuemart_media_id = '.$db->Quote($this->virtuemart_media_id));
		$db->setQuery($query);
		$id = $db->loadResult();
		if ($id > 0) return true;
		else return false;
	}

	/**
	 * Reset the keys including primary key
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	public function reset() {
		// Get the default values for the class from the table.
		foreach ($this->getFields() as $k => $v) {
			// If the property is not private, reset it.
			if (strpos($k, '_') !== 0) {
				$this->$k = NULL;
			}
		}
	}
}
?>