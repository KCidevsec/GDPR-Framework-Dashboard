<?php
include 'DATA_TO_XML.php';
include 'DigitalSignature_Encryption_Decryption.php';

//array to store varchar data
$meta_varchar_array=array();
//array to store timestamps
$meta_timestamps_array=array();

function InsertIntoMetaData($server_Name,$username,$password,$database_Name,$email,$company_name,$private_key_path,$KeyPassphrase)
{	
	//double check if input is email 
	if(isEmail_($email))
	{	//double check if email exist in database
		if(EmailExistInRecords($server_Name,$username,$password,$database_Name,$email))
		{
			$meta_varchar_array["email"]=$email;//set email as meta data in input array
		}
		else
		{
			die("Email: ".$email. "not found in the database<br>");
		}
	}
	else
	{
		die("No email match found for: " .$email. "<br>");
	}
	
	//create URI
	$meta_varchar_array["URI"]=setURI($email,$company_name);
	
	//Genesis_Time is been set by the server as Default
	
	//create datetime Creation_Time
	$meta_timestamps_array["creation_time"]=setCreationTime();
	
	//create expiration time
	$meta_timestamps_array["expiration_time"]=setExpirationTime();
	
	//Backword_Ref since the data are created now is it self
	$meta_varchar_array["backword_ref"]=$company_name;
	
	//Indirect_Backward_Ref is set to null for part 1
	$meta_varchar_array["Indirect_Backward_Ref"]=setIndirectBackwardRef();
	
	//root is it self since is been created now
	$meta_varchar_array["Backword_root_ref"]=$company_name;
	
	//storing original record to xml format
	$meta_varchar_array["original_record"]=GetRecordToXML($server_Name,$username,$password,$database_Name,$email,$meta_varchar_array,$meta_timestamps_array);
	
	//Sign XML with private key ---- Provides Confidentiality NOT INTEGRITY
	$meta_varchar_array["digital_signature"]=EncryptWithPrivateKey($meta_varchar_array["original_record"],$private_key_path,$KeyPassphrase);
	
		try 
		{
			$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			echo "Server Connection Established<br>";
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "INSERT INTO metadata (URI, Email,Creation_Time, Expiration_time,Indirect_Backward_Ref,Backward_Ref,
			Original_Record,Backward_Root_Ref,Digital_Signature)
			VALUES (:URI,:Email,:Creation_Time,:Expiration_time,:Indirect_Backward_Ref,:Backward_Ref,:Original_Record,:Backward_Root_Ref,:Digital_Signature)");
			$sql->bindParam(':URI',$meta_varchar_array["URI"]);
			$sql->bindParam(':Email',$meta_varchar_array["email"]);
			$sql->bindParam(':Creation_Time',$meta_timestamps_array["creation_time"]);
			$sql->bindParam(':Expiration_time',$meta_timestamps_array["expiration_time"]);
			$sql->bindParam(':Indirect_Backward_Ref',$meta_varchar_array["Indirect_Backward_Ref"]);
			$sql->bindParam(':Backward_Ref',$meta_varchar_array["backword_ref"]);
			$sql->bindParam(':Original_Record',$meta_varchar_array["original_record"]);
			$sql->bindParam(':Backward_Root_Ref',$meta_varchar_array["Backword_root_ref"]);
			$sql->bindParam(':Digital_Signature',$meta_varchar_array["digital_signature"]);
			
			// use exec() because no results are returned
			$sql->execute();
			
			echo "Meta data generated successfully";
		}
		catch(PDOException $e)
		{
			//testing purpose
			echo "Error: " . $e->getMessage()."<br>";
		}

		$conn = null;

}

//check if the input is valid email
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

function setURI($email,$company_name)
{
	if($email=="" || $company_name=="")
	{
		die("Could not set the URI");
	}
	else
	{
		//URI is a concatenation of Companies name, clients email and a random generated number
		$uri=$company_name;
		$uri .="|";//seperate company and email with|
		$uri .=$email;
		$uri .="|";//seperate email and random_bytes
		//generating random bytes numbers
		$rand_bytes = openssl_random_pseudo_bytes(10);
		//concatenating hexadecimal value to uri 
		$uri .= bin2hex($rand_bytes);
		return $uri;
	}
}

function setCreationTime()
{
//get time of creation 
//wamp is located to france , substract 1 hour to get local cyprus time 1hour=3600 sec
$time=time()+3600;
//change format to  2010-02-06 19:30:13
$ctime=date("Y-m-d H:i:s",$time);
return $ctime;
}

function setExpirationTime()
{
//setting expiration date to 3 years from creation
return date("Y-m-d H:i:s",strtotime('+3 years 1 hour'));
}

function setIndirectBackwardRef()
{
	$value="null";
	return $value;
}


?>