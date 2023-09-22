<!DOCTYPE html>

<?php
include 'SqlCommands.php';
include 'RecordsFunctions.php';
include 'SetKeyPath.php';
include 'MetaDataFunctions.php';

//database credentials
$server_Name = "localhost";
$database_Name = "company_e";
$username = "Kyriakos";
$password = "12345";
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>My Testing Side</title>
		<link rel="stylesheet" type="text/css" href="css/page.css">
	</head>
<body>
	<h1>My Testing Side</h1>
	<p>Go to data input website: <a href="DataInput.php">Data Input</a></p>
	<p>Go to transfer data website: <a href="ShareData.php">Share Data</a></p>
	<h3>Constructor</h3>
	<form method ="POST"
			<p>
				<p><label>ServerName: 
					<input name="server_name" type="text" size="15" maxlength="25" ></label></p>
				<p><label>Database_Name: 
					<input name="database_name" type="text" size="15" maxlength="25" ></label></p>
				<p><label>Username: 
					<input name="username" type="text" size="15" maxlength="25" ></label></p>
				<p><label>Password: 
					<input name="password" type="text" size="15" maxlength="25" ></label></p>
				<p>
					<input type="submit" class= "button"  name="DConstructor" value="Call Constructor (Default Credentials)">
					<input type="submit" class= "button"  name="IConstructor" value="Call Constructor (Filled Credentials)">
				</p>
			</p>
	</form>
