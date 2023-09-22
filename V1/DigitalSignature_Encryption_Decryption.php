<?php
/*VARIABLES FOR TESTING
$data="
<Original_Record>
<Email>kyriakoskosta@gmail.com</Email>
<Client_FName>NULL</Client_FName>
<Client_SName>NULL</Client_SName>
<Client_Address>NULL</Client_Address>
<Client_City>NULL</Client_City>
<Client_Country>NULL</Client_Country>
<Client_ZIP>NULL</Client_ZIP>
<URI>dasdsa|kyriakoskosta@gmail.com|606b1f6e0e032b39b67f</URI>
<genesis_time>2015-12-27 23:54:18</genesis_time>
<creation_time>2015-12-27 23:54:18</creation_time>
<expiration_time>2018-12-27 23:54:18</expiration_time>
<backword_ref>dasdsa</backword_ref>
<Backword_root_ref>dasdsa</Backword_root_ref>
<Original_Record_Location>C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\Original Records\kyriakoskosta@gmail.com.xml</Original_Record_Location>
</Original_Record>";
$private_key_path="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\Digital_Certificates\CompanyA\CompanyA-Private.pem";
$public_key_path="C:\Users\Kyriakos\Documents\WAMP SERVER\wamp\www\php\Project\Digital_Certificates\CompanyA\CompanyA-Public.pem";
$KeyPassphrase = "123456789";
echo getHash($data)."<br>";
$msg=EncryptWithPrivateKey(getHash($data),$private_key_path,$KeyPassphrase);
echo $msg."<br>";
$smsg=DecryptWithPublicKey($msg, $decryptedWithPublicFromPrivate, $public_key_path,$KeyPassphrase);
echo $smsg;*/

function getHash($data)
{
//return the hash value of data (32 characters long)
return md5($data);
}

function EncryptWithPrivateKey($data,$private_key_path,$KeyPassphrase)
{
	if(dirExist($private_key_path))
	{
		//get the hash of data
		$hashedData=getHash($data);
		// Load private key
		$fp=fopen($private_key_path,"r"); 
		$priv_key=fread($fp,8192); 
		fclose($fp); 
		$privateKey = openssl_pkey_get_private(array($priv_key, $KeyPassphrase));
		
		//Check if private key exist
		if (!$privateKey) 
		{
			die("Private key NOT OK<br>");
		}
		
		//Encrypt hashed data with private key
		if (!openssl_private_encrypt($hashedData, $encryptedWithPrivate, $privateKey)) {
			die("Error encrypting with private key<br>");
		}
		//return base64_encode($encryptedWithPrivate);
		return $encryptedWithPrivate;
	}
	die("FILE NOT FOUND");
}

function EncryptWithPublicKey($data,$public_key_path,$KeyPassphrase)
{
	if(dirExist($public_key_path))
	{
		//get the hash of data
		$hashedData=getHash($data);
		// Load public key
		$fp=fopen($public_key_path,"r"); 
		$pub_key=fread($fp,8192); 
		fclose($fp);
		$publicKey = openssl_pkey_get_public(array($pub_key, $KeyPassphrase));

		//Check if public key exist
		if (!$publicKey) 
		{
			die("Public key NOT OK<br>");
		}

		//Encrypt hashed data with public key
		if (!openssl_public_encrypt($hashedData, $encryptedWithPublic, $publicKey)) {
			die("Error encrypting with public key<br>");
		}

		//return base64_encode($encryptedWithPublic);
		return $encryptedWithPublic;
	}
	die("FILE NOT FOUND");
}

function DecryptWithPrivateKey($encryptedWithPublic, $decryptedWithPrivateFromPublic, $private_key_path,$KeyPassphrase)
{
	if(dirExist($private_key_path))
	{
		// Load private key
		$fp=fopen($private_key_path,"r"); 
		$priv_key=fread($fp,8192); 
		fclose($fp); 
		$privateKey = openssl_pkey_get_private(array($priv_key, $KeyPassphrase));

		//Check if private key exist
		if (!$privateKey) 
		{
			die("Private key NOT OK<br>");
		}

		//Decrypted with private key what was encrypted with public
		if (!openssl_private_decrypt($encryptedWithPublic, $decryptedWithPrivateFromPublic, $privateKey)) {
			die("Error decrypting with private key what was encrypted with public key<br>");
		}

		return $decryptedWithPrivateFromPublic;
	}
	die("FILE NOT FOUND");
}

function DecryptWithPublicKey($encryptedWithPrivate, $decryptedWithPublicFromPrivate, $public_key_path,$KeyPassphrase)
{
	if(dirExist($public_key_path))
	{
		// Load public key
		$fp=fopen($public_key_path,"r"); 
		$pub_key=fread($fp,8192); 
		fclose($fp);
		$publicKey = openssl_pkey_get_public(array($pub_key, $KeyPassphrase));

		//Check if public key exist
		if (!$publicKey) 
		{
			die("Public key NOT OK<br>");
		}

		//Decrypt with public key what was encrypted with private
		if (!openssl_public_decrypt($encryptedWithPrivate, $decryptedWithPublicFromPrivate, $publicKey)) {
			die("Error decrypting with public key what was encrypted with private key<br>");
		}

		return $decryptedWithPublicFromPrivate;
	}
	die("FILE NOT FOUND");
}

function dirExist($path)
{
	if(file_exists($path))
		return true;
	return false;
}
?>