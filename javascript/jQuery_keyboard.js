
/**
 * Tab : 9
 * Enter : 13
 * Shift : 16
 * Control : 17
 * Caps Lock : 20
 * ESC : 27
 */

$ele.on('keypress', function(e) {
	
	var code = (e.keyCode ? e.keyCode : e.which);

	if (code == 13) { // enter key

		e.preventDefault(); // this optional

		// do what you need to do

	}
})

$ele.on('keyup', function(e) {
	
	var code = (e.keyCode ? e.keyCode : e.which);

	if (code == 13) { // enter key

		e.preventDefault(); // this optional

		// do what you need to do

	}
})