<?php
if($_POST['DConstructor'] and $_SERVER['REQUEST_METHOD'] == "POST")
{

    echo "Trying to Create Table Records<br>"; 
	CreateTableRecord($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table MetaData<br>";
	CreateTableMetaData($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table Data<br>";
	CreateTableData($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table Forward_Reference<br>";
	CreateTableForward_Reference($Server_Name,$username,$password,$database_Name);
}
if($_POST['IConstructor'] and $_SERVER['REQUEST_METHOD'] == "POST")
{
	$server_Name = $_POST['server_name'];
	$database_Name = $_POST['database_name'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($server_Name=="")
		die("Server Name Required");
	if($database_Name=="")
		die("Database Name required");
	if($username=="")
		die("User Name Required");
	if($password=="")
		die("Password Required");
	
	echo "Trying to Create Table Records<br>"; 
	CreateTableRecord($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table MetaData<br>";
	CreateTableMetaData($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table Data<br>";
	CreateTableData($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table Forward_Reference<br>";
	CreateTableForward_Reference($Server_Name,$username,$password,$database_Name);
}
?>
	<br>
	<br>
	
	<h3>Registration Form for records</h3>
	<p> Please fill out this form with your personal information</p>
	<form method ="POST">
		<p><label>Email: 
			<input name="email" type="text" size="15" maxlength="99" required></label></p>
		<p><label>First Name:
			<input name="fname" autofocus type="text" size="15" maxlength="49"></label></p>
		<p><label>Last Name:
			<input name="sname" type="text" size="15" maxlength="25" ></label></p>
		<p><label>Street Address:
			<input name="address" type="text" size="15" maxlength="99"></label></p>
		<p><label>City:
			<input name="city" type="text" size="15" maxlength="50"></label></p>
		<p><label>Country:
			<input name = "country" type="text" placeholder = "Type your Country" list = "countries" />
					<datalist id = "countries">
						<option value = "United States">
						<option value = "United Kingdom">
						<option value = "Germany">
						<option value = "Canada">
						<option value = "France">
						<option value = "Italy">
						<option value = "Australia">
						<option value = "Netherlands">
						<option value = "Espania">
						<option value = "Denmark">
						<option value = "Sweden">
						<option value = "Belgium">
						<option value = "Cyprus">
					</datalist></label></p>
		<p><label>ZIP:
			<input name="zip" type="text" size="15" maxlength="9"></label></p>
	<h3>Meta Data form</h3>
	<p>Additional Information:</p>
		<p><label>Company Name:
			<input name="company" type="text" size="15" maxlength="50" required></label></p>
		<p><label>Select Primary Key File
			<input name="primaryKeyPath" type="file" id="primaryKeyPath" required></label></p>
				<script>
					document.getElementById("primaryKeyPath").addEventListener("change", function() {
					alert(this.value);
					}, false);
				</script>
		<p><label>Primary Key Pass-phrase:
			<input name="primaryKey" type="password" size="15" maxlength="50" ></label></p>
		<p>
			<input type="submit" class= "button" name ="InsertIntoRecords" value="Insert Into Records Table">
			<input type="reset" class= "button" name ="clear" value="Clear">
		</p>	
	</form>
<?php
if (isset($_POST['InsertIntoRecords']))
{
	$record=array();
	
	//record data
	$email=$_POST['email'];
	$fname=$_POST['fname'];
	$sname=$_POST['sname'];
	$address=$_POST['address'];
	$city=$_POST['city'];
	$country=$_POST['country'];
	$zip=$_POST['zip'];
	//meta data
	$company=$_POST['company'];
	$primaryKeyPath=$_POST['primaryKeyPath'];
	$primaryKey=$_POST['primaryKey'];
	
	//check email
	if(isEmail($email))
	{
		echo "Correct Email Format:".$email. "<br>";
		$record["email"]=$email;
	}
	else
	{
		echo "Invalid Email Format: " .$email. "<br>";
		die("Email is required field");
	}
	
	//check name
	if($fname=="")
	{
		echo "First Name set to null<br>";
		$record["fname"]="NULL";
	}
	else
	{
		if(isString($fname))
		{
			echo "Correct Name Format:".$fname. "<br>";
			$record["fname"]=$fname;	
		}
		else
		{
			die("Invalid first name format: ".$fname. "<br>");
		}
	}
	
	//check surname
	if($sname=="")
	{
		echo "Surname set to null<br>";
		$record["sname"]="NULL";
	}
	else
	{
		if(isString($sname))
		{
			echo "Correct Surname Format:".$sname. "<br>";
			$record["sname"]=$sname;	
		}
		else
		{
			die("Invalid surname format: ".$sname. "<br>");
		}
	}
	
	//check street address
	if($address=="")
	{
		echo "Address set to null<br>";
		$record["address"]="NULL";
	}
	else
	{
		if(isStreetAddress($address))
		{
			echo "Correct address Format:".$address. "<br>";
			$record["address"]=$address;	
		}
		else
		{
			die("Invalid address format: ".$address. "<br>");
		}
	}
	
	//check city
	If($city=="")
	{
		echo "city set to null<br>";
		$record["city"]="NULL";
	}
	else
	{
		if(isString($city))
		{
			echo "Correct City Format:".$city. "<br>";
			$record["city"]=$city;	
		}
		else
		{
			die("Invalid city format: ".$city. "<br>");
		}
	}
	
	//check country
	if($country=="")
	{
		echo "country set to null<br>";
		$record["country"]="NULL";
	}
	else
	{
		if(isString($country))
		{
			echo "Correct country Format:".$country. "<br>";
			$record["country"]=$country;	
		}
		else
		{
			die("Invalid country format: ".$country. "<br>");
		}
	}
	
	//check zip
	if($zip=="")
	{
		echo "zip set to null<br>";
		$record["zip"]="NULL";
	}
	else
	{
		if(isZIP($zip))
		{
			echo "Correct zip Format:".$zip. "<br>";
			$record["zip"]=$zip;	
		}
		else
		{
			die("Invalid zip format: ".$zip. "<br>");
		}
	}
	
	//check company
	if(isString($company))
	{
		echo "Correct Company Format:".$company. "<br>";
	}
	else
	{
		die("Invalid Company format: ".$company. "<br>");
	}
	
	//check private key file path
	if(!checkFileExtension($primaryKeyPath))
	{
		die("Invalid Primary Key file format, required \".pem\""."<br>");
	}
	else
	{
		//setting the real resource path
		$primaryKeyPath=SetKeyPath($primaryKeyPath);
		echo "Primary key file path set to: ".$primaryKeyPath."<br>";
	}
	
	//check pass phrase (can be null)
	if($primaryKey=="")
	{
		echo "Pass-phrase set to: Null <br>";
	}
	else
	{
		echo "Pass-phrase set to: ".$primaryKey. "<br>";
	}
	
	
if(InsertIntoRecords($server_Name,$username,$password,$database_Name,$record))
{
	InsertIntoMetaData($server_Name,$username,$password,$database_Name,$record["email"],$company,$primaryKeyPath,$primaryKey);
}
}
?>
</body>	
</html>