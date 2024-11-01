<?php
// main class file for sms plugin
define( 'MSMS_SECONDS_PER_DAY', 3600*24 );
define( 'MSMS_SECONDS_IN_ONE_WEEK', 60*60*24*7 );

function msms_prepare_all_year_sales($start,$end) {
	
	$results = array();
	$msms_time = new msms_time;
	$msms_time->setup_date_time();
	
	if ($end>msms_mktime())
		$end = msms_mktime();
	//$wday = date( 'N', $end )-1;
	
	
	$thisyear = msms_mktime( 0, 0, 0, date( 'n',$end ), 1 );
	//$month = date( 'n', $thismonth );
	$year = date( 'Y', $thisyear );
	
	$obj = new msms_wpec;
	while ( true ) {
		
		$start_date = mktime( 0, 0, 0, 1, 1, $year );
		$end_date = mktime( 23, 59, 59, 12, 31, $year );		
		$name = date( 'Y', $start_date );
		$temp = $obj->get_summary_between_dates( $start_date, $end_date ); 
		if ( !$temp || $temp->count == 0 ) {
			break;
		}
		$results[$name] = $temp;
		$year--;
		
		
	}
	return $results;
}

function msms_prepare_year_sales($start,$end) {
	
	$results = array();
	$msms_time = new msms_time;
	$msms_time->setup_date_time();
	
	if ($end>msms_mktime())
		$end = msms_mktime();
	//$wday = date( 'N', $end )-1;
	
	$thismonth = msms_mktime( 0, 0, 0, date( 'n',$end ), 1 );
	$month = date( 'n', $end );
	$year = date( 'Y', $end );
	
	$obj = new msms_wpec;
	while ( $month > 0 ) {
		$start_date = mktime( 0, 0, 0, $month, 1, $year );
		$end_date = mktime( 23, 59, 59, $month, $msms_time->days_in_each_month( $month, $year ), $year );		
		$name = date( 'M Y', $start_date );
		$temp = $obj->get_summary_between_dates( $start_date, $end_date ); 
		if ( !$temp || $temp->count == 0 ) {
			//break;
		}
		$results[$name] = $temp;
		$month--;	
		
	}
	return $results;
}

function msms_prepare_month_sales($start,$end) {
	
	$results = array();
	$msms_time = new msms_time;
	$msms_time->setup_date_time();
	//$results = $wpec->get_summary_between_dates( $start, $end );
	//print_r($results);die();
	if ($end>msms_mktime())
		$end = msms_mktime();
	
	$month = date( 'n', $end );
	$day = date( 'j', $end );
	$year = date( 'Y', $end );
	
	$closest_seven = floor( ( $day - 1 ) / 7 );
	
	//$day_of_week = date( 'N', $end );
	//$count = 1;	
	$obj = new msms_wpec;
	while( $closest_seven >= 0 ) {
		$start_day = 1 + $closest_seven * 7;
		$end_day = $closest_seven * 7 + 7;
		
		if ( $end_day > $day ) {
			$end_day = $day;	
		}
		
		if ( $end_day > $msms_time->days_in_each_month( $month ) ) {
			$end_day = $msms_time->days_in_each_month( $month );	
		}
		
		$start_date = mktime( 0, 0, 0, $month, $start_day, $year );
		$end_date = mktime( 23, 59, 59, $month, $end_day, $year );
		
		$name = date( 'jS', $start_date ).' - '.date( 'jS \of M Y', $end_date );
		//echo $closest_seven;
		//echo date('l jS \of F Y h:i:s A',$start_date)."<br>".date('l jS \of F Y h:i:s A',$end_date)."<br><br>";
		$temp = $obj->get_summary_between_dates( $start_date, $end_date );
		$results[$name] = $temp;
		$closest_seven--;
	}
	//echo date('l jS \of F Y h:i:s A',$tstart)."<br>".date('l jS \of F Y h:i:s A',$start)."<br><br>";
	//print_r($results);die();
	return $results;
}

function msms_prepare_week_sales($start,$end) {
	
	$results = array();
	
	//$results = $wpec->get_summary_between_dates( $start, $end );
	if ($end>msms_mktime())
		$end = msms_mktime();
	
	$tstart = msms_mktime( 0, 0, 0,date("n",$end),date("j",$end),date("Y",$end) );
	//$day_of_week = date( 'N', $end );
	//$count = 1;	
	
	$obj = new msms_wpec;
	while ( $start <= $tstart ) {
		if($tstart!=$end)
		{	
		//$start_date = $start - $count*MSMS_SECONDS_PER_DAY;
		//$end_date = $end - $count*MSMS_SECONDS_PER_DAY;
		$name = date( 'jS \of M Y (l)', $tstart );
		//echo date('l jS \of F Y h:i:s A',$tstart)."<br>".date('l jS \of F Y h:i:s A',$end)."<br><br>";
		$temp = $obj->get_summary_between_dates( $tstart, $end );
		if ( !$temp || $temp->count == 0 ) {
			//break;
		}
		$results[$name] = $temp;
		}
		$end = $tstart;
		$tstart = $tstart - MSMS_SECONDS_PER_DAY;
	}
	
	return $results;
} 

