<?php 

function binarySearch($array, $key, $low, $high) 
{

	if ($low > $high) {
		return -1;
	}

	$middle = (int)(($high + $low) / 2);

	if ($array[$middle] == $key) {
		return $middle;
	} elseif ($array[$middle] > $key) {
		return binarySearch($array, $key, $low, $middle - 1);
	} elseif ($array[$middle] < $key) {
		return binarySearch($array, $key, $middle + 1, $high);
	}
}