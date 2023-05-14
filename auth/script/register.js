// Function to submit the form via Ajax
function submitRegistration(event) {
	event.preventDefault(); // Prevent default form behavior

	// Get values from input fields
	var firstName = document.getElementById("inputName").value;
	var lastName = document.getElementById("inputSurname").value;
	var email = document.getElementById("inputEmail").value;
	var username = document.getElementById("inputUsername").value;
	var password = document.getElementById("inputPassword").value;
	var confirmPassword = document.getElementById("inputConfirmPassword").value;
	var hCaptchaResponse = document.getElementsByName("h-captcha-response");

	// Regex pattern for valid first name and last name
	var nameRegex = /^([A-Z][a-z]+\s)?[A-Z][a-z]+$/;

	// Validate first name
	if (!firstName.match(nameRegex)) {
		alert("Please enter a valid first name.");
		return;
	}

	// Validate last name
	if (!lastName.match(nameRegex)) {
		alert("Please enter a valid last name.");
		return;
	}

	// Regex pattern for valid email address
	var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}$/;

	// Validate email address
	if (!email.match(emailRegex)) {
		alert("Please enter a valid email address.");
		return;
	}

	// Check if the two passwords match
	if (password !== confirmPassword) {
		alert("Passwords do not match. Please try again.");
		return;
	}

	// Regex pattern for password validation
	var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]).{8,32}$/;

	// Validate password
	if (!password.match(passwordRegex)) {
		alert("Password must contain at least 8 characters, maximum 32 characters, one special character, one numeric digit, one uppercase letter, and one lowercase letter.");
		return;
	}

	// Create an XMLHttpRequest object
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "script/registerScript.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");

	// Handle the backend or API response
	xhr.onload = function () {
		if (xhr.status >= 200 && xhr.status < 400) {
			
			var response = JSON.parse(xhr.responseText);
			if (response.status == 'success') { // process success response
				// TODO take the redirect page from json response and redirect
			
			}
			else{ // process error response
				// TODO gateaway for errors
			}









		} else {
			console.error(xhr.statusText); // Handle any errors
		}
	};

	// Handle connection errors
	xhr.onerror = function () {
		console.error("An error occurred while making the request.");
	};

	// Convert form data to URL-encoded format
	var formData = "firstName=" + encodeURIComponent(firstName) + "&lastName=" + encodeURIComponent(lastName) + "&email=" + encodeURIComponent(email) + "&username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password) + "&h-captcha-response=" + encodeURIComponent(hCaptchaResponse[0].value);

	// Send data to the backend or API
	xhr.send(formData);
}

// Add event listener to the form submit button
document.getElementById("registrationForm").addEventListener("submit", submitRegistration);