function msms_timediff($start,$end)
{

	if ($end>msms_mktime())
		$end = msms_mktime();
	$diff = $end-$start;
	
	if ((date('Y',$end) - date('Y',$start))>0 )
		return 'All';
	elseif((date('n',$end) - date('n',$start))>0 )
		return 'Year';
	elseif((date('j',$end) - date('j',$start))>7 )
		return 'Month';
	elseif( (date('j',$end) - date('j',$start)) <= 7  AND $diff>MSMS_SECONDS_PER_DAY)
		return 'Week';
	else
		return 'Day';
}



function msms_time() {
	return time();	
}

function msms_mktime() {
	$args = array();
	for ( $i = 0; $i < func_num_args(); $i++ ) {
		$args[ $i ] = func_get_arg( $i );	
	} 
	
	
		switch( func_num_args() ) {
			case 6:
				return mktime( 
					$args[0],
					$args[1],
					$args[2],
					$args[3],
					$args[4],
					$args[5]
				);
			case 5:
				return mktime( 
					$args[0],
					$args[1],
					$args[2],
					$args[3],
					$args[4]
				);
			case 4:
				return mktime( 
					$args[0],
					$args[1],
					$args[2],
					$args[3]
				);
			case 3:
				return mktime( 
					$args[0],
					$args[1],
					$args[2]
				);
			case 2:
				return mktime( 
					$args[0],
					$args[1]
				);
			case 1:
				return mktime( 
					$args[0]
				);
			default:
				return mktime();	
		}
	
}

class msms_time {
	function msms_time()
	{
		$this->date_time_helper = array();
	}
	
	
	function setup_date_time() {	
		
		// rescales everything so Monday is 0
		$day_of_week = ( ( date( 'w' ) + 6 ) % 7 );
				
		$this->date_time_helper['today'] = array( msms_mktime( 0, 0, 0 ), msms_mktime( 23, 59, 59 ) );
		$this->date_time_helper['yesterday'] = array( $this->date_time_helper['today'][0] - MSMS_SECONDS_PER_DAY, $this->date_time_helper['today'][1] - MSMS_SECONDS_PER_DAY );
		
		$this->date_time_helper['this-month'] = array( msms_mktime( 0, 0, 0, date( 'n' ), 1 ), msms_mktime(23, 59, 59, date( 'n' ), $this->days_in_each_month( date( 'n' ) ) ) );
		
		$this->date_time_helper['this-year'] = array( msms_mktime(0, 0, 0, 1, 1 ), msms_mktime(23, 59, 59, 12, 31 ) );
		
		$month = date( 'n' );
		$year = date( 'Y' );
		
		$this->date_time_helper['last-year'] = array( msms_mktime(0, 0, 0, 1, 1, $year - 1 ), msms_mktime(23, 59, 59, 12, 31, $year - 1 ) );
		
		$month = $month - 1;
		if ( $month == 0 ) {
			$month = 12;
			$year = $year - 1;	
		}

		$this->date_time_helper['last-month'] = array( msms_mktime(0, 0, 0, $month, 1, $year ), msms_mktime(23, 59, 59, $month, $this->days_in_each_month( $month, $year ), $year ) );
		
		$this->date_time_helper['this-week'] = array( $this->date_time_helper['today'][0] - $day_of_week*MSMS_SECONDS_PER_DAY, $this->date_time_helper['today'][0] + ( 7 - $day_of_week )*MSMS_SECONDS_PER_DAY - 1 ); 
		$this->date_time_helper['last-week'] = array( $this->date_time_helper['this-week'][0] - MSMS_SECONDS_PER_DAY*7, $this->date_time_helper['this-week'][1] - MSMS_SECONDS_PER_DAY*7 );
		$this->date_time_helper['two-weeks-ago'] = array( $this->date_time_helper['this-week'][0] - MSMS_SECONDS_PER_DAY*14, $this->date_time_helper['this-week'][1] - MSMS_SECONDS_PER_DAY*14 );
		
		// Normalize Datas and Times
		foreach( $this->date_time_helper as $key => $value ) {
			$this->date_time_helper[ $key ] = array( ( $value[0] ), ( $value[1] ) );	
		}
	}
	
	// month is 1 based
	function days_in_each_month( $month, $year = false ) {
		if ( !$year ) {
			$year = date( 'Y' );
		}
		
		$is_leap_year = ( $year % 4 == 0 ) && ( $year % 100 == 0 ) && ( $year % 400 == 0 );
		
		if(in_array($month,array(4,6,9,11)))
			return 30;
		elseif ($month==2 AND $is_leap_year)
			return 29;
		elseif ($month==2 AND !$is_leap_year)
			return 28;
		else
			return 31;
	}
	
}

?>