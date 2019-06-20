<?php
	session_start();
	autoLogin();

 $DB_HOST = 'lehichcodmfrogga.mysql.db';
 $DB_USERNAME = 'lehichcodmfrogga';
 $DB_PASSWORD = 'EoZcYG7yhhiG8EHakPPz';
 $DB_NAME = 'lehichcodmfrogga';
	
	$link=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
	if (mysqli_connect_errno()) {
		
		echo "There was a problem connecting the database";
		
	}
	
	
	// Prendre le cookie, trouver le compte dans la base de données et se connecter au diary adéquat.
	function login() {
		
		
		
		header("Location: http://lehich.com/projects/secretdiary/session.php");
		
	}
	
	function keepLoggedIn($id) {
		$link=mysqli_connect("shareddb-j.hosting.stackcp.net","database-mysql-3935c87d","abcabcokdzao.@","database-mysql-3935c87d");

		if (isset($_POST["stay-loggedin"])) {
					
					$cookieId=md5($id);
					$update_cookie = "UPDATE `Users` SET `cookie` ='".mysqli_real_escape_string($link,$cookieId)."' WHERE `id` = '".$id."' LIMIT 1";
					
					setcookie("customerId",$cookieId, time() + 60 * 60 * 24); 
					$update_cookie_query = mysqli_query($link,$update_cookie);
				
		}
	}
	
	function autoLogin() {
		
		if(isset($_COOKIE["customerId"])) {
		
			login();
		
		}
		
	}

	$error = "";
	
	if($_POST) {
		
		
		
		// Login system
		if (isset($_POST["login-submit"])) {
			
			if ($_POST["login-email"] == "") {
			
				$error .="Please add a valid email address <br>";
			}
			
			if ($_POST["login-password"] == "") {
				
				$error .="Please add a password";
				
			}
			
			if ($_POST["login-email"] != "" && $_POST["login-password"] != "") {
				
				
				$look_for_login = "SELECT `id`, `password` FROM `Users` WHERE `email`='".mysqli_real_escape_string($link,$_POST['login-email'])."'";
				
				$query_login=mysqli_query($link,$look_for_login);
				
				$login_array=mysqli_fetch_array($query_login);
				
				
				
				
				// vérification s'il n'est pas enregistré puis enregistrement dans la database
				
				if ($login_array["id"] != 0 && password_verify($_POST['login-password'], $login_array["password"]) ) {
					
					$_SESSION['post_data'] = $_POST["login-email"];
					keepLoggedIn($login_array["id"]);
					login();
				
					
				} else {$error.= "A problem occured, either you are not registered yet or you tiped a wrong password.";}
				
				
			}
			
		}
		
		// Sign up system
		if (isset($_POST["signup-submit"])) {
			
			//Check erreur pwd ou email
			if ($_POST["signup-email"] == "") {
			
				$error .="Please add a valid email address <br>";
			}
			
			if ($_POST["signup-password"] == "") {
				
				$error .="Please add a password";
				
			}
			
			//Procédure pour Signup et login
			if ($_POST["signup-email"] != "" && $_POST["signup-password"] != "") {
				
				
				$look_for_login = "SELECT `id`, `password` FROM `Users` WHERE `email`='".mysqli_real_escape_string($link,$_POST['signup-email'])."'";
				
				$query_login=mysqli_query($link,$look_for_login);
				
				$login_array=mysqli_fetch_array($query_login);
		
				if ($login_array["id"] != 0) {
					
					$error.= "A problem occured, either you are not registered yet or you tiped a wrong password.";
				
					
				} 
				else {
					
					$_SESSION['post_data'] = $_POST["signup-email"];
					
					$hash = password_hash($_POST['signup-password'],PASSWORD_DEFAULT);
					
					$signup = "INSERT INTO `Users` (`email`,`password`) VALUES ('".mysqli_real_escape_string($link,$_POST['signup-email'])."','".mysqli_real_escape_string($link,$hash)."')";
					
					$signup_query=mysqli_query($link,$signup);
					
					keepLoggedIn($login_array["id"]);
					login();
				}
				
			}
		
		
		}
	
	
	}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<style type="text/css">
	
		#divSelector { 
			width: 100%;
			height: 100%;  
			position: fixed;
			top: 0;
			left: 0;
			z-index: 0; 
			background-image: url("images/background2.jpg") ;
			background-repeat:no-repeat;
			background-position: center center;
			/* background-attachment: fixed; removed for Android */
			  -webkit-background-size: cover;
			  -moz-background-size: cover;
			  -o-background-size: cover;
			  background-size: cover;
			}
	
	
		input {
			
			margin : 10px auto;

			
		}
		
		.input-text {
			
			width:300px;
			
		}
		
		.margin {
			
		
			margin-top:150px;
			
			
		}
		
		h1 {
			
			font-size:350%;
			font-weight:bold;
			color:white;
			margin-bottom:30px;
			
		}
		
		label {
			
			color:white;
			
		}
		
		.text-intro {
			
			color:white;
			font-weight:bold;
			
		}
		
		#signinDiv {
			
			display:none;
			
		}
		
		.link {
			
			color:orange;
			text-decoration:underline;
			
		}
	
	</style>
	


    <title>Secret Diary</title>
  </head>
  <body>
  
	<div id="divSelector">
	  
		<h1 class="text-center margin">The Secret Diary</h1>
		
		<div id="loginDiv" class="container">
			
			<form id="login_form" method="post">
				
				<p class="text-center text-intro">Log in to your personal diary !</p>
				
				<input type="email" class="form-control input-text" name="login-email" placeholder="Your email" >
				
				<input type="password" class="form-control input-text" name="login-password">
				
				<div class="text-center">
					
					
					<a href="" class="link" id="get-signin" >You don't have an account ? Click here to Sign In !</a><br>
					
					<input type="checkbox" name="stay-loggedin" value="login">
					<label class="form-check-label" for="stay-loggedin">Stay Logged In</label><br>
				
					<input type="submit" class="btn btn-success" name="login-submit" value="Login"> <br>
				
				</div>
				
				
			</form>	
		
		</div>
		
		
		<div id="signinDiv" class="container">
		
			<p class="text-center text-intro">Sign up and get access to your personal diary !</p>
		
			<form id="signup_form" method="post">
			
				 <input type="email" class="form-control input-text" placeholder="Your email" name="signup-email">
				 
				 <input type="password" class="form-control input-text" name="signup-password">
				 
				 <div class="text-center">
					 
					 <a href="" class="link" id="get-login">You already have an account ? Click here to Log In !</a><br>
					 
					 <input type="checkbox" name="stay-loggedin" value="signup">
					 <label class="form-check-label" for="stay-loggedin">Stay Logged In</label>
					 <br>
					 
					 
					 <input type="submit" class="btn btn-success" name="signup-submit" value="Sign Up">
				
				</div>
				
				
			</form>
	  
		</div>
		
		<div class="container">
		
			<?php 
			
				if ($error != "") {
					
					echo "<div class='alert alert-danger'>There was an error in your form:<br> ".$error."</div>";
					
		
				}
			?>
		
		</div>
		

	</div>
    
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  
	<script type="text/javascript">
	
		$("#get-signin").click(function(event) {
			
			event.preventDefault();
			$("#loginDiv").hide();
			$("#signinDiv").show();
			
			
			
		});
		
		$("#get-login").click(function(event) {
			
			event.preventDefault();
			$("#loginDiv").show();
			$("#signinDiv").hide();
			
			
			
		});
	
	
	</script>
  
  </body>
</html>