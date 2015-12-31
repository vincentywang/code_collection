<?php 

/**
 * Function to use two step to built the cvs file, one row for the title
 * othter rows for the data. 
 */
function xmlTocsv($xml_path) 
{
	$xml = simplexml_load_file($xml_path);
	$title_list = array();
	foreach ($xml as $key => $product) {
		foreach ($product as $name => $value) {
			array_push($product_property_list, $name);
		}
		break; // only need one loop to get the all titles
	}
	$process_array = array();
	foreach ($xml as $key => $product) {
		$temp_array = array();
		foreach ($product as $name) {
			array_push($temp_array, "$name");
		}
		array_push($process_array, $temp_array);
	}

	header('Content-Type: application/excel');
	header('Content-Disposition: attachment; filename="sample.csv"');

	$fp = fopen('php://output', 'w');
	// insert the title of csv each column 
	fputcsv($fp, $title_list);
	foreach ( $process_array as $product ) {
		// insert the data of the csv 
	    fputcsv($fp, $product);
	}
	fclose($fp);
}


?>
