<?php
/** 
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved. 
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com 
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.controller' );
 
class product_priceController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}	
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	function display() {
		
		parent::display();
			
	}
	function listing(){
	
		  
		 JRequest::setVar ( 'layout', 'listing' );
		 JRequest::setVar ( 'hidemainmenu', 1 );
			 parent::display ();
	}
 
	function saveprice(){
	
	 $db = JFactory::getDBO();
	 $product_id = JRequest::getVar('pid');
	 $shopper_group_id = JRequest::getVar ( 'shopper_group_id', array (), 'post', 'array' );
	 $price = JRequest::getVar ( 'price', array (), 'post', 'array' );
	 $price_quantity_start = JRequest::getVar ( 'price_quantity_start', array (), 'post', 'array' );
	 $price_quantity_end = JRequest::getVar ( 'price_quantity_end', array (), 'post', 'array' );	 
	 $price_id = JRequest::getVar ( 'price_id', array (), 'post', 'array' ); 
	  
	 for($i=0;$i<count($price);$i++){
	 
	  $sql = "SELECT count(*) FROM  #__redshop_product_price  WHERE product_id='".$product_id."' AND price_id = '".$price_id[$i]."' AND shopper_group_id = '".$shopper_group_id[$i]."' ";
	  $db->setQuery($sql);
	   if($db->loadResult()){
	   		
	   		$query = 'SELECT price_id FROM #__redshop_product_price WHERE shopper_group_id = "'.$shopper_group_id[$i].'" AND product_id = ' .$product_id.' AND price_quantity_end >= '.$price_quantity_start[$i].' AND price_quantity_start <='.$price_quantity_start[$i];
	   		$db->setQuery($query);
			$xid = intval($db->loadResult());
			if ($xid && $xid != intval($price_id[$i])) {
				echo $xid;
					
				$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS'));
				//return false;
			}
		   		if( $price[$i] !='') {
		   			
			   		$sql = "UPDATE #__redshop_product_price  SET product_price='".$price[$i]."' ,"
			   				." price_quantity_start = '".$price_quantity_start[$i]."', price_quantity_end = '".$price_quantity_end[$i]."' "
	  						." WHERE product_id='".$product_id."' AND price_id = '".$price_id[$i]."' AND shopper_group_id = '".$shopper_group_id[$i]."' ";
			   		
			   		} else {
			   		 
					$sql = "DELETE FROM  #__redshop_product_price   WHERE product_id='".$product_id."' AND price_id = '".$price_id[$i]."' AND shopper_group_id = '".$shopper_group_id[$i]."' ";
			   			   
			   		}
	  
	   		
	   } elseif($price[$i] !='') {  
	   	
	  	 	$sql = "INSERT INTO  #__redshop_product_price  SET product_price='".$price[$i]."', price_quantity_start = '".$price_quantity_start[$i]."' , price_quantity_end = '".$price_quantity_end[$i]."' , product_id='".$product_id."' , shopper_group_id = '".$shopper_group_id[$i]."' ";
	   }
	 		
			
	   		$db->setQuery($sql);
	 		$db->Query();
	 }    

	 $this->setRedirect( 'index.php?tmpl=component&option=com_redshop&view=product_price&pid='.$product_id );
	 
	}
	function template(){
	
	    $template_id = JRequest::getVar( 'template_id', '');
	    $product_id  = JRequest::getVar( 'product_id', '');
	    $section  = JRequest::getVar( 'section', ''); 
		$model = $this->getModel ( 'product' );
			
		$data_product = $model->product_template($template_id,$product_id,$section);
		echo $data_product;
		exit;
	}

}