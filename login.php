<?php 
	require_once 'php/utils.php'; 
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	  <base href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/" />
    <meta charset="UTF-8" />
  	<meta name="csrf_token" content="<?php echo createToken(); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">  
    <title>IQGS Login</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <!-- style -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="./css/globalStyle.css">
    <link rel="stylesheet" href="./css/login.css">
  </head>
  <body>
      <div class="inputContainer">
        <form id ='loginForm'>
          <div id = 'errs' class = 'errorcontainer'>
            
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
    				<input class="form-control" id="email" name="email" type="email" autocomplete="email" required placeholder="Enter your email" onkeydown="if(event.key === 'Enter'){event.preventDefault();login();}" aria-describedby="emailHelp"/>
          <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Enter your password" onkeydown="if(event.key === 'Enter'){event.preventDefault();login();}" class="form-control">
          </div>
          <div class="btn-container">
            <button onclick="login();" type="button" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
  <script src="./js/account.js"></script>
  </body>
</html>
