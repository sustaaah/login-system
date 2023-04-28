function checkUsername() {
	// regex to check if username is valid
	var regex = /^[a-zA-Z0-9]+$/;
	var username = document.getElementById("user-name").value;
	if (regex.test(username)) {
		return true;
	} else {
		// TODO insert error message to css class
		return false;
	}
}

function checkPassword(element) {
	var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#\$!€()\-])(?=.*[^\s])[\S]{8,32}$/;
	
	var password = document.getElementById(element.id).value;
	if (regex.test(username)) {
		return true;
	} else {
		// TODO insert error message to css class
		return false;
	}
}

function login() {
	// TODO implement errors code
	// TODO finish script
	//ajax request to login.php
	// (A) GET FORM DATA
	var data = new FormData();
	data.append("", document.getElementById("user-name").value);
	data.append("email", document.getElementById("user-email").value);

	// (B) AJAX
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "login.php");
	// What to do when server responds
	xhr.onload = function () {
		console.log(this.response);
	};
	xhr.send(data);
	// catch errors
	xhr.onerror = function () {
		console.log(this.response);
	};

	// (C) PREVENT HTML FORM SUBMIT
	return false;
}
