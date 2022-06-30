function request(url, data, callback) {
	
	let xhr = new XMLHttpRequest();
	xhr.open('POST', url, true);
	let loader = document.createElement('div');
	loader.className = 'loader';
	document.body.appendChild(loader);
	xhr.addEventListener('readystatechange', function() {
		if(xhr.readyState === 4) {
			if(callback) {
				callback(xhr.response);
			}
			loader.remove();
		}
	});

	let formdata = data ? (data instanceof FormData ? data : new FormData(document.querySelector(data))) : new FormData();

	let csrfMetaTag = document.querySelector('meta[name="csrf_token"]');
	if(csrfMetaTag) {
		formdata.append('csrf_token', csrfMetaTag.getAttribute('content'));
	}

	xhr.send(formdata);
}

function login() {
	request('php/login.php', '#loginForm', function(data) {
		document.getElementById('errs').innerHTML = "";
		let transition = document.getElementById('errs').style.transition;
		document.getElementById('errs').style.transition = "none";
		document.getElementById('errs').style.opacity = 0;
		switch(data) {
			case '0':
				window.location = './';
				break;
			case '1':
				document.getElementById('errs').innerHTML += '<div class="err">Incorrect username or password</div>';
				break;
			case '2':
				document.getElementById('errs').innerHTML += '<div class="err">Failed to connect to database. Please try again later.</div>';
				break;
			case '3':
				document.getElementById('errs').innerHTML += '<div class="err">You have exceeded the max number of login attempts per hour. Try again in an hour.</div>';
				break;
			case '4':
				document.getElementById('errs').innerHTML += '<div class="err">Your email has not been validated.</div>';
				break;
			default:
				document.getElementById('errs').innerHTML += '<div class="err">An unknown error occurred. Please try again later.</div>';
		}
		setTimeout(function() {
			document.getElementById('errs').style.transition = transition;
			document.getElementById('errs').style.opacity = 1;
		}, 10);
	});
}


function logout() {
	request('php/logout.php', false, function(data) {
		if(data === '0') {
			window.location = 'login.php';
		}
	});
}