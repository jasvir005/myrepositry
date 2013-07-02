<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz
 * @author RolandD,
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 5151 2011-12-19 17:10:23Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// addon for joomla modal Box
JHTML::_ ( 'behavior.modal' );
JHTML::_('behavior.tooltip');
$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id='.$this->product->virtuemart_product_id.'&virtuemart_category_id='.$this->product->virtuemart_category_id.'&tmpl=component');


$app		=	JFactory::getApplication('site');
$template	=	$app->getTemplate(); 
 
$flexibleGlobalCSSpath		=	'templates/'.$template.'/html/com_virtuemart/assets/css/';
$flexibleGlobalCSSfilename	=	"flexibleVM2Global.css";
$FlexibleImagePATH = 'templates/'.$template.'/html/com_virtuemart/assets/images/';
$FlexiblePATH = 'templates/'.$template.'/html/com_virtuemart/assets/Flexible/';
$jQueryPATH = "https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/";

$JSjQuery = 'jquery.min.js';
$JSTab = 'tabcontent.js'; 
$CSSTab = 'tabcontent.css';
$JSZoom = 'flexible-zoom.min.js'; 
$CSSZoom = 'flexible-zoom.css';
 
// flexible-zoom.min.js must be loadded after jquery.js
JHTML::stylesheet($flexibleGlobalCSSfilename, $flexibleGlobalCSSpath);
JHTML::script($JSjQuery, $jQueryPATH);
JHTML::script($JSTab, $FlexiblePATH);
JHTML::stylesheet($CSSTab, $FlexiblePATH);
JHTML::script($JSZoom, $FlexiblePATH);
JHTML::stylesheet($CSSZoom, $FlexiblePATH);



$document = &JFactory::getDocument();
$document->addScriptDeclaration("
	jQuery(document).ready(function($) {
		$('a.ask-a-question').click(function(){
			$.facebox({
				iframe: '".$url."',
				rev: 'iframe|550|550'
			});
			return false ;
		});
		 
		 
		 
		 
	});
");
/* Let's see if we found the product */
if (empty ( $this->product )) {
	echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_NOT_FOUND' );
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}
?>
<!--
<script src="<?php echo $FlexiblePATH; ?>flexible-zoom.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery.fn.flexibleZoom.defaults = {
        tint: false,
		zoomWidth: '320',
        zoomHeight: '370',
        position: 'right',
        tintOpacity: 0.5,
        lensOpacity: 1,
        softFocus: false,
        smoothMove: 5,
        showTitle: true,
        titleOpacity: 0.5,
        adjustX: 30,
        adjustY: 0
    };
	</script> -->
    
    
 <?php    
