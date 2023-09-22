<?php

function RetrieveData($server_Name,$username,$password,$database_Name,$email)
{
	$record=GetRecordData($server_Name,$username,$password,$database_Name,$email);
	$metadata=GetMetaData($server_Name,$username,$password,$database_Name,$email);
	$data=GetData($server_Name,$username,$password,$database_Name,$metadata["URI"]);
	$MergedData=MergeData($record,$metadata,$data);
	return $MergedData;
}
function MergeData($record,$metadata,$data)
{
	$MergedData=$record;
	$MergedData["MURI"]=$metadata["URI"];
	$MergedData["MEmail"]=$metadata["Email"];
	$MergedData["Genesis_Time"]=$metadata["Genesis_Time"];
	$MergedData["Creation_Time"]=$metadata["Creation_Time"];
	$MergedData["Expiration_Time"]=$metadata["Expiration_Time"];
	$MergedData["Backward_Ref"]=$metadata["Backward_Ref"];
	$MergedData["Original_Record"]=$metadata["Original_Record"];
	$MergedData["Indirect_Backward_Ref"]=$metadata["Indirect_Backward_Ref"];
	$MergedData["Backward_Root_Ref"]=$metadata["Backward_Root_Ref"];
	$MergedData["Digital_Signature"]=$metadata["Digital_Signature"];
	$MergedData["DURI"]=$data["URI"];
	$MergedData["Category"]=$data["Category"];
	$MergedData["Type"]=$data["Type"];
	$MergedData["Value"]=$data["Value"];
	return $MergedData;
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
function GetMetaData($server_Name,$username,$password,$database_Name,$email)
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
function isEmail($email)
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
function GetCredentials($company)
{
	if($company=='A')
	{
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_a";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='B')
	{
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_b";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='C')
	{
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_c";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='D')
	{
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_d";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='E')
	{
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_e";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='F')
	{
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_f";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	
	return $credentials;
}

function GetTrail($email,$company)
{
	$trail=array();
	$root=false;
	
	while(!$root)
	{
		$credentials=GetCredentials($company);
		$Cserver_Name=$credentials["server_Name"];
		$Cusername=$credentials["username"];
		$Cpassword=$credentials["password"];
		$Cdatabase_Name=$credentials["database_Name"];
		
		try 
		{
			$conn = new PDO("mysql:host=$Cserver_Name;dbname=$Cdatabase_Name", $Cusername, $Cpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//sanitizing values
			//if the optional field in array records is null i am allowed to pass them as null reference into sql querry
			$sql =$conn->prepare( "SELECT * FROM metadata
									WHERE Email LIKE :Email");
			$sql->bindParam(':Email',$email);
			$sql->execute();
			
			$querry_result=$sql->fetch(PDO::FETCH_ASSOC);
			if($company==substr($querry_result["Backward_Root_Ref"],-1))
			{
				$root=true;
			}
			$trail[] = $company;
			$company=substr($querry_result["Backward_Ref"],-1);
		}	
		catch(PDOException $e) 
		{
			echo "Error: " . $e->getMessage();
		}

		$conn = null;
	}
	return $trail;
}
?>