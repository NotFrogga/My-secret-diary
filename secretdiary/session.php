<?php
	include 'databaseinfo.php';
	session_start();

 $DB_HOST = '$databse["DB_HOST"]';
 $DB_USERNAME = '$databse["DB_USERNAME"]';
 $DB_PASSWORD = '$databse["DB_PASSWORD"]';
 $DB_NAME = '$databse["DB_NAME"]';

	 
	$link=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
	if (mysqli_connect_errno()) {
		
		echo "There was a problem connecting the database";
		
	}
	
	logOut();
	
	getAccount();
	
	// Message introduction utilisateur
	$message="";
	
	
	function getAccount() {
		$link=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
		if(isset($_COOKIE["customerId"])){
		
			$query = "SELECT `email` FROM `Users` WHERE `cookie` ='".$_COOKIE["customerId"]."' LIMIT 1";
			
			$customer_array = mysqli_fetch_array(mysqli_query ($link,$query));
				

			$message="Hello ".$customer_array["email"]." !";

		
		
		} else { $message= "Hello you !";}
		
	}
	
	function logOut(){
		
		if(isset($_POST["logout"])){
			
			
			header("Location: http://lehich.com/projects/secretdiary/index.php");
			setcookie("customerId","", time() - (60 * 60 * 24));
			
		
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Your diary !</title>
	
	
	
	
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
		
		#divDiary {
			
			height:90vh;
			
		}
		
		#diary {
			
			height:100%;
			
			
		}
	
	</style>
	 
  </head>
  <body>
	
	<div id="divSelector">
		
		<div class="container">
		
		
			<form method="post">

				<input type="submit" class="btn btn-primary" name="logout" value="Log Out">
			
			</form>
		
		</div>
		
		<div id="divDiary" class="container">
		
			
			
			
				<textarea id="diary" name="textDiary" class="form-control"><?php 
				
				
					if(isset($_COOKIE["customerId"])) {
						
						$diaryViaCookie = "SELECT `diary` FROM `Users` WHERE `cookie`='".mysqli_real_escape_string($link,$_COOKIE['customerId'])."' LIMIT 1";
						
						$query_cookie =mysqli_query($link,$diaryViaCookie);
						
						$diary_array_cookie= mysqli_fetch_array($query_cookie);
						
						print_r($diary_array_cookie["diary"]);
						
					} 
					else {
						$diaryViaEmail = "SELECT `diary` FROM `Users` WHERE `email`='".mysqli_real_escape_string($link,$_SESSION['post_data'])."' LIMIT 1";
						
						$diary_array_email= mysqli_fetch_array(mysqli_query($link,$diaryViaEmail));
						
						print_r($diary_array_email["diary"]);
						
						}
				
				?></textarea> <br>
	
			
			
		</div>
	
	</div>
	
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
		<script type="text/javascript">

		const URL = 'http://lehich.com/projects/secretdiary/update.php';
	
		$("#diary").bind('input propertychange',function(event) {
			
			event.preventDefault();
			textDiary = $("#diary").val();
			
			
			$.ajax({
				type	: 'post',
				url		: URL,
				data 	: {textDiary : textDiary},
			});
			
			
		});
	
	
		
	
	</script>
  
  
  </body>
</html>
