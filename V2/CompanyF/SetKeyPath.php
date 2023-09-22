<?php

function checkFileExtension($filename)
{
	$validPrimaryKeyExtension=".pem";
	$validPrimaryKeyExtensionLenght=-4;
	
	//get last 4 characters of filename eg ".pem"
	$extension = substr($filename, $validPrimaryKeyExtensionLenght);
	if($extension==$validPrimaryKeyExtension)
		return true;
	return false;
}

function SetKeyPath($filename)
{
	$certificatesStorageDirectory="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyF\Digital_Certificates\\";
	$validPrimaryKeyExtensionLenght=-4;
	$privateWordLenght=-8;
	$startingPoint=0;
	
	/*returns the company's name (substract the file extension, - and Private indicator from string
	This is working only for filename with the format CompanyA-Private.pem*/
	$certificateFolder = substr($filename,$startingPoint,$privateWordLenght+$validPrimaryKeyExtensionLenght);
	/*setting real path my concatenating folder name to resource path
	This works only for folders that are assign Company's name like "CompanyA" folder*/
	$certificatesStorageDirectory.=$certificateFolder;
	$certificatesStorageDirectory.="\\";
	$certificatesStorageDirectory.=$filename;
	return $certificatesStorageDirectory;
}

?>