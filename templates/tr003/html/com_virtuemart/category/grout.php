<style>
.browse-view h3{ 
	background: none repeat scroll 0 0 #ffffff;
    box-shadow: 0 0 1px rgba(0, 0, 0, 0.5);
    color: #000000;
    font-family: "Verdana",Arial,Helvetica,Sans-Serif;
    font-weight: 400;   
    padding: 1px;
    font-size: 16px;
    line-height: 100%;
    margin: 5px 0;	
	font-style: normal;
    font-weight: bold;
    text-decoration: none;
}
.nim-postcontent h3 { 
    background: none repeat scroll 0 0 #ffffff;
    box-shadow: 0 0 1px rgba(0, 0, 0, 0.5);
    color: #000000;
    font-family: "Verdana",Arial,Helvetica,Sans-Serif;
    font-weight: 400;   
    padding: 1px;
    font-size: 16px;
    line-height: 100%;
    margin: 5px 0;	
	font-style: normal;
    font-weight: bold;
    text-decoration: none;
}
.nim-postcontent h4 p{ 
    color: #000000;
    font-family: "Verdana",Arial,Helvetica,Sans-Serif;
    font-weight: 400;   
    padding: 1px;
    font-size: 16px;
    line-height: 100%;
    margin: 0px 0;	
	font-style: normal;
    font-weight: bold;
    text-decoration: none;
}
.category-view h3{ 
	background: none repeat scroll 0 0 #ffffff;
    box-shadow: 0 0 1px rgba(0, 0, 0, 0.5);
    color: #000000;
    font-family: "Verdana",Arial,Helvetica,Sans-Serif;
    font-weight: 400;   
    padding: 1px;
    font-size: 16px;
    line-height: 100%;
    margin: 5px 0;	
	font-style: normal;
    font-weight: bold;
    text-decoration: none;
}
 .FlexibleThumbBrowseV1ProductDetailsButton a.product-details{width:65px;}

.FWListBrowseV1 .product-fields {
    margin-left: -190px;
    margin-top: 15px;
    padding: 3px;
    position: relative;
    width:365px;
	}
   .prod_attr_img img{}
   .prod_attr_main{
    float: left;   
    width: 179px;
	margin:5px 0;
	}
	.prod_attr_radio{}
	.prod_attr_img{
    float:left;
    margin: 2px;
    width: 35px;
   
	}
	.prod_attr_text{}
 </style>
