<?php
/*
Notes for regular expressions php
abc…	Letters
123…	Digits
\d	Any Digit
\D	Any Non-digit character
.	Any Character
\.	Period
[abc]	Only a, b, or c
[^abc]	Not a, b, nor c
[a-z]	Characters a to z
[0-9]	Numbers 0 to 9
\w	Any Alphanumeric character
\W	Any Non-alphanumeric character
{m}	m Repetitions
{m,n}	m to n Repetitions
*	Zero or more repetitions
+	One or more repetitions
?	Optional character
\s	Any Whitespace
\S	Any Non-whitespace character
^…$	Starts and ends
(…)	Capture Group
(a(bc))	Capture Sub-group
(.*)	Capture all
(abc|def)	Matches abc or def
*/
//Mutators and accessors for the table Records

/*email is provided by user , need to check format before forward 
it to database since will be our main identifier*/
function InsertIntoRecords($server_Name,$username,$password,$database_Name,$records_array)
{	

	if(!DuplicateRecords($server_Name,$username,$password,$database_Name,$records_array["email"]))
	{

		try 
		{
			$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			echo "Server Connection Established<br>";
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "INSERT INTO Records (Email, Client_FName, Client_SName, Client_Address, Client_City, Client_Country, Client_ZIP)
			VALUES (:Email,:FName,:SName,:Address,:City,:Country,:ZIP)");
			$sql->bindParam(':Email',$records_array["email"]);
			$sql->bindParam(':FName',$records_array["fname"]);
			$sql->bindParam(':SName',$records_array["sname"]);
			$sql->bindParam(':Address',$records_array["address"]);
			$sql->bindParam(':City',$records_array["city"]);
			$sql->bindParam(':Country',$records_array["country"]);
			$sql->bindParam(':ZIP',$records_array["zip"]);
			
			// use exec() because no results are returned
			$sql->execute();
			
			echo "New record created successfully<br>";
			return true;
		}
		catch(PDOException $e)
		{
			//testing purpose
			echo "Error: " . $e->getMessage()."<br>";
		}

		$conn = null;
	}
	else
	{
		echo "Email Already exist<br>";
		return false;
	}
}

function DuplicateRecords($server_Name,$username,$password,$database_Name,$email)
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

//check if the input is valid email
function isEmail($email)
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		return 0;
	return 1;
}

//check if a string input contains only letter or whitespace
function isString($str)
{
	if (!preg_match("/^[a-zA-Z ]*$/",$str)) 
		return 0;
	return 1;
}

//check if string input is valid street address e.g. 
function isStreetAddress($address)
{
	if(!preg_match("/^[a-zA-Z0-9_:\-()'\" ]*$/",$address))
		return 0;
	return 1;
}

//check if string is valid ZIP
function isZIP($zip)
{
$ZIPREG=array(
	0=>"^\d{5}([\-]?\d{4})?$",//US
	"^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",//UK
	"\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",//DE
	"^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",//CA
	"^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$",//FR
	"^(V-|I-)?[0-9]{5}$",//IT
	"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",//AU
	"^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",//NL
	"^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",//ES
	"^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",//DK
	"^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",//SE
	"^[1-9]{1}[0-9]{3}$",//BE
	12=>"^[1-9]{1}[0-9]{3}$"//CY
);
	for($i=0;$i<13;$i++)
	{
		if (preg_match("/".$ZIPREG[$i]."/i",$zip))
			return 1;
	}
	return 0;
		
}
?>