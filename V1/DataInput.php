<!DOCTYPE html>

<?php
include 'DataFunctions.php';

//database credentials
$server_Name = "localhost";
$database_Name = "data";
$username = "Kyriakos";
$password = "12345";
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>My input data website</title>
	</head>
	<body>
		<h1>My input data website</h1>
		<p>Go to main website: <a href="Index.php">Home</a></p>
		<form method ="POST"
			<p>
				<p><label>Email: 
					<input name="Email" type="text" size="25" maxlength="150" required></label></p>
				<p><label>Data Category/Description: 
					<input name="Category" type="text" size="25" maxlength="150" ></label></p>
				<p><label>Type: 
					<select name="Type" id="Type">
						<option value=""> Select Type </option>
						<option value="integer">Integer</option>
						<option value="string">String</option>
						<option value="decimal">Decimal</option>
						<option value="other">Other</option>
					</select></p>
				<p><label>Value: 
					<input name="Value" type="text" size="25" maxlength="1000" ></label></p>
				<p>
					<input type="submit" name ="InsertIntoData" value="Insert Data">
					<input type="reset" name ="clear" value="Clear">
				</p>	
			</p>
	</form>
	</body>
</html>
<?php
if (isset($_POST['InsertIntoData']))
{
	$data=array();
	
	//data
	$email=$_POST['Email'];
	$category=$_POST['Category'];
	$type=$_POST['Type'];
	$value=$_POST['Value'];
	
	//check email
	if(isEmail_($email))
	{
		echo "Correct Email Format:".$email. "<br>";
		$data["Email"]=$email;
	}
	else
	{
		echo "Invalid Email Format: " .$email. "<br>";
		die("Email is required field");
	}
	
	//check that type and value exist if the user has input category
	if($category=="" || $type=="" || $value=="")
	{
		if($category=="" && $type=="" && $value=="")
		{
			die("No data to be stored");
		}
		else if($category=="" && $type=="")
		{
			die("Input a category/description and select a type");
		}
		else if($category=="" && $value=="")
		{
			die("Input a category/description and value");
		}
		else if($category=="")
		{
			die("Input a category/description");
		}
		else if($type=="" && $value=="")
		{
			die("Select type and insert value");
		}
		else if($type=="")
		{
			die("Select type");
		}
		else if($value=="") 
		{
			die("Input a value");
		}
	}
	else
	{
		$data["Category"]=$category;
		if(ValueMatchesType($type,$value))
		{
			$data["Type"]=$type;
			$data["Value"]=$value;
			InsertIntoData($server_Name,$username,$password,$database_Name,$data);
		}
		else
		{
			echo "Value: ".$value." does not belong to Type: ".$type."<br>";
			die("Type selected does not match value input");
		}
	}
	
	//check that type and value
	
	
}