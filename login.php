<?php
	require "includes/lightopenid/openid.php";
	$_STEAMAPI = "8D010D23856695131B29281138C42C21";

	try {
		$openid = new LightOpenId('index.html');
		if (!$openid->mode) {
			if ( isset($_GET["login"]) ) {
				$openid->identity = "http://steamcommunity.com/openid/?l=english";
				header('location' . $openid->authUrl());
			} else {
				echo "<form action='?login' method='?post'>"
				echo "	<input type='image' scr='https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_02.png'>"
				echo "</form>";
			}
		} else if ($openid->mode == 'cancel') {
			echo 'user has canceled auth';
		} else {
			if ( $openid->validate() ) {
				$id = $openid->identity;
				$ptn = "/^http:\/\/steamcommunity\.com\/openid\/(7[0-9]{15,25})$/";
				preg_match($ptn, $id, $matches);

				$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&streamids=$matches[1]";
				$json_object = file_get_contents($url);
				$json = json_decode($json_object);

				foreach ( $json->response->players as $player ) {
					echo $player->$steamid;
				}
			} else {
				echo "user is not logged in";
			}
		} catch(ErrorExeption $e) {
			echo $e->getMessage();
		}
	}
?>