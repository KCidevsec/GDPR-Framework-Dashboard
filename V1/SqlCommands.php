<?php
/*Contains Sql queries to create the database tables,
those queries can be used by a constructor*/

/*-----------------PHP DOCUMENTATION------------------------------
WARING: THE VALUES OF THESE DIRECTIVES WILL BE EXPOSED IF ANY OF THE CODE INCLUDES THE phpinfo() FUNCTION.
If security is a concern, locate the include file outside of your web root folder.
-----------------PHP DOCUMENTATION--------------------------------*/

include 'DBFunctions.php';

//Create table Records
function CreateTableRecord($server_Name,$username,$password,$database_Name)
{
	$query =	"CREATE TABLE Records(
				Email varchar(100) NOT NULL PRIMARY KEY,
				Client_FName varchar(50),
				Client_SName varchar(50),
				Client_Address varchar(100),
				Client_City varchar(50),
				Client_Country varchar(50),
				Client_ZIP varchar(10)
				) ";
	$connection = ConnectToDatabase($server_Name,$username,$password,$database_Name);
	//if connection has establish
		if($connection)
		{
			if(IsTableExist("records"))
			{
				echo "Table Records already exist<br>";
				CloseDatabase();
			}
			else
			{
				//submit Sql command
				$querry_result= mysqli_query($connection,$query);
				
				if($querry_result)
				{
					echo "Table Records Created<br>";
				}
				else
				{
					$reply=array(
							"status"=>"error",
							"message"=> mysqli_error($connection)); //error occured
					die(print_r($reply));//display error occured and kill proccess
				}
				//closing Server Connection
				CloseDatabase();
			}
		}
		else//if no connection with server
		{
			die("Server Connection Failed<br>");
		}
}

function CreateTableMetaData($server_Name,$username,$password,$database_Name)
{
//TIMESTAMP DEFAULT NOW() n this case the column created retains its initial value and is not changed during subsequent updates.
//DATETIME e.g. format 2012-06-08 12:13:14
//LONGBLOB Holds up to 4,294,967,295 bytes of data
//xml Stores XML formatted data. Maximum 2GB

	$query =	"CREATE TABLE MetaData(
				URI varchar(150) NOT NULL,
				Email varchar(100) NOT NULL ,
				Genesis_Time TIMESTAMP DEFAULT NOW() NOT NULL,
				Creation_Time DATETIME NOT NULL,
				Expiration_Time DATETIME NOT NULL ,
				Backward_Ref varchar(100) NOT NULL,
				Original_Record LONGTEXT NOT NULL,
				Backward_Root_Ref varchar(100) NOT NULL,
				Digital_Signature varchar(150) NOT NULL,
    			PRIMARY KEY (URI),
    			FOREIGN KEY (Email) REFERENCES records(Email)
				) ";
	$connection = ConnectToDatabase($server_Name,$username,$password,$database_Name);
	//if connection has establish
		if($connection)
		{
			if(IsTableExist("metadata"))
			{
			echo"Table MetaData already exist<br>";
			CloseDatabase();
			}
			else
			{
				//submit Sql command
				$querry_result= mysqli_query($connection,$query);
				
				if($querry_result)
				{
					echo "Table MetaData Created<br>";
				}
				else
				{
					$reply=array(
							"status"=>"error",
							"message"=> mysqli_error($connection)); //error occured
					die(print_r($reply));//display error occured and kill proccess
				}
				//closing Server Connection
				CloseDatabase();
			}
		}
		else//if no connection with server
		{
			die("Server Connection Failed<br>");
		}
}

function CreateTableData($server_Name,$username,$password,$database_Name)
{
	$query =	"CREATE TABLE Data(
				URI varchar(100) NOT NULL ,
				Category varchar(100),
				Type varchar(20),
				Value LONGTEXT,
				FOREIGN KEY (URI) REFERENCES metadata(URI)
				) ";
	$connection = ConnectToDatabase($server_Name,$username,$password,$database_Name);
	//if connection has establish
		if($connection)
		{
			if(IsTableExist("data"))
			{
				echo "Table data already exist<br>";
				CloseDatabase();
			}
			else
			{
				//submit Sql command
				$querry_result= mysqli_query($connection,$query);
				
				if($querry_result)
				{
					echo "Table Data Created<br>";
				}
				else
				{
					$reply=array(
							"status"=>"error",
							"message"=> mysqli_error($connection)); //error occured
					die(print_r($reply));//display error occured and kill proccess
				}
				//closing Server Connection
				CloseDatabase();
			}
		}
		else//if no connection with server
		{
			die("Server Connection Failed<br>");
		}
}

function CreateTableForward_Reference($server_Name,$username,$password,$database_Name)
{
	$query =	"CREATE TABLE Forward_Reference(
				URI varchar(100) NOT NULL ,
				Forward_Ref_Name varchar(100),
				Data_Transferd LONGTEXT,
				FOREIGN KEY (URI) REFERENCES metadata(URI)
				) ";
	$connection = ConnectToDatabase($server_Name,$username,$password,$database_Name);
	//if connection has establish
		if($connection)
		{
			if(IsTableExist("forward_reference"))
			{
				echo "Table Forward_Reference already exist<br>";
				CloseDatabase();
			}
			else
			{
				//submit Sql command
				$querry_result= mysqli_query($connection,$query);
				
				if($querry_result)
				{
					echo "Table Forward_Reference Created<br>";
				}
				else
				{
					$reply=array(
							"status"=>"error",
							"message"=> mysqli_error($connection)); //error occured
					die(print_r($reply));//display error occured and kill proccess
				}
				//closing Server Connection
				CloseDatabase();
			}
		}
		else//if no connection with server
		{
			die("Server Connection Failed<br>");
		}
}
?>