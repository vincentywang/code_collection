var request;
if (window.XMLHttpRequest) {
	request = new XMLHttpRequest();
} else {
	request = new ActiveXObject("Microsoft.XMLHTTP");
}

request.open('GET', 'data.json'); // or POST, from some resource URL
request.onreadystatechage = function() {
	if ((request.status === 200) && (request.readyState ===4)) {

		var data = JSON.parse(request.responseText);
		// function hadnle this data response go here.
		
	}
}

request.send();
