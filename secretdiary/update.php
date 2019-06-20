<?php
session_start();
$DB_HOST = 'lehichcodmfrogga.mysql.db';
	$DB_USERNAME = 'lehichcodmfrogga';
	$DB_PASSWORD = 'EoZcYG7yhhiG8EHakPPz';
	$DB_NAME = 'lehichcodmfrogga';
	 
	$link=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

	if(isset($_POST['textDiary'])) {
		
		if(isset($_COOKIE['customerId'])) {
			
			$update = "UPDATE `Users` SET `diary`='".mysqli_real_escape_string($link,$_POST['textDiary'])."' WHERE `cookie`='".mysqli_real_escape_string($link,$_SESSION['post_data'])."' LIMIT 1";
			
			mysqli_query($link,$update);
			
		} else {
		
				$update = "UPDATE `Users` SET `diary`='".mysqli_real_escape_string($link,$_POST['textDiary'])."' WHERE `email`='".mysqli_real_escape_string($link,$_SESSION['post_data'])."' LIMIT 1";
				
				mysqli_query($link,$update);
				
				
			}
		
		
		
	}
	
	else echo "Ajax not working";
	

?>