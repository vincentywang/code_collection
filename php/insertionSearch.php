<?php 

function insertionSort($insertBase)
{
	for ($i=1; $i < sizeof($insertBase); $i++) { 
		$right = $insertBase[$i];
		$j = $i - 1;
		if ($j >= 0 && $right < $insertBase[$j]) {
			$insertBase[$j + 1] = $insertBase[$j];
			$j --;
		}
		$insertBase[$j + 1] = $right;
	}
}