<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	error_reporting(0);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	

	$user_id_query = "SELECT user_id FROM tbusers WHERE email = '$email' AND password = '$pass'";
	$user_id_result = mysqli_query($mysqli, $user_id_query);
	if ($row = mysqli_fetch_array($user_id_result)) 
	{
		$user_id = $row['user_id'];
	}
	// echo $user_id;
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Reinhard Stoop">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);

				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	

					"<form action = 'login.php' method = 'post' enctype='multipart/form-data'>
								<div class='form-group'>


									<input type='hidden'  id='loginPass' name='loginPass' value = '$pass'/> 
									<input type='hidden' id='loginEmail' name='loginEmail' value = '$email'/> 

									<input type='file' class='form-control' name='fileToUpload' id='fileToUpload' /><br/>

									<input type='submit' class='btn btn-light' value='Upload Image' name='submit' />

								</div>

					</form>";
					
					$target_dir = "gallery/";
					$uploadFile = $_FILES["fileToUpload"]["name"];
					$fileType =  $_FILES["fileToUpload"]["type"];
					$fileSize =  $_FILES["fileToUpload"]["size"];
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

					if (isset($_POST["submit"])) 
					{
						//echo $uploadFile;
						//echo $target_file;
						
						if ($fileType == "image/jpg" || $fileType == "image/jpeg" && $fileSize < 1048576) 
						{
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
							{
								//insert stuff into gallery table
								$galleryQuery = "INSERT into tbgallery (user_id, filename) VALUES ('$user_id' , '$uploadFile');";
								$result = mysqli_query($mysqli, $galleryQuery);

								//$res = $mysqli->query($query);
								if ($result) 
								{
									echo "Table updated" . "</br>";
								}
								else
								{
									echo "dafuck dey doing over";
								}

							    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
							} 
							else 
							{
							    echo "Sorry, there was an error uploading your file.";
							}							
						}
						else 
						{
							echo "Sorry, Your file was not uploaded";
						}

						// echo $target_dir;
					}

				}

				else
				{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}

		?>
	</div>

			<div class = 'container'>
				<h1>Image Gallery</h1>	
					<div class = 'row imageGallery'>
						<?php 
							$image_query = "SELECT filename  FROM tbgallery WHERE user_id = '$user_id'";
							$image_result = mysqli_query($mysqli, $image_query);
							while ($row = mysqli_fetch_array($image_result)) 
							{
								// $user_id = $row['user_id'];
								echo 
								"
								<div class='col-3' style='background-image: url(gallery/".$row["filename"].")'>

								</div>
								";
							}							
						 ?>
					</div>
			</div>

</body>
</html>