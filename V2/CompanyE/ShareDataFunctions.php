<?php
include 'Transaction_Credentials.php';
include 'Verification_Credentials.php';
include 'DigitalSignature_Encryption_Decryption.php';

function SelectDataToBeTransfer($server_Name,$username,$password,$database_Name,$selected_emails,$company,$mySelf)
{
	$time_stamp=GetTimeStamp();
	//Server location to store XML data
	$id="Company ".$company ." ".$time_stamp;
	$path= "C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyE\Transaction History"."\\".$id.".xml";
	
	$emails=array();//array to hold emails selected
	$record=array();//array to hold data retrieve from records table
	$metadata=array();//array to hold data retrieve from metadata table 
	$data=array();//array to hold data retrieve from data table
	$credentials=array();
	$transaction_data;
	$emails=StringToArray($selected_emails);//calling a function to convert javascript string($selected_emails) into php array
	echo "Establishing Connections"."<br>";
	foreach($emails as $index => $email)
	{
		$record[$index]=GetRecordData($server_Name,$username,$password,$database_Name,$email);
		$metadata[$index]=GetMetaData($server_Name,$username,$password,$database_Name,$email,$mySelf);
		$data[$index]=GetData($server_Name,$username,$password,$database_Name,$metadata[$index]["URI"]);
	}
	echo "Fetching Data"."<br>";
	$xml_string=GetXmlString($record,$metadata,$data,$path,$transaction_data);//convert data to xml file and string
	echo "Creating XML backup of transmision"."<br>";
	$credentials=transaction_credentials($mySelf,$company);//get appropriate credentials
	$encryptedHashWithPrivate=EncryptWithPrivateKey($xml_string,$credentials["private_key_path"],$credentials["KeyPassphrase"]);//sign with your own private key
	echo "Signining MD5 HASH with my Private Key"."<br>";
	$xml_string_with_hash=$xml_string.$encryptedHashWithPrivate;
	echo "Adding MD5 HASH to xml"."<br>";
	echo "Transmiting Data"."<br>";
	$Authentication_Flag=VirtualVerification($xml_string_with_hash,$mySelf,$company);//authenticating data
	if(!$Authentication_Flag)
	{
		die ("Error Occurred in Authentication Procedure!");
	}
	$StoreData_Flag=StoreData($transaction_data,$credentials,$mySelf,$company);
	if($StoreData_Flag>0)
	{
		echo "Data Transferred: ".$StoreData_Flag."<br>";
	}
	//Create Link between source and destination
	$CreateLink_Flag=CreateLink($server_Name,$username,$password,$database_Name,$transaction_data,$company);
	if($StoreData_Flag>0 && CreateLink_Flag==true)
	{
		echo "Link between sender ".$mySelf." and recipient ".$company." created!"."<br>";
	}
}
function StringToArray($selected_emails)
{
	$emails=array();
	$i=substr_count($selected_emails, "|");
	$email="";
	
	while($i>0)
	{
	$email=substr($selected_emails,0,strpos($selected_emails,"|"));
	array_push($emails,$email);
	$selected_emails=substr($selected_emails,strpos($selected_emails,"|")+3);
	$i--;
	}
	return $emails;
}
function GetRecordData($server_Name,$username,$password,$database_Name,$email)
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
			
			//get result and convert it to array
			$querry_result=$sql->fetch(PDO::FETCH_ASSOC);
			
		}	
	catch(PDOException $e) 
		{
			echo "Error: " . $e->getMessage();
		}
			$conn = null;
			
			return $querry_result;		
}

function GetMetaData($server_Name,$username,$password,$database_Name,$email,$mySelf)
{
	try 
		{
			$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "SELECT * FROM metadata
									WHERE Email LIKE :Email");
			$sql->bindParam(':Email',$email);                   
			$sql->execute();
			
			//get result and convert it to array
			$querry_result=$sql->fetch(PDO::FETCH_ASSOC);
			
		}	
	catch(PDOException $e) 
		{
			echo "Error: " . $e->getMessage();
		}
			$conn = null;
			
			return $querry_result;		
}

function GetData($server_Name,$username,$password,$database_Name,$URI)
{
	try 
		{
			$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "SELECT * FROM data
									WHERE URI LIKE :Uri");
			$sql->bindParam(':Uri',$URI);
			$sql->execute();
			
			//get result and convert it to array
			$querry_result=$sql->fetch(PDO::FETCH_ASSOC);
			
		}	
	catch(PDOException $e) 
		{
			echo "Error: " . $e->getMessage();
		}
			$conn = null;
			
			return $querry_result;		
}

