<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>
<h1><?php echo "Pending List Orders"; ?></h1>
<?php
//if (count($this->orderlist) == 0) {
	//echo JText::_('COM_VIRTUEMART_ACC_NO_ORDER');
//	 echo shopFunctionsF::getLoginForm(false,true);
//} else
 {
 ?>
 <script>
function pendingorder(id){
//alert("-->"+id);
window.opener.document.getElementById('select_order_id').value=id;
window.close();
window.opener.pendingorder1();
}
</script>
 
<div id="editcell">
<?php
$db = JFactory::getDBO();
	 $query0 = "SELECT  *  FROM #__virtuemart_orders WHERE order_status='P' AND virtuemart_user_id='".$_GET['user_id']."'";
		$db->setQuery($query0);
		$orderList123 = $db->loadObjectList();
		if(count($orderList123) == 0)
		{
		
		echo "<span style='color:red'>No order in pending....</span>";
		}
		else
		{
		?>

	<table class="adminlist" width="100%">
	<thead>
	<tr>
		<th align="center">
			<?php echo "Order Number"; ?>
		</th>
		<th align="left">
			<?php echo JText::_('COM_VIRTUEMART_ORDER_LIST_CDATE'); ?>
		</th>
		<th align="left">
			<?php echo JText::_('COM_VIRTUEMART_ORDER_LIST_MDATE'); ?>
		</th>
		<th align="left">
			<?php echo "Status"; ?>
		</th>
		<th align="left">
			<?php echo JText::_('COM_VIRTUEMART_ORDER_LIST_TOTAL'); ?>
		</th>
        <th align="left">
			<?php echo "Edit"; ?>
		</th>
	</thead>
	<?php	
		$k = 0;
		foreach ($orderList123 as $row) {
			$editlink = JURI::root().'index.php/sales-orders/?orderid='.$row->virtuemart_order_id;
			if(ShopFunctions::getOrderStatusName($row->order_status)=="Pending"){
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td  align="center">
					<?php echo $row->order_number; ?>
				</td>
				<td align="left">
					<?php echo vmJsApi::date($row->created_on,'LC4',true); ?>
				</td>
				<td align="left">
					<?php echo vmJsApi::date($row->modified_on,'LC3',true); ?>
				</td>
				<td align="left">
					<?php echo ShopFunctions::getOrderStatusName($row->order_status); ?>
				</td>
				<td align="left">
					<?php echo $this->currency->priceDisplay($row->order_total); ?>
				</td>
                <td align="left">
					<a style="cursor:pointer;color:#0000FF;" onclick="pendingorder('<?php echo $row->virtuemart_order_id; ?>')">Edit</a>
                    <!--<a href="<?php echo $editlink; ?>">Edit</a>-->
				</td>
			</tr>
	<?php
			$k = 1 - $k;
			}
		}
	?>
	</table>
    <?php } ?>
</div>
<?php } die; ?>
