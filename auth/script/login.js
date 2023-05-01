function checkUsername() {
	// regex to check if username is valid
	var regex = /^[a-zA-Z0-9]+$/;
	var username = document.getElementById("username").value;
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
	//ajax request to login.php
	// (A) GET FORM DATA
	var data = new FormData();
	var hCaptchaResponse = document.getElementsByName('h-captcha-response');

	data.append("username", document.getElementById("username").value);
	data.append("password", document.getElementById("password").value);
	data.append("captchaResponse", hCaptchaResponse[0].value);


	// (B) AJAX
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "script/loginScript.php");
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
