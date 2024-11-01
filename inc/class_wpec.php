<?php
class msms_wpec {
	
	function get_summary_between_dates( $start_date, $end_date ) {
		global $wpdb;

		$msms_purchases = new stdClass;
		$msms_purchases->start = $start_date;
		$msms_purchases->end = $end_date;
		
		$sales = $wpdb->get_row( $wpdb->prepare("SELECT count(*) AS count,SUM(totalprice) AS total FROM " . $wpdb->prefix . "wpsc_purchase_logs WHERE processed IN (2,3,4) AND date >= %d AND date<= %d",$start_date,$end_date) );
		
		if ( $sales ) {
			$msms_purchases->total = $sales->total;
			$msms_purchases->count = $sales->count;
			
			if ( $msms_purchases->count ) {
				$total_days = ( $end_date - $start_date ) / 86400;	
				if ( $total_days ) {
					$msms_purchases->amount_per_day = $msms_purchases->total / $total_days;
				}
			}
		} else {
			$msms_purchases->total = 0;
			$msms_purchases->count = 0;	
		}
		
		return $msms_purchases;		
	}		
	
	function get_sales_between_dates( $start_date, $end_date, $name = false ) {
		global $wpdb;
		$msms_purchases = array();
	
		
		$sql = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "wpsc_purchase_logs WHERE processed IN (2,3,4) AND date >= %d  AND date <= %d  ORDER BY date DESC",$start_date , $end_date );
		
		$sales = $wpdb->get_results( $sql );

		if ( $sales ) {
			foreach( $sales as $sale ) {			
				$info = new stdClass;
			
				$info->date = $sale->date;
				$info->total_price = 0;				
				$info->id = $sale->id;
				$info->sales = array();
	
				$sql = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "wpsc_cart_contents WHERE purchaseid = %d",$sale->id);
				$these_sales = $wpdb->get_results( $sql );
				
				if ( $these_sales ) {
					foreach( $these_sales as $this_sale ) {
						$one_sale = new stdClass;
						$one_sale->product = $this_sale->name;
						$one_sale->value = $this_sale->price;
						$one_sale->quantity = $this_sale->quantity;
						$info->total_price = $info->total_price + $one_sale->value;
						
						$info->sales[] = $one_sale;
					}
				}
				
				$msms_purchases[] = $info;
			}
		} 

		return $msms_purchases;		
	}	

	function get_product_summary_between_dates( $start_date, $end_date ) {
		global $wpdb;
		$purchases = array();
		$msms_purchases = new stdClass;
		
		global $msms;
		$sales = $wpdb->get_results( $wpdb->prepare("SELECT SUM(price) AS p,count(*) AS c,name FROM " . $wpdb->prefix . "wpsc_purchase_logs AS a INNER JOIN " . $wpdb->prefix . "wpsc_cart_contents AS b ON a.id = b.purchaseid WHERE processed IN (2,3,4) AND date >= %d AND date <= %d  GROUP BY name ORDER BY p DESC LIMIT %d",$start_date , $end_date ,intval($msms->get_option('bestseller')) )); 
		
		if ( $sales ) {
			foreach( $sales as $sale ) {
				$msms_purchases = new stdClass;
		
				$msms_purchases->total = $sale->p;
				$msms_purchases->count = $sale->c;
		
				$purchases[ $sale->name ] = $msms_purchases;
			}	
		}
		
		return $purchases;
	}
}
?>