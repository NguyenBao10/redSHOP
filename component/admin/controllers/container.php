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
// prakash
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.controller' );

class containerController extends JController
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
	function export_data()
	{
		$model = $this->getModel('container');
				
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
       	header("Content-type: text/x-csv");
    	header("Content-type: text/csv");
    	header("Content-type: application/csv");
    	header('Content-Disposition: attachment; filename=StockroomProduct.csv');
    	
    	echo "Container,Container Name,Container Desc,Creation Date\n\n";
    	
    	$data = $model->getData();
    	
    	for($i=0;$i<count($data);$i++)
		{		
			echo $data[$i]->container_id.",";
			echo $data[$i]->container_name.",";
			echo $data[$i]->container_desc.",";
			echo $data[$i]->creation_date.",";
			//echo $data[$i]->quantity * $data[$i]->product_volume;
			echo "\n";
		} 
    	exit;
	}
	function print_data()
	{
		echo '<script type="text/javascript" language="javascript">	window.print(); </script>';		
	}
}	

