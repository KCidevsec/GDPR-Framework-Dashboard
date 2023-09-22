<?php
 function veri_credentials($mySelf,$company)
{
	$veri=array();
	if($mySelf=='A')
	{
		$veri["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyA\Digital_Certificates\CompanyA\CompanyA-Public.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($mySelf=='B')
	{
		$veri["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyB\Digital_Certificates\CompanyB\CompanyB-Public.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($mySelf=='C')
	{
		$veri["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyC\Digital_Certificates\CompanyC\CompanyC-Public.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($mySelf=='D')
	{
		$veri["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyD\Digital_Certificates\CompanyD\CompanyD-Public.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($mySelf=='E')
	{
		$veri["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyE\Digital_Certificates\CompanyE\CompanyE-Public.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($mySelf=='F')
	{
		$veri["public_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyF\Digital_Certificates\CompanyF\CompanyF-Public.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	
	if($company=='A')
	{
		$veri["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyA\Digital_Certificates\CompanyA\CompanyA-Private.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($company=='B')
	{
		$veri["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyB\Digital_Certificates\CompanyB\CompanyB-Private.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($company=='C')
	{
		$veri["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyC\Digital_Certificates\CompanyC\CompanyC-Private.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($company=='D')
	{
		$veri["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyD\Digital_Certificates\CompanyD\CompanyD-Private.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($company=='E')
	{
		$veri["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyE\Digital_Certificates\CompanyE\CompanyE-Private.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	else if($company=='F')
	{
		$veri["private_key_path"]="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\V2\CompanyF\Digital_Certificates\CompanyF\CompanyF-Private.pem";
		$veri["KeyPassphrase"]="123456789";
	}
	
	return $veri;
}

?>