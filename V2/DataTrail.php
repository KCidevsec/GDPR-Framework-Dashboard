<?php
include 'DataTrailFunctions.php';

?>

<html>
	<head>
		<meta charset="utf-8">
		<title>My Testing Side</title>
		<!-- <link rel="stylesheet" type="text/css" href="css/trail.css"> -->
	</head>
<body>
	<h1>Data Tracker</h1>
	<p>Go to data input website: <a href="DataInput.php">Data Input</a></p>
	<p>Go to transfer data website: <a href="ShareData.php">Share Data</a></p>
	<p>Go to main website: <a href="Index.php">Home</a></p>
	<form method ="POST"
		<p><label>Enter Your email: <input name="email" type="text" size="15" maxlength="99" ></label></p>
		<p><label>Select Company: 
		<select name="company" >
		<option value="A">Company A</option>
		<option value="B">Company B</option>
		<option value="C">Company C</option>
		<option value="D">Company D</option>
		<option value="E">Company E</option>
		<option value="F">Company F</option>
		</label></p>
		<p><input type="submit" class= "button" name ="DataTracker" value="Sumbit"></p>
	</form>
	<?php
		if (isset($_POST['DataTracker']))
		{
			$email=$_POST['email'];
			$company=$_POST['company'];
			
			//check email
			if(isEmail($email))
			{
				echo "Correct Email Format:".$email. "<br>";
				$Vemail=$email;
			}
			else
			{
				echo "Invalid Email Format: " .$email. "<br>";
				die("Email is required field");
			}
			
			//get credentials
			$credentials=GetCredentials($company);
			//check if email exist
			if(EmailExistInRecords($credentials["server_Name"],$credentials["username"],$credentials["password"],$credentials["database_Name"],$Vemail))
			{
				echo "Email ".$Vemail." found in Company ".$company."<br>";
			}
			else
			{
				die("Email does not exist in the database of Company ".$company."<br>");
			}
			$data=RetrieveData($credentials["server_Name"],$credentials["username"],$credentials["password"],$credentials["database_Name"],$Vemail);
			$trail=GetTrail($Vemail,$company);
			var_dump($data);
			var_dump($trail);
		}
	?>
</body>	
</html>