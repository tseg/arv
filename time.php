<?php

    $input = "20/10/2014 05:39 AM";  //20/10/2014 05:39 PM

    list($day, $month, $year, $hour, $minute, $dayType) = preg_split('/[\/\s:]+/', $input); 
    $d1me = $year . '-' . $month. '-' .  $day . ' ' . ($dayType == "PM"?$hour+12: $hour) . ":" . $minute . ":00";
	echo $d1me;
	$date2 = new DateTime($d1me);
	
	echo '<br>'.date('Y-m-d H:i:s', strtotime($input));
	
	echo '<br>'.date( 'Y-m-d H:i:s', time());
	
	$date = '25/05/2010 05:39 AM';
	$date = str_replace('/', '-', $date);
	echo '<br>'.date('Y-m-d H:i:s', strtotime($date));
	
	
	$date1 = new DateTime(date('Y-m-d H:i:s', strtotime($date)));
	$date2 = new DateTime($d1me);
	var_dump($date2);
	var_dump($date1 == $date2);
	var_dump($date1 < $date2);
	var_dump($date1 > $date2);
	
	$current = date('Y-m-d H:i:s');
	$date3 = new DateTime($current);
	echo '<br>'.$current;
	
	var_dump($date1 == $date3);
	var_dump($date1 < $date3);
	var_dump($date1 > $date3);
	
	
	$ds = "05/25/2010 05:39 AM";
	$dr = str_replace('/', '-', $ds);
	$dt = strtotime($dr);
	$d = date('d/m/Y',$dt);
	$t = date('h:i A',$dt);
	echo $d.'<br/>'.$t;
	
	
	
?>