function GetXmlString($record,$metadata,$data,$path,&$transaction_data)
{
	$transaction_data=$record;
	
	foreach($transaction_data as $tindex => $tdata)
	{
		foreach($metadata as $mindex => $mvalue)
		{
			if($tindex==$mindex)
			{
				$transaction_data[$tindex]["MURI"]=$mvalue["URI"];//<<<<------------------same fields
				$transaction_data[$tindex]["MEmail"]=$mvalue["Email"];//<<<<------------------same fields
				$transaction_data[$tindex]["Genesis_Time"]=$mvalue["Genesis_Time"];
				$transaction_data[$tindex]["Creation_Time"]=$mvalue["Creation_Time"];
				$transaction_data[$tindex]["Expiration_Time"]=$mvalue["Expiration_Time"];
				$transaction_data[$tindex]["Backward_Ref"]=$mvalue["Backward_Ref"];
				$transaction_data[$tindex]["Original_Record"]=$mvalue["Original_Record"];
				$transaction_data[$tindex]["Indirect_Backward_Ref"]=$mvalue["Indirect_Backward_Ref"];
				$transaction_data[$tindex]["Backward_Root_Ref"]=$mvalue["Backward_Root_Ref"];
				$transaction_data[$tindex]["Digital_Signature"]=$mvalue["Digital_Signature"];
			}
		}
		foreach($data as $dindex => $ddata)
		{
			if($dindex==$tindex)
			{
				$transaction_data[$tindex]["DURI"]=$ddata["URI"];//<<<<------------------same fields
				$transaction_data[$tindex]["Category"]=$ddata["Category"];
				$transaction_data[$tindex]["Type"]=$ddata["Type"];
				$transaction_data[$tindex]["Value"]=$ddata["Value"];
			}
		}
	}
	
	// creating object of SimpleXMLElement
	$xml= new SimpleXMLElement('<?xml version="1.0"?><Original_Records></Original_Records>');
			
	// function call to convert array to xml
	array_to_xml($transaction_data,$xml);
	
	//creating xml file in directory
	$result = $xml->asXML($path);
	
	//converting xml to string
	$xml_string=htmlentities( $xml->asXML());
	
	return $xml_string;
}

// function defination to convert array to xml
function array_to_xml( $querry_result, &$xml) {
    foreach( $querry_result as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml->addChild("$key",htmlspecialchars("$value"));
        }
     }
}

function GetTimeStamp()
{
//get time of creation 
//wamp is located to france , substract 1 hour to get local cyprus time 1hour=3600 sec
$time=time()+3600;
//change format to  2010-02-06 19:30:13
$ctime=date("Y-m-d H-i-s",$time);
return $ctime;
}

function VirtualVerification($xml_string_with_hash,$mySelf,$company)
{
	$verification_credentials=veri_credentials($mySelf,$company);
	$encryptedWithPrivate=substr($xml_string_with_hash,-256);
	$y=DecryptWithPublicKey($encryptedWithPrivate, $decryptedWithPublicFromPrivate, $verification_credentials["public_key_path"],$verification_credentials["KeyPassphrase"]);
	
	if($y!=null)
	{
		echo "Unsigning MD5 HASH with public key of Company ".$mySelf."<br>";
	}
	else
		die("Unsing MD5 HASS proccess failed!"."<br>");
	
	$xml_string=substr($xml_string_with_hash,0,-256);
	$xml_md5=getHash($xml_string);
	if($xml_md5==$y)
	{
		echo "Data Authenticated!"."<br>"; 
		echo "Storage Procedure Initiating"."<br>";
		return true;
	}
	return false;
}

