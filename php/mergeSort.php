<?php 

function mergeSort($array)
{
	if (sizeof($array) < = 1) {
		return $array;
	}

	$middle = (int)(count($array) / 2);

	$leftFrag = array_slice($array, 0, $middle);
	$rightFrag = array_slice($array, $middle);

	$leftFrag = mergeSort($leftFrag);
	$rightFrag = mergeSort($rightFrag);

	return merge($leftFrag, $rightFrag);
}


function merge($leftFrag, $rightFrag)
{
	$return = array();
	while (count($leftFrag) > 0 && count($rightFrag) > 0) {
		if ($leftFrag[0] <= $rightFrag[0]) {
			array_push($return, array_shift($leftFrag));
		} else {
			array_push($return, array_shift($rightFrag));
		}

		array_splice($return, count($return), 0, $leftFrag);
		array_splice($return, count($return), 0, $rightFrag);

		return $return;
	}
}