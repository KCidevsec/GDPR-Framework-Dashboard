<?php
	$server_Name = "localhost";
	$database_Name = "data";
	$username = "Kyriakos";
	$password = "12345";
	//---------------------------------------CONSTRACTORS FOR TABLES TESTING-------------------------------
	
	include 'SqlCommands.php';
	
	echo "Trying to Create Table Records<br>"; 
	CreateTableRecord($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table MetaData<br>";
	CreateTableMetaData($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table Data<br>";
	CreateTableData($Server_Name,$username,$password,$database_Name);
	echo "Trying to Create Table Forward_Reference<br>";
	CreateTableForward_Reference($Server_Name,$username,$password,$database_Name);
	//-------------------------------------------------------------------------------------------------
	
/*----------------------------------Records TABLE TESTING FUCNTIONS---------------------------------*/
	include 'RecordsFunctions.php';
	
	$record=array();
	
	$email="KyriakosKosta@gmail.com";
	$fname="Kyriakos";
	$sname="Kosta";
	$address="Kissavou 20A Strovolos";
	$city="Nicosia";
	$country="Cyprus";
	$zip="2052";
	
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
	if(isset($fname))
	{
		if(isString($fname))
		{
			echo "Correct Name Format:".$fname. "<br>";
			$record["fname"]=$fname;	
		}
		else
		{
			echo "Invalid first name format: ".$fname. "<br>";
		}
	}
	else
	{
		echo "First Name set to null<br>";
		$record["fname"]="NULL";
	}
	
	//check surname
	if(isset($sname))
	{
		if(isString($sname))
		{
			echo "Correct Surname Format:".$sname. "<br>";
			$record["sname"]=$sname;	
		}
		else
		{
			echo "Invalid surname format: ".$sname. "<br>";
		}
	}
	else
	{
		echo "Surname set to null<br>";
		$record["sname"]="NULL";
	}
	
	//check street address
	if(isset($address))
	{
		if(isStreetAddress($address))
		{
			echo "Correct address Format:".$address. "<br>";
			$record["address"]=$address;	
		}
		else
		{
			echo "Invalid address format: ".$address. "<br>";
		}
	}
	else
	{
		echo "Address set to null<br>";
		$record["address"]="NULL";
	}
	
	//check city
	if(isset($city))
	{
		if(isString($city))
		{
			echo "Correct City Format:".$city. "<br>";
			$record["city"]=$city;	
		}
		else
		{
			echo "Invalid city format: ".$city. "<br>";
		}
	}
	else
	{
		echo "city set to null<br>";
		$record["city"]="NULL";
	}
	
	//check country
	if(isset($country))
	{
		if(isString($country))
		{
			echo "Correct country Format:".$country. "<br>";
			$record["country"]=$country;	
		}
		else
		{
			echo "Invalid country format: ".$country. "<br>";
		}
	}
	else
	{
		echo "country set to null<br>";
		$record["country"]="NULL";
	}
	
	//check zip
	if(isset($zip))
	{
		if(isZIP($zip))
		{
			echo "Correct zip Format:".$zip. "<br>";
			$record["zip"]=$zip;	
		}
		else
		{
			echo "Invalid zip format: ".$zip. "<br>";
		}
	}
	else
	{
		echo "zip set to null<br>";
		$record["zip"]="NULL";
	}
	
InsertIntoRecords($server_Name,$username,$password,$database_Name,$record)
	
	
?>