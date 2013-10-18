<?php 
include "utils.php";

$bases = getBases();

$count = count($bases);

for ($i = 0; $i < $count; $i++) {
	
		$idb = $bases[$i]['id'];
		$roomNumber = $bases[$i]['komn'];
		$maxIndex = 0;
		
		$r = mysql_query("SELECT * FROM {$idb}_schedule");
		while ($row = mysql_fetch_array($r)) {
			if ($row['rooms'] > $maxIndex) {
				$maxIndex = $row['rooms'];
			}
		}

		if ($maxIndex < $roomNumber) {
			if ($maxIndex == 0) {
				$maxIndex = 1;
			}

			for ( $j = $maxIndex; $j <= $roomNumber; $j++ ) {
				$sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_schedule` (`rooms`) VALUES ('$j')");
			}
		}
		
	
}
	/*
	}*/

	/*$maxIndex = 0;
	while ($row = mysql_fetch_array($r)) {
		if ($row['id'] > $maxIndex) {
			$maxIndex = $row['id'];
		}
	}*/

	/*if ($maxIndex < $roomNumber) {
		print($idb." name = ".$bases[$i]['name']." rooms = ".$roomNumber." actual =".$maxIndex." <br>\n\n");
		for ($j = 1;$j <=$roomNumber; $j++) {
			$sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_schedule` (`rooms`) VALUES ('$j')");
        	$sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_equip` (`id`) VALUES ('$j')");
		}
	}

	if ($roomNumber == 0) {
		$komn = 1;
		$query = "UPDATE bases SET `komn`='".$komn."' WHERE id='".$idb."'";
    	$r = mysql_query($query);
    	$sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_schedule` (`rooms`) VALUES ('1')");
        $sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$idb}_equip` (`id`) VALUES ('1')");
	}*/


?>