<?php
/**
*
* Show the products in a category
*
* @package	VirtueMart
* @subpackage
* @author RolandD
* @author Max Milbers
* @todo add pagination
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 5120 2011-12-18 18:29:26Z electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior.modal' );
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/

include_once dirname(__FILE__) . '/../assets/includes/flexibleMartGlobal.php';

$app		=	JFactory::getApplication('site');
$template	=	$app->getTemplate(); 
$FlexibleImagePATH = 'templates/'.$template.'/html/com_virtuemart/assets/images/';




$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";

$document = JFactory::getDocument();
$document->addScriptDeclaration($js);

 


?>

<div class="category_description">
	<?php echo $this->category->category_description ; ?>
</div>
<?php
/* Show child categories */
$title_show = 0;
if ( VmConfig::get('showCategory',1) ) {
	if ($this->category->haschildren) {
	?><h4 class="FWCategoryTitle"><?php if($this->category->category_showtitle == "")echo $this->category->category_name; else echo $this->category->category_showtitle; ?></h4><?php

		// Category and Columns Counter
		$iCol = 1;
		$title_show = 1;
		$iCategory = 1;

		// Calculating Categories Per Row
		$categories_per_row = VmConfig::get ( 'categories_per_row', 3 );
		$category_cellwidth = ' width'.floor ( 100 / $categories_per_row );

		// Separator
		$verticalseparator = " vertical-separator";
		?>

		<div class="category-view">

		<?php // Start the Output
		if(!empty($this->category->children)){
		foreach ( $this->category->children as $category ) {

			// Show the horizontal seperator
			if ($iCol == 1 && $iCategory > $categories_per_row) { ?>
			<div class="horizontal-separator"></div>
			<?php }

			// this is an indicator wether a row needs to be opened or not
			if ($iCol == 1) { ?>
			<div class="row">
			<?php }

			// Show the vertical seperator
			if ($iCategory == $categories_per_row or $iCategory % $categories_per_row == 0) {
				$show_vertical_separator = ' ';
			} else {
				$show_vertical_separator = $verticalseparator;
			}

			// Category Link
			$caturl = JRoute::_ ( 'index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id );

				// Show Category ?>
				<div class="category floatleft<?php echo $category_cellwidth . $show_vertical_separator ?>">
						<div class="FWcategorybox">
			  				<center>
                             <a id="FWcategorynameImage"  href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
                            <?php // if ($category->ids) {
								echo $category->images[0]->displayMediaThumb("",false);
							//} ?>
                            </a>
                            </center>
							 <a id="FWcategorynamelink" href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
							<?php echo $category->category_name ?>
						 
							
							</a>
						</div>
				</div>
			<?php
			$iCategory ++;

		// Do we need to close the current row now?
		if ($iCol == $categories_per_row) { ?>
		<div class="clear"></div>
		</div>
			<?php
			$iCol = 1;
		} else {
			$iCol ++;
		}
	}
	}
	// Do we need a final closing row tag?
	if ($iCol == 1) { ?>
		<div class="clear"></div>
        <?php //if($title_show != 1) { ?>
<h4 class="FWCategoryTitle"><?php if($this->category->category_showtitle == "")echo $this->category->category_name; else echo $this->category->category_showtitle; ?></h4>
	<?php  echo $this->category->category_description ; } ?>	
    
</div>
<?php }
}

// Show child categories
if (!empty($this->products)) {
	if (!empty($this->keyword)) {
		?>
		<h3><?php echo $this->keyword; ?></h3>
		<?php
	}
	?>

<?php // Category and Columns Counter
$iBrowseCol = 1;
$iBrowseProduct = 1;

// Calculating Products Per Row
$BrowseProducts_per_row = $this->perRow;
$Browsecellwidth = ' width'.floor ( 100 / $BrowseProducts_per_row );

// Separator
$verticalseparator = " vertical-separator";
?>

<div class="browse-view" style="margin-bottom:0px;">
 
	<h4 class="FWCategoryTitle"><?php if($this->category->category_showtitle == "")echo $this->category->category_name; else echo $this->category->category_showtitle; ?></h4>
		<form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&limitstart=0&virtuemart_category_id='.$this->category->virtuemart_category_id ); ?>" method="get">
		<?php if ($this->search) { ?>
		<!--BEGIN Search Box --><div class="virtuemart_search">
		<?php echo $this->searchcustom ?>
		<br />
		<?php echo $this->searchcustomvalues ?>
		<input style="height:16px;vertical-align :middle;" name="keyword" class="inputbox" type="text" size="20" value="<?php echo $this->keyword ?>" />
		<input type="submit" value="<?php echo JText::_('COM_VIRTUEMART_SEARCH') ?>" class="button" onclick="this.form.keyword.focus();"/>
		</div>
				<input type="hidden" name="search" value="true" />
				<input type="hidden" name="view" value="category" />


		<!-- End Search Box -->
		<?php } ?>
        
          <script type="text/javascript">
       jQuery(document).ready(function(){         
           			jQuery("a.switch_thumb").toggle(function(){
			  jQuery(this).removeClass("swap"); 
			  jQuery("div.FWListBrowseV1").fadeOut("fast"); 
			  jQuery("div.FWThumbBrowseV1").fadeIn("fast");
			  }, function () {
			  jQuery(this).addClass("swap");
			   jQuery("div.FWThumbBrowseV1").fadeOut("fast"); 
			  jQuery("div.FWListBrowseV1").fadeIn("fast");
			});
			        
        });
        </script>
        
			<div class="orderby-displaynumber">
				<div class="width55 floatleft">
                
					<?php echo $this->orderByList['orderby']; ?>
					<?php echo $this->orderByList['manufacturer']; ?>
                    
				</div>
                <div class="width20 floatleft"><?php echo $this->vmPagination->getResultsCounter();?><br/><?php echo $this->vmPagination->getLimitBox(); ?></div>
				<div class="width10 floatright display-number"><a class="switch_thumb swap" href="#switchview"> </a></div>
				
			<div class="clear"></div>
			</div>
            
            <div id="bottom-pagination">
					<div class="floatright"><?php echo $this->vmPagination->getPagesLinks(); ?></div>
				</div>

		 <div class="clear"></div>
</form>

<div class="FWListBrowseV1"  style="display:block;">


<?php // Start the Output
foreach ( $this->products as $product ) {
	// Show Products ?>
 
		<div class="FWBrowseListContainerOut">
	 		<div class="FlexibleListBrowseV1Picture width25 floatleft">
                <?php 
	                        $customcatimg = false;							
						 	foreach ($product->customfields as $field)
							{
							if($field->custom_title == "ProductThumbnailImage"  && $field->display != "")
								{
								$customcatimg = true;
								/*$frst_image = preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $field->display, $matches );
						 		$customcatimgsrc = $matches[ 1 ][ 0 ];*/
                                $customcatimgsrc =$field->display;
								
								}
							}
						
						 if($customcatimg){					 	 						 
						  ?>                         
                         <a href="<?php echo $product->link; ?>"><img border="0" title="<?php echo $product->product_sku; ?>" class="browseProductImage" alt="<?php echo $product->product_sku; ?>" src="<?php echo $customcatimgsrc; ?>"></a>
						 <?php }else{ echo JHTML::link($product->link, $product->images[0]->displayMediaThumb('class="browseProductImage"  border="0" title="'.$product->product_sku.'" ',false,'class="modal"'));
						 } ?>
                <div class="FlexibleThumbBrowseV1QuickBuy"> </div>	 
			</div>
            <?php
						// CHECK IF THE PRODUCT IS NEW OR OLD. IF THE PRODUCT WAS CREATED LESS THAN 1 WEEK AGO, IT WILL HAVE "NEW" BADGE.
						$createddate = strtotime(($product->created_on) . " +1 week");  // +1 week can anything you want:  +2 week, +1 month, +24 hour   so on..
						$today = strtotime(date("Y-m-d G:i:s")); //todays date
						if ($createddate > $today) {
							$NEWorOLD = "FlexibleNew";
							} else {
							$NEWorOLD = "";
							}
						if ($product->product_special == 1) {
							$SpecialOrNOT = "FlexibleSpecial";
							} else {
							$SpecialOrNOT = "";
							}
						$DiscountORnot = "";
						$DiscountTEXT = $this->currency->createPriceDiv('discountAmount',0,$product->prices);	
						if (preg_match("/none/i", $DiscountTEXT)) {
    						$DiscountORnot = "";
							} else {
   							 if (!(empty($DiscountTEXT))) {
							 $DiscountORnot = "FlexibleDiscount";
							 	}
							}
						 
						 ?> 
                <div class="FlexibleBadge"><span class="<?php echo $SpecialOrNOT; ?>"></span> <span class="<?php echo $DiscountORnot; ?>"></span> <span class="<?php echo $NEWorOLD; ?>"></span></div>
			<div class="floatleft width45">
            	<div class="FlexibleListBrowseV1ProductName">
                	<?php echo "Model: ".JHTML::link($product->link, $product->product_sku); ?> 
				</div>
				
                 
						 
                        <?php // Rating Start
                 	if($this->showRating){
					$ratingModel = VmModel::getModel('ratings');
					$showRating = $ratingModel->showRating();
					$this->assignRef('showRating', $showRating);
					$rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
					$this->assignRef('rating', $rating);	
				    $maxrating = VmConfig::get('vm_maximum_rating_scale',5);
					$rating = empty($this->rating)? JText::_('COM_VIRTUEMART_RATING').' '.JText::_('COM_VIRTUEMART_UNRATED'):'' . round($this->rating->rating, 2 ) . '';
					$ratingwidth = ( $this->rating * 100 ) / $maxrating;
					?>
                    <?php if ($rating != 0) { ?>
                    	<?php 
							if (round($rating, 1) <= 5 && round($rating, 1) > 4.5) { $ratingStar = 5;}
							if (round($rating, 1) <= 4.5 && round($rating, 1) > 4) { $ratingStar = 4.5;}
							if (round($rating, 1) <= 4 && round($rating, 1) > 3.5) { $ratingStar = 4;}
							if (round($rating, 1) <= 3.5 && round($rating, 1) > 3) { $ratingStar = 3.5;}
							if (round($rating, 1) <= 3 && round($rating, 1) > 2.5) { $ratingStar = 3;}
							if (round($rating, 1) <= 2.5 && round($rating, 1) > 2) { $ratingStar = 2.5;}
							if (round($rating, 1) <= 2 && round($rating, 1) > 1.5) { $ratingStar = 2;}
							if (round($rating, 1) <= 1.5 && round($rating, 1) > 1) { $ratingStar = 1.5;}
							if (round($rating, 1) <= 1 && round($rating, 1) > 0.5) { $ratingStar = 1;}
							if (round($rating, 1) <= 0.5 && round($rating, 1) > 0) { $ratingStar = 0.5;} 
						?>  
						 
					<img src="<?php echo $FlexibleImagePATH; ?>/Flexible/stars_<?php echo $ratingStar; ?>0.png" alt="<?php echo round($this->rating->rating); ?> stars out of 5" /> <span class="rating-text">(<?php echo $rating; ?>/5)</span>
                     		<?php } else { ?>	 
					 	<span class="rating-text"><?php echo $rating; ?></span>
                  	<?php } } //End Rating ?>

					<?php // Stock info START
						if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none') && (VmConfig::get ( 'display_stock', 1 )) ){?>
						<!--if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none')){?> -->
						<div class="paddingtop8">
               			<span class="stock-level" style="display:block; text-align:left;"><?php echo JText::_('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP') ?></span>
						<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>	
						</div>
					<?php } // Stock info END?>


						<?php // Product Short Description
						if(!empty($product->product_s_desc)) { ?>
						<p class="product_s_desc">
						<?php echo $product->product_name.' <br>'. shopFunctionsF::limitStringByWord($product->product_s_desc, 140, '...') ?>
						</p>
						<?php } ?>
					  

					<div class="FlexibleListBrowseV1ProductDetailsButton">
					<?php // Product Details Button
					echo JHTML::link($product->link, JText::_('COM_VIRTUEMART_PRODUCT_DETAILS'), array('title' => $product->product_name,'class' => 'product-details'));
					?>
				</div>

			</div>
            <div class="width25 floatright" style="text-align:right;">
                <div class="product-price marginbottom12" style="text-align:right;" id="productPrice<?php echo $product->virtuemart_product_id ?>">
					<?php
					if ($this->show_prices == '1') {
						if( $product->product_unit && VmConfig::get('vm_price_show_packaging_pricelabel')) {
							echo "<strong>". JText::_('COM_VIRTUEMART_CART_PRICE_PER_UNIT').' ('.$product->product_unit."):</strong>";
						}

						//todo add config settings
						if( $this->showBasePrice){
							echo $this->currency->createPriceDiv('basePrice','COM_VIRTUEMART_PRODUCT_BASEPRICE',$product->prices);
							echo $this->currency->createPriceDiv('basePriceVariant','COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT',$product->prices);
						}
						echo $this->currency->createPriceDiv('variantModification','COM_VIRTUEMART_PRODUCT_VARIANT_MOD',$product->prices);
						echo $this->currency->createPriceDiv('basePriceWithTax','COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX',$product->prices);
						echo $this->currency->createPriceDiv('discountedPriceWithoutTax','COM_VIRTUEMART_PRODUCT_DISCOUNTED_PRICE',$product->prices);
						echo $this->currency->createPriceDiv('salesPriceWithDiscount','COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT',$product->prices);
						echo $this->currency->createPriceDiv('salesPrice','COM_VIRTUEMART_PRODUCT_SALESPRICE',$product->prices);
						echo $this->currency->createPriceDiv('priceWithoutTax','COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX',$product->prices);
						echo $this->currency->createPriceDiv('discountAmount','COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT',$product->prices);
						echo $this->currency->createPriceDiv('taxAmount','COM_VIRTUEMART_PRODUCT_TAX_AMOUNT',$product->prices);
					} ?>
				</div>
					<?php if (empty($product->prices)) { //price is or not check-START
                    $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id.'&tmpl=component');
                     ?>
                     <a class="modal FlexibleAskforPrice" style="float:right;" rel="{handler: 'iframe', size: {x: 700, y: 550}}" href="<?php echo $url ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_ASKPRICE') ?></a>
                    <?php } //price is or not check-END ?>
                <?php // Add To Cart Button
				if (!VmConfig::get('use_as_catalog', 0) and !empty($product->prices)) {?>
				<form method="post" class="product js-recalculate" action="index.php" id="form-<?php echo $product->virtuemart_product_id; ?>" >	
                <div class="addtocart-area">
									
					<?php /* Product custom Childs
					  * to display a simple link use $field->virtuemart_product_id as link to child product_id
					  * custom_value is relation value to child
					  */
					
					if (!empty($product->customsChilds)) {  ?>
						<div class="product-fields" id="product-fields<?php echo $product->virtuemart_product_id;?>">
							<?php foreach ($product->customsChilds as $field) {  ?>
								<div style="display:inline-block;float:right; padding:3px;" class="product-field product-field-type-<?php echo $field->field->field_type ?>">
								<span class="product-fields-title" ><b><?php echo JText::_($field->field->custom_title) ?></b></span>
								<span class="product-field-desc"><?php echo JText::_($field->field->custom_value) ?></span>
								<span class="product-field-display"><?php echo $field->display ?></span>

								</div> 
								<?php
							} ?>
						</div>
					<?php } ?>
 
				<div class="addtocart-bar">

						<?php // Display the quantity box ?>
						<!-- <label for="quantity<?php echo $product->virtuemart_product_id;?>" class="quantity_box"><?php echo JText::_('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label> -->
						 
						<input type="hidden" class="quantity-input" name="quantity[]" value="1" />
						 
						 
						<?php // Display the quantity box END ?>

						<?php // Add the button
						$button_lbl = JText::_('COM_VIRTUEMART_CART_ADD_TO');
						$button_cls = 'addtocart-button'; //$button_cls = 'addtocart_button';
						$button_name = 'addtocart'; //$button_cls = 'addtocart_button';


						// Display the add to cart button
						$stockhandle = VmConfig::get('stockhandle','none');
						if(($stockhandle=='disableit' or $stockhandle=='disableadd') and ($product->product_in_stock - $product->product_ordered)<1){
							$button_lbl = JText::_('COM_VIRTUEMART_CART_NOTIFY');
							$button_cls = 'notify-button';
							$button_name = 'notifycustomer';
						}
						vmdebug('$stockhandle '.$stockhandle.' and stock '.$product->product_in_stock.' ordered '.$product->product_ordered);
						?>
					 <?php 
					       $db =& JFactory::getDBO();
						   $user =& JFactory::getUser();					
						   $query = 'select group_id from #__user_usergroup_map where user_id='.$user->id;
						   $db->setQuery($query);
						   $row = $db->loadRowList();
if($row[0][0]!=9 && $row[1][0]!=9 && $row[2][0]!=9) {
						?>
                        
						<span class="addtocart-button">
							<?php if ($button_cls == "notify-button") { ?>
                            <span class="outofstock"><?php echo JText::_('COM_VIRTUEMART_CART_PRODUCT_OUT_OF_STOCK'); ?></span>
                            
                            <?php } else {?>
                            <input type="submit" name="<?php echo $button_name ?>"  class="<?php echo $button_cls ?>" value="<?php echo $button_lbl ?>" title="<?php echo $button_lbl ?>" />
                            
                           
                            
                            
                            <?php } ?>
						</span>
                 <?php } else {?>
                 
                 <span class="addtocart-button">
							<?php if ($button_cls == "notify-button") { ?>
                            <span class="outofstock"><?php echo JText::_('COM_VIRTUEMART_CART_PRODUCT_OUT_OF_STOCK'); ?></span>
                            
                            <?php } else {?>
                            <input type="submit" name="<?php echo $button_name ?>"  class="<?php echo $button_cls ?>" value="<?php echo $button_lbl ?>" title="<?php echo $button_lbl ?>" disabled="disabled" style=" background: none repeat scroll 0 0 #CCCCCC; border: 1px solid #606060;"/>
                            
                           
                            
                            
                            <?php } ?>
						</span>
                 <?php } ?>

					<div class="clear"></div>
				</div>

					<?php // Display the add to cart button END ?>
					<input type="hidden" class="pname" value="<?php echo $product->product_name ?>" />
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="view" value="cart" />
					<noscript><input type="hidden" name="task" value="add" /></noscript>
					<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>" />
					<?php /** @todo Handle the manufacturer view */ ?>
					<input type="hidden" name="virtuemart_manufacturer_id" value="<?php echo $product->virtuemart_manufacturer_id ?>" />
					<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->virtuemart_category_id ?>" />
					
                    </div>
				<?php // Product custom_fields
						
					if (!empty($product->customfieldsCart)) {  ?>
					<div class="product-fields" id="product-fields<?php echo $product->virtuemart_product_id;?>">
						<?php foreach ($product->customfieldsCart as $field)
						{ ?>
                        
                        	<div style="text-align:left;" class="product-field product-field-type-<?php echo $field->field_type ?>">
							<span class="product-fields-title" ><b><?php echo  JText::_($field->custom_title) ?></b></span>
								<?php if ($field->custom_tip) echo JHTML::tooltip($field->custom_tip,  JText::_($field->custom_title), 'tooltip.png'); ?>
							
                            <span class="product-field-display"><?php echo $field->display ?></span>
							<div style="clear:both;"></div>	
							<span class="product-field-desc"><?php echo $field->custom_field_desc ?></span>
							</div><br /><br />
							<?php
						}
						?>
					</div>
					<?php } ?>
					
                    </form> 
			
				<?php }   // Add To Cart Button END ?>
               
		</div>
       
			<div class="clear"></div>
             <div class="image-shadow2"></div>
	</div>
		
	 
		<?php	 
}
// Do we need a final closing row tag?

 ?>

 </div> 
 

<div class="FWThumbBrowseV1" style="display:none;" >
<?php // Start the Output
foreach ( $this->products as $product ) {

	// Show the horizontal seperator
	if ($iBrowseCol == 1 && $iBrowseProduct > $BrowseProducts_per_row) { ?>
	<div class="horizontal-separator"></div>
	<?php }

	// this is an indicator wether a row needs to be opened or not
	if ($iBrowseCol == 1) { ?>
	<div class="row">
	<?php }

	// Show the vertical seperator
	if ($iBrowseProduct == $BrowseProducts_per_row or $iBrowseProduct % $BrowseProducts_per_row == 0) {
		$show_vertical_separator = ' ';
	} else {
		$show_vertical_separator = $verticalseparator;
	}

		// Show Products ?>
         
		<div class="floatleft<?php echo $Browsecellwidth . $show_vertical_separator ?>" style="position:relative;">
			<div class="FlexibleThumbBrowseV1out">
				<div class="FlexibleThumbBrowseV1Picture">
						 <?php 
	                        $customcatimg = false;							
						 	foreach ($product->customfields as $field)
							{
							if($field->custom_title == "ProductThumbnailImage"  && $field->display != "")
								{
								$customcatimg = true;
								/*$frst_image = preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $field->display, $matches );
						 		$customcatimgsrc = $matches[ 1 ][ 0 ];*/
								$customcatimgsrc =$field->display;
								
								}
							}
						
						 if($customcatimg){					 	 						 
						  ?>                         
                         <a href="<?php echo $product->link; ?>"><img border="0" title="<?php echo $product->product_name; ?>" class="browseProductImage" alt="<?php echo $product->product_name; ?>" src="<?php echo $customcatimgsrc; ?>"></a>
						 <?php }else{
						  echo JHTML::link($product->link, $product->images[0]->displayMediaThumb('class="browseProductImage"  border="0" title="'.$product->product_name.'" ',false,'class="modal"'));
						  }?>
					<div class="FlexibleThumbBrowseV1QuickBuy"> </div>	 
				</div>


						<!-- The "Average Customer Rating" Part -->
						 
                        <?php 
                 	if($this->showRating){
					$ratingModel = VmModel::getModel('ratings');
					$showRating = $ratingModel->showRating();
					$this->assignRef('showRating', $showRating);
					$rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
					$this->assignRef('rating', $rating);	
				    $maxrating = VmConfig::get('vm_maximum_rating_scale',5);
					$rating = empty($this->rating)? JText::_('COM_VIRTUEMART_RATING').' '.JText::_('COM_VIRTUEMART_UNRATED'):'' . round($this->rating->rating, 2 ) . '';
					$ratingwidth = ( $this->rating * 100 ) / $maxrating;
					?>
                    <?php if ($rating != 0) { ?>
                    	<?php 
							if (round($rating, 1) <= 5 && round($rating, 1) > 4.5) { $ratingStar = 5;}
							if (round($rating, 1) <= 4.5 && round($rating, 1) > 4) { $ratingStar = 4.5;}
							if (round($rating, 1) <= 4 && round($rating, 1) > 3.5) { $ratingStar = 4;}
							if (round($rating, 1) <= 3.5 && round($rating, 1) > 3) { $ratingStar = 3.5;}
							if (round($rating, 1) <= 3 && round($rating, 1) > 2.5) { $ratingStar = 3;}
							if (round($rating, 1) <= 2.5 && round($rating, 1) > 2) { $ratingStar = 2.5;}
							if (round($rating, 1) <= 2 && round($rating, 1) > 1.5) { $ratingStar = 2;}
							if (round($rating, 1) <= 1.5 && round($rating, 1) > 1) { $ratingStar = 1.5;}
							if (round($rating, 1) <= 1 && round($rating, 1) > 0.5) { $ratingStar = 1;}
							if (round($rating, 1) <= 0.5 && round($rating, 1) > 0) { $ratingStar = 0.5;} 
						?>  
						 
						 <img src="<?php echo $FlexibleImagePATH; ?>/Flexible/stars_<?php echo $ratingStar; ?>0.png" alt="<?php echo round($this->rating->rating); ?> stars out of 5" /> <span class="rating-text">(<?php echo $rating; ?>/5)</span>
                        <?php } else { ?>	 
					 	<span class="rating-text"><?php echo $rating; ?></span>
                     <?php } ?>
					 
						 
						 
						<?php } ?>

						<?php
						if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none') && (VmConfig::get ( 'display_stock', 1 )) ){?>
<!-- 						if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none')){?> -->
				<div class="paddingtop8">
                <span class="stock-level" style="display:block; text-align:left;"><?php echo JText::_('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP') ?></span>
							<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
							
				</div>
						<?php }?>
			

				<div class="FlexibleThumbBrowseV1ProductName">
                	 
						<?php echo 'Model: '. JHTML::link($product->link, $product->product_sku).'<br>'.$product->product_s_desc; ?> 
				</div>
						
                        <?php
						// CHECK IF THE PRODUCT IS NEW OR OLD. IF THE PRODUCT WAS CREATED LESS THAN 1 WEEK AGO, IT WILL HAVE "NEW" BADGE.
						$createddate = strtotime(($product->created_on) . " +1 week");  // +1 week can anything you want:  +2 week, +1 month, +24 hour   so on..
						$today = strtotime(date("Y-m-d G:i:s")); //todays date
						if ($createddate > $today) {
							$NEWorOLD = "FlexibleNew";
							} else {
							$NEWorOLD = "";
							}
						if ($product->product_special == 1) {
							$SpecialOrNOT = "FlexibleSpecial";
							} else {
							$SpecialOrNOT = "";
							}
						$DiscountORnot = "";	
						$DiscountTEXT = $this->currency->createPriceDiv('discountAmount',0,$product->prices);	
						if (preg_match("/none/i", $DiscountTEXT)) {
    						$DiscountORnot = "";
							} else {
   							 if (!(empty($DiscountTEXT))) {
							 $DiscountORnot = "FlexibleDiscount";
							 	}
							}
						
						 ?> 
                <div class="FlexibleBadge"><span class="<?php echo $SpecialOrNOT; ?>"></span> <span class="<?php echo $DiscountORnot; ?>"></span> <span class="<?php echo $NEWorOLD; ?>"></span> </div>
                
						 
				<div class="product-price marginbottom12" id="productPrice<?php echo $product->virtuemart_product_id ?>">
					<?php
					if ($this->show_prices == '1') {
						if( $product->product_unit && VmConfig::get('vm_price_show_packaging_pricelabel')) {
							echo "<strong>". JText::_('COM_VIRTUEMART_CART_PRICE_PER_UNIT').' ('.$product->product_unit."):</strong>";
						}
						if(empty($product->prices) and VmConfig::get('askprice',1) and empty($product->images[0]->file_is_downloadable) ){
							echo JText::_('COM_VIRTUEMART_PRODUCT_ASKPRICE');
						}
						//todo add config settings
						if( $this->showBasePrice){
							echo $this->currency->createPriceDiv('basePrice','COM_VIRTUEMART_PRODUCT_BASEPRICE',$product->prices);
							echo $this->currency->createPriceDiv('basePriceVariant','COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT',$product->prices);
						}
						echo $this->currency->createPriceDiv('variantModification','COM_VIRTUEMART_PRODUCT_VARIANT_MOD',$product->prices);
						echo $this->currency->createPriceDiv('basePriceWithTax','COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX',$product->prices);
						echo $this->currency->createPriceDiv('discountedPriceWithoutTax','COM_VIRTUEMART_PRODUCT_DISCOUNTED_PRICE',$product->prices);
						echo $this->currency->createPriceDiv('salesPriceWithDiscount','COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT',$product->prices);
						echo $this->currency->createPriceDiv('salesPrice','COM_VIRTUEMART_PRODUCT_SALESPRICE',$product->prices);
						echo $this->currency->createPriceDiv('priceWithoutTax','COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX',$product->prices);
						echo $this->currency->createPriceDiv('discountAmount','COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT',$product->prices);
						echo $this->currency->createPriceDiv('taxAmount','COM_VIRTUEMART_PRODUCT_TAX_AMOUNT',$product->prices);
					} ?>
				</div>

				<div class="FlexibleThumbBrowseV1ProductDetailsButton">
					<!-- add to card button code start-->                	
                <form method="post" class="product js-recalculate" action="index.php" id="form-<?php echo $product->virtuemart_product_id; ?>">
				 <div style="width:65px;float:left;margin-left:5px;">
                    <?php // Product Details Button
					echo JHTML::link($product->link, JText::_('Details'), array('title' => $product->product_name,'class' => 'product-details'));
					?>
                    </div>	 
				<div class="addtocart-bar" style="width: 130px;float:left;margin-left:15px;">

						<?php // Display the quantity box ?>
						<!-- <label for="quantity<?php echo $product->virtuemart_product_id;?>" class="quantity_box"><?php echo JText::_('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label> -->
						 
						<input type="hidden" class="quantity-input" name="quantity[]" value="1" />
						 
						 
						<?php // Display the quantity box END ?>

						<?php // Add the button
						$button_lbl = JText::_('COM_VIRTUEMART_CART_ADD_TO');
						$button_cls = 'addtocart-button'; //$button_cls = 'addtocart_button';
						$button_name = 'addtocart'; //$button_cls = 'addtocart_button';


						// Display the add to cart button
						$stockhandle = VmConfig::get('stockhandle','none');
						if(($stockhandle=='disableit' or $stockhandle=='disableadd') and ($product->product_in_stock - $product->product_ordered)<1){
							$button_lbl = JText::_('COM_VIRTUEMART_CART_NOTIFY');
							$button_cls = 'notify-button';
							$button_name = 'notifycustomer';
						}
						vmdebug('$stockhandle '.$stockhandle.' and stock '.$product->product_in_stock.' ordered '.$product->product_ordered);
						?>
                      <!-- add condition for show add to cart butto-->
                      <?php $show_button_addtocart = true;
					  		$attr_msg = "";
							foreach ($product->customfields as $productcustom)
							{
							if($productcustom->custom_title == 'Category Thumb Message')
							$attr_msg = $productcustom->custom_value;
							}
							
					  		if (!empty($product->customfieldsCart)) {  ?> 
                      	<?php foreach ($product->customfieldsCart as $field)
								{ 
								 if($field->virtuemart_custom_id == 47) 
								 $show_button_addtocart = false;
								} ?>
					 <?php } ?>
                     <!-- end code for add condition for show add to cart butto-->
                     <?php if ($show_button_addtocart) {  ?>
						<span class="addtocart-button" style="margin: 0px;">
							<?php if ($button_cls == "notify-button") { ?>
                            <span class="outofstock"><?php echo JText::_('COM_VIRTUEMART_CART_PRODUCT_OUT_OF_STOCK'); ?></span>
                            
                            <?php } else {?>
                            <input type="submit" name="<?php echo $button_name ?>"  class="<?php echo $button_cls ?>" value="<?php echo $button_lbl ?>" title="<?php echo $button_lbl ?>" style="border:none;border:2px solid #000000;height:31px;text-decoration:underline;font-family:Verdana,Geneva,Arial,Helvetica,Sans-Serif;font-size:14px;width:130px;padding:0;padding-bottom:4px;"  />
                            
                            <?php } ?>
						</span>
 						<?php } else { echo '<span class="addtocart-button" style="margin: 0px;">'.$attr_msg.'</span>'; } ?>
					<div class="clear"></div>
				</div>

					<?php // Display the add to cart button END ?>
					<input type="hidden" class="pname" value="<?php echo $product->product_name ?>" />
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="view" value="cart" />
					<noscript><input type="hidden" name="task" value="add" /></noscript>
					<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>" />
					<?php /** @todo Handle the manufacturer view */ ?>
					<input type="hidden" name="virtuemart_manufacturer_id" value="<?php echo $product->virtuemart_manufacturer_id ?>" />
					<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->virtuemart_category_id ?>" />
                   
					</form>
                <!--add to card button code end-->
				</div>
                <div style="clear:both" />

				</div>
			<div class="clear"></div>
            
			</div><!-- end of flexibleWebOut -->
            <div class="image-shadow"></div>
		</div> <!-- end of product -->
	<?php

   // Do we need to close the current row now?
   if ($iBrowseCol == $BrowseProducts_per_row) {?>
   <div class="clear"></div>
   </div> <!-- end of row -->
      <?php
      $iBrowseCol = 1;
   } else {
      $iBrowseCol ++;
   }

   $iBrowseProduct ++;
} // end of foreach ( $this->products as $product )
// Do we need a final closing row tag?
if ($iBrowseCol != 1) { ?>
	<div class="clear"></div>

<?php
}
?>
	</div> 
 
  <div class="clear"></div> 
	<div id="bottom-pagination"><?php echo $this->vmPagination->getPagesLinks(); ?> <span><?php echo $this->vmPagination->getPagesCounter(); ?></span></div>
</div>

<?php }?>
<div style="clear:both;"></div>
<?php // if ($iCol != 1) echo $this->category->category_description ; ?>
