<?php
/**
 * Category import
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2012 RolandD Cyber Produksi
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: categoryimport.php 2048 2012-07-28 16:27:43Z RolandD $
 */

defined('_JEXEC') or die;

/**
 * Processor for category details
 *
 * Main processor for importing categories.
 *
 * @todo 	Check vendor ID
 */
class CsviModelCategoryimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_category table */
	private $_categories = null;
	/** @var object contains the vm_media table */
	private $_medias = null;
	/** @var object contains the vm_media table */
	private $_category_medias = null;
	/** @var object contains the vm_media table */
	private $_categories_lang = null;

	// Public variables
	public $category_path = null;
	/** @var integer contains the category ID for a product */
	public $virtuemart_category_id = null;
	/** @var array contains the setting if the category needs to be deleted */
	public $category_delete = null;
	/** @var int contains the name of the full image */
	public $file_url = null;
	/** @var int contains the name of the thumbnail image */
	public $file_url_thumb = null;
	public $file_title = null;
	public $file_description = null;
	public $file_meta = null;
	public $file_ordering = null;
	public $published = null;

	// Private variables
	/** @var bool set if settings are loaded or not */
	private $_settings_loaded = false;
	/** @var object contains general category functions */
	private $_categorymodel = null;
	/** @var string Category separator */
	private $_catsep = null;
	private $_tablesexist = true;

	/**
	 * Constructor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.4
	 */
	public function __construct() {
		parent::__construct();
		// Load the tables that will contain the data
		$this->_loadTables();
		$this->loadSettings();
		
		// Set some initial values
		$this->date = JFactory::getDate();
		$this->user = JFactory::getUser();
    }

	/**
	 * Here starts the processing
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getStart() {
		// Get the logger
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		
		// Only continue if all tables exist
		if ($this->_tablesexist) {
			
			// Load the data
			$this->loadData();
	
			// Get the general category functions
			$this->_categoriesmodel = $this->getModel('category');
			$this->_categoriesmodel->getStart();
	
			// Load the helper
			$this->helper = new Com_VirtueMart();
	
			// Check for vendor ID
			$this->virtuemart_vendor_id = $this->helper->getVendorId();
	
			// Process data
			foreach ($this->csvi_data as $name => $fields) {
				foreach ($fields as $filefieldname => $details) {
					$value = $details['value'];
					// Check if the field needs extra treatment
					switch ($name) {
						case 'published':
							switch ($value) {
								case 'n':
								case 'N':
								case '0':
									$value = 0;
									break;
								default:
									$value = 1;
									break;
							}
							$this->published = $value;
							break;
						default:
							$this->$name = $value;
							break;
					}
				}
			}
	
			// If we have no category path we cannot continue
			if (empty($this->category_path)) {
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_CATEGORY_PATH_SET'));
				return false;
			}
			return true;
		}
		else {
			$template = $jinput->get('template', null, null);
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_LANG_TABLE_NOT_EXIST', $template->get('language', 'general')));
			return false;
		}
	}

	/**
	 * Load the tables
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _loadTables() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		
		$this->_categories = $this->getTable('categories');
		$this->_medias = $this->getTable('medias');
		$this->_category_medias = $this->getTable('category_medias');
		
		// Check if the language tables exist
		$db = JFactory::getDbo();
		$tables = $db->getTableList();
		if ($template->get('language', 'general') == $template->get('target_language', 'general')) $lang = $template->get('language', 'general');
		else $lang = $template->get('target_language', 'general');
		if (!in_array($db->getPrefix().'virtuemart_categories_'.$lang, $tables)) {
			$this->_tablesexist = false;
		}
		else {
			$this->_tablesexist = true;
			$this->_categories_lang = $this->getTable('categories_lang');
		}
	}

	/**
	 * Cleaning the tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.0
	 */
	protected function cleanTables() {
		$this->_categories->reset();
		$this->_medias->reset();
		$this->_category_medias->reset();
		$this->_categories_lang->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}

	/**
	 * Process each record and store it in the database
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getProcessRecord() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$translate = false;
		
		// Load the category separator
		if (is_null($this->_catsep)) {
			$this->_catsep = $template->get('category_separator', 'general', '/');
		}
		
		// Loop through all categories if we are importing a translation
		if (isset($this->category_path_trans)) {
			$trans_paths = explode($this->_catsep, $this->category_path_trans);
			$paths = explode($this->_catsep, $this->category_path);
			if (!is_array($paths)) $paths = (array)$paths;
			$translate = true;
		}
		else if ($template->get('language', 'general') == $template->get('target_language', 'general')) {
			$trans_paths = array($this->category_path);
			$paths = array($this->category_path);
		}
		else {
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CATEGORY_LANGUAGE_UNKNOWN', $template->get('language', 'general'), $template->get('target_language', 'general')));
			return false;
		}
		
		// Process the paths
		foreach ($paths as $key => $path) {
			// Construct the full path
			$fullpath = array();
			for($i=0; $i<= $key; $i++) {
				$fullpath[] = $paths[$i];
			}
			$path = implode($this->_catsep, $fullpath);
			
			// First get the category ID
			if (empty($this->virtuemart_category_id)) {
				// Check if we are importing a translation
				$categoryid = $this->_categoriesmodel->getCategoryIdFromPath($path);
				// If we can't get a category ID we cannot continue
				if (!$categoryid) {
					$csvilog->AddStats('incorrect', JText::_('COM_CSVI_COULD_NOT_FIND_A_CATEGORY_ID'));
					return false;
				}
				else $this->virtuemart_category_id = $categoryid['category_id'];
			}
	
			// We have the category ID, lets see if it should be deleted
			if ($this->category_delete == 'Y') {
				$this->_deleteCategory();
			}
			else {
				// Handle the images
				$this->_processMedia();
	
				// Set some basic values
				if (!isset($this->modified_on)) {
					$this->_categories->modified_on = $this->date->toMySQL();
					$this->_categories->modified_by = $this->user->id;
				}
	
				// Add a creating date if there is no product_id
				if (empty($this->virtuemart_category_id)) {
					$this->_categories->created_on = $this->date->toMySQL();
					$this->_categories->created_by = $this->user->id;
				}
	
				// Check if the category_name matches the last entry in the category_path
				if (isset($this->category_name)){
					$catparts = explode($this->_catsep, $this->category_path);
					end($catparts);
					if (current($catparts) != $this->category_name) {
						$csvilog->AddStats('incorrect', JText::_('COM_CSVI_CATEGORY_NAME_NO_MATCH_CATEGORY_PATH'));
						return false;
					}
				}
	
				// All fields have been processed, bind the data
				$this->_categories->bind($this);
	
				// Now store the data
				if ($this->_categories->store()) {
					if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_CATEGORY_DETAILS'));
					else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_CATEGORY_DETAILS'));
				}
				else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CATEGORY_DETAILS_NOT_ADDED', $this->_categories->getError()));
				
				// Store the debug message
				$csvilog->addDebug(JText::_('COM_CSVI_CATEGORY_DETAILS_QUERY'), true);
	
				// Set the product ID
				$this->virtuemart_category_id = $this->_categories->virtuemart_category_id;
				
				// Store the language fields
				$this->_categories_lang->load($this->virtuemart_category_id);
				$this->_categories_lang->bind($this);
				
				// Set the translated category name
				if ($translate) {
					$this->_categories_lang->category_name = $trans_paths[$key];
				}
	
				// Check and store the language data
				if ($this->_categories_lang->check()) {
					if ($this->_categories_lang->store()) {
						if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_CATEGORY_LANG'));
						else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_CATEGORY_LANG'));
					}
					else {
						$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CATEGORY_LANG_NOT_ADDED', $this->_categories_lang->getError()));
						return false;
					}
				}
				else {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CATEGORY_LANG_NOT_ADDED', $this->_categories_lang->getError()));
					return false;
				}
	
				// Store the debug message
				$csvilog->addDebug(JText::_('COM_CSVI_CATEGORY_DETAILS_QUERY'), true);
			}
	
			// Clean the tables
			$this->cleanTables();
			$this->virtuemart_category_id = null;
		}
	}

	/**
	 * Delete a category and its references
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
	private function _deleteCategory() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Delete the product
		if ($this->_categories->delete($this->virtuemart_category_id)) {
			$csvilog->AddStats('deleted', JText::_('COM_CSVI_CATEGORY_DELETED'));

			$db = JFactory::getDbo();
			// Delete category translations
			jimport('joomla.language.helper');
			$languages = array_keys(JLanguageHelper::getLanguages('lang_code'));
			foreach ($languages as $language){
				$query = $db->getQuery(true);
				$query->delete('#__virtuemart_categories_'.strtolower(str_replace('-', '_', $language)));
				$query->where('virtuemart_category_id = '.$this->virtuemart_category_id);
				$db->setQuery($query);
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_CATEGORY_LANG_XREF'), true);
				$db->query();
			}

			// Delete category reference
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_category_categories');
			$query->where('category_child_id = '.$this->virtuemart_category_id);
			$db->setQuery($query);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_CATEGORY_XREF'), true);
			$db->query();

			// Delete media
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_category_medias');
			$query->where('virtuemart_category_id = '.$this->virtuemart_category_id);
			$db->setQuery($query);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_MEDIA_XREF'), true);
			$db->query();

			// Reset the products that link to this category
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_product_categories');
			$query->where('virtuemart_category_id = '.$this->virtuemart_category_id);
			$db->setQuery($query);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_PRODUCT_CATEGORY_XREF'), true);
			$db->query();
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_CATEGORY_NOT_DELETED'));
		}
	}

	/**
	 * Process media files
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _processMedia() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);
		// Check if any image handling needs to be done
		if ($template->get('process_image', 'image', false)) {
			if (!is_null($this->file_url)) {
				// Create an array of images to process
				$images = explode('|', $this->file_url);
				$thumbs = explode('|', $this->file_url_thumb);
				$titles = explode('|', $this->file_title);
				$descriptions = explode('|', $this->file_description);
				$metas = explode('|', $this->file_meta);
				$order = explode('|', $this->file_ordering);
				$ordering = 1;
				// Image handling
				$imagehelper = new ImageHelper;

				foreach ($images as $key => $image) {
					$image = trim($image);
					if (!empty($image)) {
						// Verify the original image
						if ($imagehelper->isRemote($image)) {
							$original = $image;
							$remote = true;
							$full_path =  $template->get('file_location_category_images', 'path');
						}
						else {
							$original = $template->get('file_location_category_images', 'path').$image;
							$remote = false;
							
							// Get subfolders
							$path_parts = pathinfo($original);
							$full_path = $path_parts['dirname'].'/';
						}
		
						$file_details = $imagehelper->ProcessImage($original, $full_path);
						
						if ($file_details['exists']) {
							// Check if the image is an external image
							if (substr($file_details['name'], 0, 4) == 'http') {
								$csvilog->AddStats('incorrect', 'COM_CSVI_VM_NOSUPPORT_URL');
							}
							else {
								$title = (isset($titles[$key])) ? $titles[$key] : $file_details['output_name'];
								$description = (isset($descriptions[$key])) ? $descriptions[$key] : '';
								$meta = (isset($metas[$key])) ? $metas[$key] : '';
								$media = array();
								$media['virtuemart_vendor_id'] = $this->virtuemart_vendor_id;
								if ($template->get('autofill', 'image')) {
									$media['file_title'] = $file_details['output_name'];
									$media['file_description'] = $file_details['output_name'];
									$media['file_meta'] = $file_details['output_name'];
								}
								else {
									$media['file_title'] = $title;
									$media['file_description'] = $description;
									$media['file_meta'] = $meta;
								}
								$media['file_mimetype'] = $file_details['mime_type'];
								$media['file_type'] = 'category';
								$media['file_is_product_image'] = 0;
								$media['file_is_downloadable'] = 0;
								$media['file_is_forSale'] = 0;
								$media['file_url'] = (empty($file_details['output_path'])) ? $file_details['output_name'] : $file_details['output_path'].$file_details['output_name'];
								$media['published'] = $this->published;
									
								// Create the thumbnail
								if ($file_details['isimage']) {
									$thumb = (isset($thumbs[$key])) ? $thumbs[$key] : null;
									if ($template->get('thumb_create', 'image')) {
										if (empty($thumb)) $thumb = 'resized/'.basename($media['file_url']);
										$media['file_url_thumb'] = $imagehelper->createThumbnail($media['file_url'], $template->get('file_location_category_images', 'path'), $thumb);
									}
									else {
										$media['file_url_thumb'] = (empty($thumb)) ? $media['file_url'] : $file_details['output_path'].$thumb;
										if (substr($media['file_url_thumb'], 0, 4) == 'http') {
											$csvilog->addDebug(JText::sprintf('COM_CSVI_RESET_THUMB_NOHTTP', $media['file_url_thumb']));
											$media['file_url_thumb'] = '';
										}
									}
								}
								else {
									$media['file_is_product_image'] = 0;
									$media['file_url_thumb'] = '';
								}
						
								// Bind the media data
								$this->_medias->bind($media);
									
								// Check if the media image already exists
								$this->_medias->check();
									
								// Store the media data
								if ($this->_medias->store()) {
									if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_MEDIA'));
									else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_MEDIA'));
						
									// Store the debug message
									$csvilog->addDebug('COM_CSVI_MEDIA_QUERY', true);
						
									// Watermark the image
									if ($template->get('full_watermark','image') && $file_details['isimage']) $imagehelper->addWatermark(JPATH_SITE.'/'.$media['file_url']);
						
									// Store the category image relation
									$data = array();
									$data['virtuemart_category_id'] = $this->virtuemart_category_id;
									$data['virtuemart_media_id'] = $this->_medias->virtuemart_media_id;
									$data['ordering'] = (isset($order[$key]) && !empty($order[$key])) ? $order[$key] : $ordering;
									$this->_category_medias->bind($data);
									if (!$this->_category_medias->check()) {
										if ($this->_category_medias->store()) {
											$csvilog->addDebug('COM_CSVI_STORE_CATEGORY_IMAGE_RELATION', true);
											$ordering++;
										}
									}
									else {
										$csvilog->addDebug('Product image relation already exists');
									}
								}
								else {
									$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MEDIA_NOT_ADDED', $this->_medias->getError()));
									return false;
								}
								// Reset the product media table
								$this->_medias->reset();
								$this->_category_medias->reset();
							} // else
						} // if
					}
				}
			}
		}
	}
}
?>