$parameter = $_GET['flexible'];
if ($parameter == "largeview") {
	$jsFlexible = "
jQuery_1_5_2.fn.flexibleZoom.defaults = {
        tint: false,
		zoomWidth: '350',
        zoomHeight: '370',
        position: 'inside',
        tintOpacity: 0.5,
        lensOpacity: 1,
        softFocus: false,
        smoothMove: 5,
        showTitle: true,
        titleOpacity: 0.5,
        adjustX: 10,
        adjustY: 0
    };";
$doc =& JFactory::getDocument();
$doc->addScriptDeclaration($jsFlexible);
?>

<!-- Flexible Web Design Zoom Effect START -->
<?php // Product Main Image
if (!empty($this->product->images[0])) { ?>
<div class="main-image-ENLARGE width70">
<a href="<?php echo $this->product->images[0]->file_url;?>" class = 'flexible-zoom' id='zoom1' title="<?php echo $this->product->product_name ?>" ><img src="<?php echo $this->product->images[0]->file_url;?>" alt="<?php echo $this->product->product_name ?>" class="product-image" /></a>
</div>
<?php } // Product Main Image END ?>
 
<div class="flexible-zoom-additionalImagesLArgeVIEW width30">
<div class="FlexibleProductDetailProductName"><?php echo $this->product->product_name ?></div>
<?php // Showing The Additional Images
if(!empty($this->product->images) && count($this->product->images)>1) { ?>

<?php // List all Images
$i = 0;
foreach ($this->product->images as $image) {
$ImageId = $i++;
?>
<a href="<?php echo $this->product->images[$ImageId]->file_url;?>" class="flexible-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?php echo JURI::root(); ?><?php echo $this->product->images[$ImageId]->file_url;?>'"><img src="<?php echo $this->product->images[$ImageId]->file_url_thumb;?>" class="zoom-tiny-image-additional" style="height:80px; width:auto;" /></a>
<?php	} ?>
</div>
<?php	 } // Showing The Additional Images END ?>
<!-- Flexible Web Design Zoom Effect END -->


<?php } else { 

$jsFlexible = "
jQuery_1_5_2.fn.flexibleZoom.defaults = {
        tint: false,
		zoomWidth: '350',
        zoomHeight: '370',
        position: 'right',
        tintOpacity: 0.5,
        lensOpacity: 1,
        softFocus: false,
        smoothMove: 5,
        showTitle: true,
        titleOpacity: 0.5,
        adjustX: 10,
        adjustY: 0
    };";
$doc =& JFactory::getDocument();
$doc->addScriptDeclaration($jsFlexible);

?>    
    
    
    
    
    
    
    
    
    
<div class="productdetails-view">

	<?php
    // Product Navigation
    if (!($parameter == "quickbuy") && (VmConfig::get('product_navigation', 1))) { ?>
	 <div class="product-neighbours" style="padding-bottom:10px;">
	    <?php
	    if (!empty($this->product->neighbours ['previous'][0])) {
		$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id);
		echo JHTML::_('link', $prev_link, $this->product->neighbours ['previous'][0]
			['product_name'], array('class' => 'previous-page'));
	    }
	    if (!empty($this->product->neighbours ['next'][0])) {
		$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id);
		echo JHTML::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'], array('class' => 'next-page'));
	    }
	    ?>
    	<div class="clear"></div>
        </div>
    <?php } // Product Navigation END
    ?>
	

	<div>
		<div class="width35 floatleft">

	<div class="FlexProductDetailV2left">	
     
<!-- Flexible Web Design Zoom Effect START -->
<?php // Product Main Image
if (!empty($this->product->images[0]->file_url_thumb)) { ?>
<div class="main-image">
<a href="<?php echo $this->product->images[0]->file_url;?>" class = 'flexible-zoom' id='zoom1' title="<?php echo $this->product->product_name ?>" ><img src="<?php echo $this->product->images[0]->file_url;?>" alt="<?php echo $this->product->product_name ?>" class="product-image" /></a>
</div>
<?php } else {
	echo $this->product->images[0]->displayMediaThumb("",false);
}
	 // Product Main Image END ?>
</div>
<?php // if no image is uploaded, large view icon doesn't appear
if (!empty($this->product->images[0]->file_url_thumb)) { ?>
<a href="<?php echo 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component&flexible=largeview'; ?>" class="modal" rel="{handler: 'iframe', size: {x: 1100, y: 650}}"><img src="<?php echo $FlexibleImagePATH; ?>Flexible/largerView.png" style="float:right;" width="85" height="20" /></a>
<?php } ?>


<?php // Showing The Additional Images
if(!empty($this->product->images) && count($this->product->images)>1) { ?>
<div class="flexible-zoom-additionalImages" style="text-align:left; margin-top:25px;">
<?php // List all Images
$i = 0;
foreach ($this->product->images as $image) {
$ImageId = $i++;
?>
<a href="<?php echo $this->product->images[$ImageId]->file_url;?>" class="flexible-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?php echo JURI::root(); ?><?php echo $this->product->images[$ImageId]->file_url;?>'"><img src="<?php echo $this->product->images[$ImageId]->file_url_thumb;?>" class="zoom-tiny-image-additional" style="height:50px; width:auto;" /></a>
<?php	} ?>
</div>
<?php	 } // Showing The Additional Images END ?>
<!-- Flexible Web Design Zoom Effect END -->

