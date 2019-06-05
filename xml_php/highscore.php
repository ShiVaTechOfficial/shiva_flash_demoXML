<?php

	//globals -----------------------------------------

	$filename = "./highscore.xml";
	$linenum = 0;
	$newxmlarray=array();

	//read POST -----------------------------------------

if ( isset($_POST['STContent'] ) ) {

	//new XML content sent from SHiVa
	$xml_add = stripslashes($_POST['STContent']);

	//get the score for sorting later on
	$grep = strpos( $xml_add, "score=\"" );
	$newscore = substr( $xml_add, $grep+7, 4 );

	//read xml to array -----------------------------------------

	$lines = file($filename);
	$countlines = count($lines);

	foreach($lines as $line) {

		//search for score, match only 4-figure numbers
		$moregrep = preg_match ( '/"[0-9]{4}"/', $line ) ;

		if ($moregrep > 0) {
			$grep = strpos( $line, "score=\"" );
			$oldscore = substr ( $line, $grep+7, 4 );

			if ( intval($oldscore) <= intval($newscore) ) {
				//found lesser score
				break;
			}
		}
	$linenum += 1;
	}

	//compose new XML order-----------------------------------


	for ( $k=0; $k<$linenum; $k++ ) { $newxmlarray[] = $lines[$k]; }

	$newxmlarray[] = $xml_add."
"; //add our new highscore, including a line break - important!

	for ( $j=$linenum; $j<=$countlines-1; $j++ ) { $newxmlarray[] = $lines[$j]; }

	//write xml to disk-----------------------------------------


	if (is_writable($filename)) {
		$handle = fopen($filename, 'w');

		for ($j=0; $j<count($newxmlarray); $j++) {
		    fwrite( $handle, $newxmlarray[$j] ) ;
		}

	    fclose($handle);

	}

	//-----------------------------------------

} else { echo "Error."; }

?>