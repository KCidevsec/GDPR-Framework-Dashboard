<?php

function GetRecordToXML($server_Name,$username,$password,$database_Name,$email,$meta_varchar_array,$meta_timestamps_array)
{
	//Server location to store XML data
	$path= "C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V1\Original Records"."\\".$email.".xml";
	
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
			
			//adding metadata to array
			$querry_result["URI"]=$meta_varchar_array["URI"];
			$querry_result["genesis_time"]=$meta_timestamps_array["creation_time"];//same as creation time since is the first time the record is created
			$querry_result["creation_time"]=$meta_timestamps_array["creation_time"];
			$querry_result["expiration_time"]=$meta_timestamps_array["expiration_time"];
			$querry_result["backword_ref"]=$meta_varchar_array["backword_ref"];
			$querry_result["Backword_root_ref"]=$meta_varchar_array["Backword_root_ref"];
			$querry_result["Original_Record_Location"]=$path;
			
			// creating object of SimpleXMLElement
			$xml= new SimpleXMLElement('<?xml version="1.0"?><Original_Record></Original_Record>');
			
			// function call to convert array to xml
			array_to_xml($querry_result,$xml);
			
			//creating xml file in directory
			$result = $xml->asXML($path);
			
			//converting xml to string
			$xml_string=htmlentities( $xml->asXML());
			
			return $xml_string;
		}	
	catch(PDOException $e) 
		{
			echo "Error: " . $e->getMessage();
		}

		$conn = null;

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
?>