<?php 
//hold the data to be input in db
$dataArray=array();

function InsertIntoData($server_Name,$username,$password,$database_Name,$data)
{
	//double check if input is email 
	if(isEmail_($data["Email"]))
	{	//double check if email exist in database
		if(EmailExistInRecords($server_Name,$username,$password,$database_Name,$data["Email"]))
		{
			$uri_=getURI($server_Name,$username,$password,$database_Name,$data["Email"]);
			if($uri_!="null")
			{
				echo "URI match found ".$uri_."<br>";
				$dataArray["URI"]=$uri_;
			}
			else
			{
				die("URI match for ".$data["Email"]." not found<br>");
			}
		}
		else
		{
			die("Email: ".$data["Email"]. " not found in the database<br>");
		}
	}
	else
	{
		die("No email match found for: " .$data["Email"]. "<br>");
	}
	
	$dataArray["Category"]=$data["Category"];
	$dataArray["Type"]=$data["Type"];
	$dataArray["Value"]=$data["Value"];
	
	try 
		{
			$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			echo "Server Connection Established<br>";
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "INSERT INTO data (URI, Category, Type, Value)
			VALUES (:URI,:Category,:Type,:Value)");
			$sql->bindParam(':URI',$dataArray["URI"]);
			$sql->bindParam(':Category',$dataArray["Category"]);
			$sql->bindParam(':Type',$dataArray["Type"]);
			$sql->bindParam(':Value',$dataArray["Value"]);
			
			// use exec() because no results are returned
			$sql->execute();
			
			echo "Data successfully Stored";
		}
	catch(PDOException $e)
	{
		//testing purpose
		echo "Error: " . $e->getMessage()."<br>";
	}

	$conn = null;
}

function getURI($server_Name,$username,$password,$database_Name,$email)
{
	try 
	{
		$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		
		//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
		
		$sql =$conn->prepare( "SELECT URI 
								FROM metadata 
								WHERE Email = :Email");
		$sql->bindParam(':Email',$email);
		$sql->execute();
		
		// set the resulting array to associative
		if($sql->rowCount()==0)
		{
			$result="null";
		}
		else
		{
			$array_result = $sql->fetch(PDO::FETCH_ASSOC);
			$result=$array_result['URI'];
		}
	}
	catch(PDOException $e) 
	{
		echo "Error: " . $e->getMessage();
	}

	$conn = null;
	return $result;
}

function isEmail_($email)
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		return 0;
	return 1;
}

function EmailExistInRecords($server_Name,$username,$password,$database_Name,$email)
{
	try 
	{
		$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//sanitizing values
		//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
		$sql =$conn->prepare( "SELECT * FROM records
								WHERE Email LIKE :Email");
		$sql->bindParam(':Email',$email);
		$sql->execute();
		
		// set the resulting array to associative
		if($sql->rowCount()==0)
			$flag=false;
		else
			$flag=true;
	}	
	catch(PDOException $e) 
	{
		echo "Error: " . $e->getMessage();
	}

	$conn = null;
	return $flag;
}

function ValueMatchesType($type,$value)
{
	if($type=="integer")
		return isInteger($value);
	if($type=="string")
		return isString($value);
	if($type=="decimal")
		return isDecimal($value);
	if($type=="other" && $value!="")
		return true;
	
	return false;
}

function isString($value)
{
	if (!preg_match("/^[a-zA-Z ]*$/",$value)) 
		return false;
	return true;
}

function isInteger($value)
{
	//check if string contains only digits
	if (ctype_digit($value))
		return true;
	return false;
}

function isDecimal($value)
{
	$value=$value+0;
	if (is_float($value))
		return true;
	return false;
}



?>