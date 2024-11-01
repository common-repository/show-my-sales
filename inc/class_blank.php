<?php
class msms_wpec {
	
	function get_summary_between_dates( $start_date, $end_date ) {
		$msms_purchases = new stdClass;
		$msms_purchases->start = $start_date;
		$msms_purchases->end = $end_date;
		$msms_purchases->total = 0;
		$msms_purchases->count = 0;	
		return $msms_purchases;		
	}		
	
	function get_sales_between_dates( $start_date, $end_date, $name = false ) {
		$msms_purchases = array();
		return $msms_purchases;		
	}	

	function get_product_summary_between_dates( $start_date, $end_date, $name = false ) {
		$purchases = array();
		$msms_purchases = new stdClass;
		return $purchases;
	}
	
}
?>