function StoreData($transaction_data,$credentials,$mySelf,$company)
{
	$rServer_Name=$credentials["server_Name"];
	$rUserName=$credentials["username"];
	$rPassword=$credentials["password"];
	$rDatabase_Name=$credentials["database_Name"];
	$counter=0;
	//check if records exist in recipient
	foreach($transaction_data as $item)
	{
		if(!DuplicateRecords($rServer_Name,$rUserName,$rPassword,$rDatabase_Name,$item["Email"],$mySelf))
		{
			$counter++;
			//store record data
			try 
			{
			$conn = new PDO("mysql:host=$rServer_Name;dbname=$rDatabase_Name", $rUserName, $rPassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "INSERT INTO Records (Email, Client_FName, Client_SName, Client_Address, Client_City, Client_Country, Client_ZIP)
			VALUES (:Email,:FName,:SName,:Address,:City,:Country,:ZIP)");
			$sql->bindParam(':Email',$item["Email"]);
			$sql->bindParam(':FName',$item["Client_FName"]);
			$sql->bindParam(':SName',$item["Client_SName"]);
			$sql->bindParam(':Address',$item["Client_Address"]);
			$sql->bindParam(':City',$item["Client_City"]);
			$sql->bindParam(':Country',$item["Client_Country"]);
			$sql->bindParam(':ZIP',$item["Client_ZIP"]);
			
			// use exec() because no results are returned
			$sql->execute();
			
			}
			catch(PDOException $e)
			{
			//testing purpose
			echo "Error: " . $e->getMessage()."<br>";
			}
			$conn = null;
			//store metadata
			//setting backward Reference to the sender
			$item["Backward_Ref"]="Company ".$mySelf;
			try 
			{
			$conn = new PDO("mysql:host=$rServer_Name;dbname=$rDatabase_Name", $rUserName, $rPassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "INSERT INTO metadata (URI, Email,Creation_Time,Expiration_time,Indirect_Backward_Ref,Backward_Ref,
			Original_Record,Backward_Root_Ref,Digital_Signature)
			VALUES (:URI,:Email,:Creation_Time,:Expiration_time,:Indirect_Backward_Ref,:Backward_Ref,:Original_Record,:Backward_Root_Ref,:Digital_Signature)");
			$sql->bindParam(':URI',$item["MURI"]);
			$sql->bindParam(':Email',$item["MEmail"]);
			$sql->bindParam(':Creation_Time',$item["Creation_Time"]);
			$sql->bindParam(':Expiration_time',$item["Expiration_Time"]);
			$sql->bindParam(':Indirect_Backward_Ref',$item["Indirect_Backward_Ref"]);
			$sql->bindParam(':Backward_Ref',$item["Backward_Ref"]);
			$sql->bindParam(':Original_Record',$item["Original_Record"]);
			$sql->bindParam(':Backward_Root_Ref',$item["Backward_Root_Ref"]);
			$sql->bindParam(':Digital_Signature',$item["Digital_Signature"]);
			
			// use exec() because no results are returned
			$sql->execute();
			
			}
			catch(PDOException $e)
			{
				//testing purpose
				echo "Error: " . $e->getMessage()."<br>";
			}
			$conn = null;

			//store data
			if($item["DURI"]!=null)//checking if data exist for spesific record
			{
				try 
				{
				$conn = new PDO("mysql:host=$rServer_Name;dbname=$rDatabase_Name", $rUserName, $rPassword);
				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//sanitizing values
				//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
				$sql =$conn->prepare( "INSERT INTO data (URI, Category, Type, Value)
				VALUES (:URI,:Category,:Type,:Value)");
				$sql->bindParam(':URI',$item["MURI"]);
				$sql->bindParam(':Category',$item["Category"]);
				$sql->bindParam(':Type',$item["Type"]);
				$sql->bindParam(':Value',$item["Value"]);
				
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
		}
		else
		{
		echo "Duplicate Email: ".$item["Email"]." found "."<br>";
		}
	}
	return $counter;
}

function DuplicateRecords($server_Name,$username,$password,$database_Name,$email,$mySelf)
{
	try 
	{
		$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//sanitizing values
		//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
		$sql =$conn->prepare( "SELECT * FROM Records
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

function CreateLink($server_Name,$username,$password,$database_Name,$transaction_data,$company)
{
	foreach($transaction_data as $item)
	{
		$fref="Company ".$company;
		// creating object of SimpleXMLElement
		$xml= new SimpleXMLElement('<?xml version="1.0"?><Root></Root>');
				
		// function call to convert array to xml
		array_to_xml($item,$xml);
		
		//converting xml to string
		$xml_string=htmlentities( $xml->asXML());
		
		try 
			{
			$conn = new PDO("mysql:host=$server_Name;dbname=$database_Name", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "INSERT INTO forward_reference (URI,Forward_Ref_Name,Data_Transferd)
			VALUES (:URI,:FRef,:data_trans)");
			$sql->bindParam(':URI',$item["MURI"]);
			$sql->bindParam(':FRef',$fref);
			$sql->bindParam(':data_trans',$xml_string);
			
			// use exec() because no results are returned
			$sql->execute();
			
			}
			catch(PDOException $e)
			{
			//testing purpose
			echo "Error: " . $e->getMessage()."<br>";
			}
			$conn = null;
	}
	return true;
}
?>