<br/><Br/> 


		</div>

		<div class="width65 floatright">
			<div class="spacer-buy-area">
	<?php // Product Title ?>
	<div class="FlexibleProductDetailProductName"><?php echo $this->product->product_name ?></div>
	<?php // Product Title END ?>

	<?php // Product Edit Link
	echo $this->edit_link;
	// Product Edit Link END ?>
				<?php // TO DO in Multi-Vendor not needed at the moment and just would lead to confusion
				/* $link = JRoute::_('index2.php?option=com_virtuemart&view=virtuemart&task=vendorinfo&virtuemart_vendor_id='.$this->product->virtuemart_vendor_id);
				$text = JText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL');
				echo '<span class="bold">'. JText::_('COM_VIRTUEMART_PRODUCT_DETAILS_VENDOR_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a><br />
				*/ ?>

				<?php
				if($this->showRating){
				    $maxrating = VmConfig::get('vm_maximum_rating_scale',5);
					if (empty($this->rating)) 
					echo JText::_('COM_VIRTUEMART_RATING').' '.JText::_('COM_VIRTUEMART_UNRATED');
				
					if (!empty($this->rating)) {
						 
							if (round($this->rating->rating, 1) <= 5 && round($this->rating->rating, 1) > 4.5) { $ratingStar = 5;}
							if (round($this->rating->rating, 1) <= 4.5 && round($this->rating->rating, 1) > 4) { $ratingStar = 4.5;}
							if (round($this->rating->rating, 1) <= 4 && round($this->rating->rating, 1) > 3.5) { $ratingStar = 4;}
							if (round($this->rating->rating, 1) <= 3.5 && round($this->rating->rating, 1) > 3) { $ratingStar = 3.5;}
							if (round($this->rating->rating, 1) <= 3 && round($this->rating->rating, 1) > 2.5) { $ratingStar = 3;}
							if (round($this->rating->rating, 1) <= 2.5 && round($this->rating->rating, 1) > 2) { $ratingStar = 2.5;}
							if (round($this->rating->rating, 1) <= 2 && round($this->rating->rating, 1) > 1.5) { $ratingStar = 2;}
							if (round($this->rating->rating, 1) <= 1.5 && round($this->rating->rating, 1) > 1) { $ratingStar = 1.5;}
							if (round($this->rating->rating, 1) <= 1 && round($this->rating->rating, 1) > 0.5) { $ratingStar = 1;}
							if (round($this->rating->rating, 1) <= 0.5 && round($this->rating->rating, 1) > 0) { $ratingStar = 0.5;} 
						 
						?>	 
					
                    <img src="<?php echo $FlexibleImagePATH; ?>/Flexible/stars_<?php echo $ratingStar; ?>0.png" alt="<?php echo round($this->rating->rating); ?> stars out of 5" /> <span class="rating-text">(<?php echo JText::_ ( 'COM_VIRTUEMART_RATING' )?> <?php echo round($this->rating->rating,1); ?>)</span>	 
					 
				<?php } }

				// Product Price
				if ($this->show_prices) { ?>
				<div class="product-price" id="productPrice<?php echo $this->product->virtuemart_product_id ?>">
				<?php
				if ($this->product->product_unit && VmConfig::get ( 'price_show_packaging_pricelabel' )) {
					echo "<strong>" . JText::_ ( 'COM_VIRTUEMART_CART_PRICE_PER_UNIT' ) . ' (' . $this->product->product_unit . "):</strong>";
				} else {
					echo "";
				}

				if ($this->showBasePrice) {
					echo $this->currency->createPriceDiv ( 'basePrice', 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $this->product->prices );
					echo $this->currency->createPriceDiv ( 'basePriceVariant', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT', $this->product->prices );
				}

				echo $this->currency->createPriceDiv ( 'variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $this->product->prices );
				echo $this->currency->createPriceDiv ( 'basePriceWithTax', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX', $this->product->prices );
				echo $this->currency->createPriceDiv ( 'discountedPriceWithoutTax', 'COM_VIRTUEMART_PRODUCT_DISCOUNTED_PRICE', $this->product->prices );
				echo $this->currency->createPriceDiv ( 'salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $this->product->prices );
				echo $this->currency->createPriceDiv ( 'salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $this->product->prices );
				echo $this->currency->createPriceDiv ( 'priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $this->product->prices );
				echo $this->currency->createPriceDiv ( 'discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $this->product->prices );
				echo $this->currency->createPriceDiv ( 'taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $this->product->prices ); ?>
				</div>
				<?php } ?>
                 <?php if (empty($this->product->prices)) { //price is or not check-START ?>
                 <a class="modal FlexibleAskforPrice" rel="{handler: 'iframe', size: {x: 700, y: 550}}" href="<?php echo $url ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_ASKPRICE') ?></a>
                <?php } //price is or not check-END ?>
                
                <br/>
                <?php // Product Short Description
	if (!empty($this->product->product_s_desc)) { ?>
	<div class="product-short-description">
		<?php /** @todo Test if content plugins modify the product description */
		echo $this->product->product_s_desc; ?>
	</div>    
	<?php } // Product Short Description END 
	
    if (!empty($this->product->customfieldsSorted['ontop'])) {
	$this->position='ontop';
	echo $this->loadTemplate('customfields');
    } // Product Custom ontop end
    ?>
                <br/>
                
                
				<?php // Add To Cart Button
				if (!VmConfig::get('use_as_catalog', 0) and !empty($this->product->prices)) { ?>
				<div class="addtocart-area">

					<form method="post" class="product js-recalculate" action="index.php" id="form-<?php echo $this->product->virtuemart_product_id; ?>" >
					<?php // Product custom_fields
					if (!empty($this->product->customfieldsCart)) {  ?>
					<div class="product-fields" id="product-fields<?php echo $this->product->virtuemart_product_id;?>">
						<?php foreach ($this->product->customfieldsCart as $field)
						{ ?><div style="display:inline-block;" class="product-field product-field-type-<?php echo $field->field_type ?>">
							<div class="product-fields-titleDIV">
                            <span class="product-fields-title" ><?php echo  JText::_($field->custom_title) ?></span>
							<?php if ($field->custom_tip) echo JHTML::tooltip($field->custom_tip,  JText::_($field->custom_title), 'tooltip.png'); ?>
                            </div>
							<span class="product-field-display"><?php echo $field->display ?></span>

							<span class="product-field-desc"><?php echo $field->custom_field_desc ?></span>
							</div><br />
							<?php
						}
						?>
					</div>
					<?php }
					 /* Product custom Childs
					  * to display a simple link use $field->virtuemart_product_id as link to child product_id
					  * custom_value is relation value to child
					  */

					if (!empty($this->product->customsChilds)) {  ?>
						<div class="product-fields" id="product-fields<?php echo $this->product->virtuemart_product_id;?>">
							<?php foreach ($this->product->customsChilds as $field) {  ?>
								<div style="display:inline-block;" class="product-field product-field-type-<?php echo $field->field->field_type ?>">
								<span class="product-fields-title"><?php echo JText::_($field->field->custom_title) ?></span>
								<span class="product-field-desc"><?php echo JText::_($field->field->custom_value) ?></span>
								<span class="product-field-display"><?php echo $field->display ?></span>

								</div><br />
								<?php
							} ?>
						</div>
					<?php } ?>

					<div class="addtocart-bar">

						<?php $stockhandle = VmConfig::get('stockhandle','none');
						if(($stockhandle=='disableit' or $stockhandle=='disableadd') and ($this->product->product_in_stock - $this->product->product_ordered)<1) : // Display the quantity box?>
                        <?php else : ?> 
                         <label for="quantity<?php echo $this->product->virtuemart_product_id;?>" class="quantity_box"><?php echo JText::_('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label> 
						<span class="quantity-controls">
                        <input type="button" class="quantity-controls quantity-minus" />
                        </span>
                        <span class="quantity-box">
							<input type="text" class="quantity-input" name="quantity[]" value="1" />
						</span>
						<span class="quantity-controls">
							<input type="button" class="quantity-controls quantity-plus" />
						</span>
						<?php endif // Display the quantity box END ?>

						<?php // Add the button
						$button_lbl = JText::_('COM_VIRTUEMART_CART_ADD_TO');
						$button_cls = 'addtocart-button'; //$button_cls = 'addtocart_button';
						$button_name = 'addtocart'; //$button_cls = 'addtocart_button';


						// Display the add to cart button
						$stockhandle = VmConfig::get('stockhandle','none');
						if(($stockhandle=='disableit' or $stockhandle=='disableadd') and ($this->product->product_in_stock - $this->product->product_ordered)<1){
							$button_lbl = JText::_('COM_VIRTUEMART_CART_NOTIFY');
							$button_cls = 'notify-button';
							$button_name = 'notifycustomer';
						}
						vmdebug('$stockhandle '.$stockhandle.' and stock '.$this->product->product_in_stock.' ordered '.$this->product->product_ordered);
						?>
						<span class="addtocart-button">
							<?php if ($button_cls == "notify-button") { ?>
                            <span class="outofstock"><?php echo JText::_('COM_VIRTUEMART_CART_PRODUCT_OUT_OF_STOCK'); ?></span>
                            
                            <?php } else {?>
                            <input type="submit" name="<?php echo $button_name ?>"  class="<?php echo $button_cls ?>" value="<?php echo $button_lbl ?>" title="<?php echo $button_lbl ?>" />
                            
                           
                            
                            
                            <?php } ?>
						</span>
							  <?php // Stock info START
							  
					if(($stockhandle=='disableit' or $stockhandle=='disableadd') and ($this->product->product_in_stock - $this->product->product_ordered)<1){ ?>
						<?php echo  "";
						} else {?>	
						 	 
							<div class="FlexibleProductPageAvailability">
                            <div class="FlexibleInStock"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_IN_STOCK'); ?></div>
							<?php if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none') && (VmConfig::get ( 'display_stock', 1 )) ){?>
                            	<?php if ($this->product->product_in_stock <1) { ?>
                  					
                 				<?php	}else{ ?> 
				   			 
                      		 <div class="FlexibleInStockLevel"><?php echo JText::_('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP'); ?>: (<?php echo $this->product->product_in_stock; ?>)</div>
                 				<?php } }	?>
                  	<?php ?>
                            
                            </div>
                         
							<?php } // Stock info END?>
					<div class="clear"></div>
					</div>

					<?php // Display the add to cart button END ?>
					<input type="hidden" class="pname" value="<?php echo $this->product->product_name ?>" />
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="view" value="cart" />
					<noscript><input type="hidden" name="task" value="add" /></noscript>
					<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $this->product->virtuemart_product_id ?>" />
					<?php /** @todo Handle the manufacturer view */ ?>
					<input type="hidden" name="virtuemart_manufacturer_id" value="<?php echo $this->product->virtuemart_manufacturer_id ?>" />
					<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $this->product->virtuemart_category_id ?>" />
					</form>

					<div class="clear"></div>
				</div>
				<?php }  // Add To Cart Button END ?>
        
        <?php
		// Manufacturer of the Product
		if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
		    $this->loadTemplate('manufacturer');
		}
		?>

				<div class="FlexibleProductDetailShareWindow">
                	<div class="FlexibleProductDetailShareWindowLEFT"> 
                    	<div class="FlexibleProductDetailShareWindowLEFTa"> 
                    		<!-- AddThis Button BEGIN -->
							<div class="addthis_toolbox addthis_default_style ">
								<a class="addthis_button_preferred_1"></a>
                                <a class="addthis_button_preferred_2"></a>
                                <a class="addthis_button_compact"></a>
							</div>
                            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f734d3509f75765"></script>
                        </div>
                        <div class="FlexibleProductDetailShareWindowLEFTb">     
                            <iframe src="//www.facebook.com/plugins/like.php?href=<?php $uri = & JFactory::getURI();
echo $live_site= $uri->toString( array('scheme', 'host', 'port', 'path'));?>&amp;send=false&amp;layout=standard&amp;width=40&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35&amp;appId=115329165206253" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:55px; height:29px;" allowTransparency="true"></iframe>
							 
                              
							<!-- AddThis Button END --></div>
                       </div>
                    <div class="FlexibleProductDetailShareWindowRIGHT">
                   		<div class="FlexibleProductDetailShareWindowRIGHTa">
                    		<a href="<?php echo 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component'; ?>" class="modal" rel="{handler: 'iframe', size: {x: 700, y: 550}}"><img src="<?php echo $FlexibleImagePATH; ?>Flexible/ProductDetailShareeMail.png" width="18" height="17" /></a>
                        </div>
                       	<div class="FlexibleProductDetailShareWindowRIGHTb">
                        	<a href="<?php echo 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id. '&format=pdf'; ?>" class="" rel="{handler: 'iframe', size: {x: 700, y: 550}}"><img src="<?php echo $FlexibleImagePATH; ?>Flexible/ProductDetailSharePrint.png" width="23" height="18" /></a>
                        </div>
                        
                     </div>
                </div>

		 
			</div>
            <div class="clear"></div>
            <Br/><Br/>
            <div style="text-align:right; float:right;">
            <table border="0">
<tr>
	<td valign="top"><div id="askquestion"><a class="button modal" rel="{handler: 'iframe', size: {x: 700, y: 550}}" href="<?php echo $url ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a> </div></td>
 
    
  <td valign="middle"><div class="productdetail-boxes">
  <div class="productdetail-boxes-header"><?php echo JText::_('COM_VIRTUEMART_CART_SHIPPING') ?></div>
  <?php // Availability Image
				/* TO DO add width and height to the image */
				if (!empty($this->product->product_availability)) {
					$stockhandle = VmConfig::get('stockhandle','none');
					if($stockhandle=='risetime' and ($this->product->product_in_stock - $this->product->product_ordered)<1){
					?>	<div class="availability">
						 
                        <img src="<?php echo JURI::root().'templates/'.$template.'/html/com_virtuemart/assets/images/availability/'.VmConfig::get('rised_availability','7d.gif'); ?>" class="availability" alt="" />
					</div>
				<?php	} else {
					?>
					<div class="availability">
						 <?php 
						    $GifToPNG = substr($this->product->product_availability, 0, -3);
							 
						?>
                         <img src="<?php echo JURI::root().'templates/'.$template.'/html/com_virtuemart/assets/images/availability/'.$GifToPNG; ?>png" class="availability" alt="" />
					</div>
				<?php }
				}

				 ?>
  </div>
  
     </td>
</tr>
</table>
         </div>   
            
		</div> 
        
        
		<div class="clear"></div>
        
        
        
        
        
	</div>
 

<br/>
<div class="vmFlyPageBottom">
	<div class="tabsstyleDIV">
		<ul id="vmtabs" class="shadetabs">
			<li class="selected">
            	<a href="#" rel="desc"><span><?php echo JText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></span></a></li>
			<?php if ($this->showReview) {?>
            <li><a href="#" rel="reviews"><span><?php echo JText::_('COM_VIRTUEMART_REVIEWS') ?> <?php
				if($this->showRating){
				    $maxrating = VmConfig::get('vm_maximum_rating_scale',5);
					if (empty($this->rating))  { ?>
					<?php 
							 
						?>  
                    <img src="<?php echo $FlexibleImagePATH; ?>/Flexible/stars_00.png" style=" vertical-align:middle;padding-left:5px;width:90px;height:auto;" alt="<?php echo round($this->rating->rating); ?> stars out of 5" />
                    
					<?php } else {?>	 
					
                    <img src="<?php echo $FlexibleImagePATH; ?>/Flexible/stars_<?php echo $ratingStar; ?>0.png" style=" vertical-align:middle;padding-left:5px;width:90px;height:auto;"  alt="<?php echo $ratingStar; ?> stars out of 5" />	 
					 
				<?php } }?></span>
            
			
            
            </a></li>
			<?php } ?>
            
			<?php if (!empty($this->product->customfieldsRelatedProducts)) { ?>
            <li><a href="#" rel="related"><span><?php echo JText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></span></a></li>
			<?php } ?>
            
			<?php if (!empty($this->product->customfieldsRelatedCategories)) { ?>
            <li><a href="#" rel="relcategories"><span><?php echo JText::_('COM_VIRTUEMART_RELATED_CATEGORIES'); ?></span></a></li>
			<?php }?>
            
            <?php if ((VmConfig::get('showCategory',1)) and ($this->category->haschildren) ) { ?>
            <li><a href="#" rel="subcategories"><span><?php echo JText::_('COM_VIRTUEMART_SUBCATEGORIES'); ?></span></a></li>
			<?php }?>
		</ul>

	<div class="tabcontent-container">

		<div id="desc" class="tabcontent">
			<p class="vmProductDesc"><?php // Product Description
	if (!empty($this->product->product_desc)) { ?>
			<div class="product-description">
	 		<?php echo $this->product->product_desc; ?>
			</div>
	<?php } // Product Description END ?>
    		</p>
    
    <?php // Product custom_fields START
	 if (!empty($this->product->customfields)) { ?>
	<div class="product-fields" id="product-fields<?php echo $this->product->virtuemart_product_id;?>">
	<?php
	$custom_title = null ;
	foreach ($this->product->customfields as $field){
		?><div class="product-field product-field-type-<?php echo $field->field_type ?>">
		<?php if ($field->custom_title != $custom_title) { ?>
			<span class="product-fields-title" ><?php echo JText::_($field->custom_title); ?></span>
			<?php if ($field->custom_tip) echo JHTML::tooltip($field->custom_tip,  JText::_($field->custom_title), 'tooltip.png');
			} ?>
		<span class="product-field-display"><?php echo $field->display ?></span>
		<span class="product-field-desc"><?php echo jText::_($field->custom_field_desc) ?></span>
		</div>
		<?php
		$custom_title = $field->custom_title;
		} ?>
	</div>
   
	<?php
	} // Product custom_fields END
	 ?>
	<?php
	// Product Packaging
	$product_packaging = '';
	if ($this->product->packaging || $this->product->box) { ?>
	<div class="product-packaging">

		<?php
		if ($this->product->packaging) {
			$product_packaging .= JText::_('COM_VIRTUEMART_PRODUCT_PACKAGING1').$this->product->packaging;
			if ($this->product->box) $product_packaging .= '<br />';
		}
		if ($this->product->box) $product_packaging .= JText::_('COM_VIRTUEMART_PRODUCT_PACKAGING2').$this->product->box;
		echo str_replace("{unit}",$this->product->product_unit ? $this->product->product_unit : JText::_('COM_VIRTUEMART_PRODUCT_FORM_UNIT_DEFAULT'), $product_packaging); ?>
	</div>
	<?php } // Product Packaging END ?>
    <?php // Product Files START (delete // if you need this section
	// foreach ($this->product->images as $fkey => $file) {
		// Todo add downloadable files again
		// if( $file->filesize > 0.5) $filesize_display = ' ('. number_format($file->filesize, 2,',','.')." MB)";
		// else $filesize_display = ' ('. number_format($file->filesize*1024, 2,',','.')." KB)";

		/* Show pdf in a new Window, other file types will be offered as download */
		// $target = stristr($file->file_mimetype, "pdf") ? "_blank" : "_self";
		// $link = JRoute::_('index.php?view=productdetails&task=getfile&virtuemart_media_id='.$file->virtuemart_media_id.'&virtuemart_product_id='.$this->product->virtuemart_product_id);
		// echo JHTMl::_('link', $link, $file->file_title.$filesize_display, array('target' => $target));
	// }
	?>
    	
        
        	
		</div>
<?php // Customer Reviews
	if($this->showReview) { ?>
		
		<div id="reviews" class="tabcontent">
		<div class="vmReviews">         
     <?php
echo $this->loadTemplate('reviews');
?>

</div>
</div>
         
<?php } // else echo JText::_('COM_VIRTUEMART_REVIEW_LOGIN'); // Login to write a review! ?>

		<?php 
	if (!empty($this->product->customfieldsRelatedProducts)) { ?>
		<div id="related" class="tabcontent">
		
		<?php echo $this->loadTemplate('relatedproducts');	?>
		</div>
	<?php } // Product customfieldsRelatedProducts END

	if (!empty($this->product->customfieldsRelatedCategories)) { ?>
    <div id="relcategories" class="tabcontent">
	
     <table width="100%" border="0">
  <tr>
 	<?php foreach ($this->product->customfieldsRelatedCategories as $field){ ?>  <td width="33%" valign="bottom">
				<div class="FWBrowseContainerOut CategoryThumb">
                        	<div class="CategoryThumbImage" style="height:auto;">
                   <center>         
                <span class="product-field-display" style="display:block; height:190px;"><?php echo $field->display ?></span>
				<span class="product-field-desc"><?php echo jText::_($field->custom_field_desc) ?></span>
                </center>
                </div></div>
			  </td>
			<?php
		} ?>
		</tr>
</table>
 
    </div>
	<?php
	} // Product customfieldsRelatedCategories END 
	?>
     
    <?php

	// Show child categories
	if ( VmConfig::get('showCategory',1) ) { ?>
    <div id="subcategories" class="tabcontent">
	<?php	echo $this->loadTemplate('showcategory'); } ?>
   
    
    
    
    
    
	</div>
</div>
</div>
	<script type="text/javascript">

	var countries=new ddtabcontent("vmtabs")
	countries.setpersist(true)
	countries.setselectedClassTarget("link") //"link" or "linkparent"
	countries.init()

	</script>

	

</div>
	

	<?php } ?>
    
    


