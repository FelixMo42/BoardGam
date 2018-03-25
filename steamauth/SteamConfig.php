<?php
	//Version 3.2
	$steamauth['apikey'] = "97952AC71F669B89C290C3E393077995";
	$steamauth['domainname'] = "http://www.mosegames.com/";
	$steamauth['logoutpage'] = "http://www.mosegames.com/BoardGam/index.php";
	$steamauth['loginpage'] = "http://www.mosegames.com/BoardGam/index.php";

	// System stuff
	if (empty($steamauth['apikey'])) {die("<div style='display: block; width: 100%; background-color: red; text-align: center;'>SteamAuth:<br>Please supply an API-Key!<br>Find this in steamauth/SteamConfig.php, Find the '<b>\$steamauth['apikey']</b>' Array. </div>");}
	if (empty($steamauth['domainname'])) {$steamauth['domainname'] = $_SERVER['SERVER_NAME'];}
	if (empty($steamauth['logoutpage'])) {$steamauth['logoutpage'] = $_SERVER['PHP_SELF'];}
	if (empty($steamauth['loginpage'])) {$steamauth['loginpage'] = $_SERVER['PHP_SELF'];}
?>