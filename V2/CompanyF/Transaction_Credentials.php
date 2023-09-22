<?php

function transaction_credentials($self,$company)
{
	$credentials=array();
	
	if($self=='A')
	{
		$credentials["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyA\Digital_Certificates\CompanyA\CompanyA-Private.pem";
		$credentials["KeyPassphrase"]="123456789";
	}
	else if($self=='B')
	{
		$credentials["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyB\Digital_Certificates\CompanyB\CompanyB-Private.pem";
		$credentials["KeyPassphrase"]="123456789";
	}
	else if($self=='C')
	{
		$credentials["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyC\Digital_Certificates\CompanyC\CompanyC-Private.pem";
		$credentials["KeyPassphrase"]="123456789";
	}
	else if($self=='D')
	{
		$credentials["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyD\Digital_Certificates\CompanyD\CompanyD-Private.pem";
		$credentials["KeyPassphrase"]="123456789";
	}
	else if($self=='E')
	{
		$credentials["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyE\Digital_Certificates\CompanyE\CompanyE-Private.pem";
		$credentials["KeyPassphrase"]="123456789";
	}
	else if($self=='F')
	{
		$credentials["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyF\Digital_Certificates\CompanyF\CompanyF-Private.pem";
		$credentials["KeyPassphrase"]="123456789";
	}
	
	if($company=='A')
	{
		$credentials["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyA\Digital_Certificates\CompanyA\CompanyA-Public.pem";
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_a";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='B')
	{
		$credentials["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyB\Digital_Certificates\CompanyB\CompanyB-Public.pem";
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_b";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='C')
	{
		$credentials["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyC\Digital_Certificates\CompanyC\CompanyC-Public.pem";
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_c";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='D')
	{
		$credentials["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyD\Digital_Certificates\CompanyD\CompanyD-Public.pem";
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_d";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='E')
	{
		$credentials["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyE\Digital_Certificates\CompanyE\CompanyE-Public.pem";
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_e";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	else if($company=='F')
	{
		$credentials["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyF\Digital_Certificates\CompanyF\CompanyF-Public.pem";
		$credentials["server_Name"]="localhost";
		$credentials["database_Name"]="company_f";
		$credentials["username"]="Kyriakos";
		$credentials["password"]="12345";
	}
	
	return $credentials;
}
?>