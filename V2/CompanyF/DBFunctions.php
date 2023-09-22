<?php
//returns a connection with the database
function ConnectToDatabase($server_Name,$username,$password,$database_Name){
	//Variables Required for database connection
	
	/*Credentials to be used in testing
	$server_Name = "localhost";
	$database_Name = "company_a";
	$username = "Kyriakos";
	$password = "12345";*/
	
	//establish connection with server
	global $DB_connection;
	$DB_connection = mysqli_connect($server_Name,$username,$password);
	
	//if no connection
	if(!$DB_connection)
	{
		die('Connection Error: ' . mysqli_connect_error());//display error occured and kill proccess
	}
	
	//establishing connection with database
	mysqli_select_db($DB_connection,$database_Name);
	echo "Server Connection Established<br>";
	
	//returning the current connection with server and database
	return $DB_connection;
}
//closing the database
function CloseDatabase(){
	global $DB_connection;
	echo "Connection Terminated<br>";
	mysqli_close($DB_connection);
}

//check if a table already exist
function IsTableExist($Table_name)
{
	global $DB_connection;
	//Sql commands to get all table names
	$query = "SHOW TABLES LIKE '".$Table_name."'";
	//submit query
	$result = mysqli_query($DB_connection,$query);
	
	//create an array with tables
	while($row = mysqli_fetch_row($result))
	{
		//array_values() will ignore numeric indexes, it will renumber them according to the 'foreach' ordering(works like a stack.push
		$arr[] = $row[0];
	}
	//print_r(array_values($arr)); //to be used in testing 
	
	//release memory and return boolean variable
	if($arr!= null)
	{
		if(in_array($Table_name,$arr)) 
		{
		mysqli_free_result($result);
		return true;
		}
	}
	else 
	{
	mysqli_free_result($result);
	return false;
	}
}

?>