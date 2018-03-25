<?php
	require "steamauth/steamauth.php";

	if ( isset($_SESSION["steamid"]) ) {
		require "steamauth/userInfo.php";
		require "steamauth/userGames.php";

		$id = $_SESSION["steamid"];
		$logged = True;
	} else {
		$logged = False;
	}
?>

<head>
	<link rel="icon" href="logo.png">
	<title>BoardGam</title>

	<style>
		body {
			background-color: #1b2838;
			margin: 0px;
			font-family: "Motiva Sans", Sans-serif;
			color: #c7d5e0;
		}
		.head {
			background-color: #171a21;
			padding: 7px;
			padding-bottom: 7px;
			box-shadow: 0px 0px 4px 4px black;
		}
		a {
			padding: 8px;
			color: #b8b6b4;
			text-decoration: none;
		}
		a:hover {
		    color: #ebebeb;
		    cursor: pointer;
		}
		th , td {
			border-collapse: collapse;
			padding: 6px;
			text-align: center;
		}
		th {
			padding-top: 25px;
		}
		h3 {
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="head">
		<? if ($logged) { ?>
			<a href='?logout'>LOGOUT</a>
		<? } else { ?>
			<a href='?login'>LOGIN</a>
		<? } ?>
		<a href='?BOARD_GAMES'>BOARD GAMES</a>
		<a href='?MY_TAGS'>MY TAGS</a>
		<a href='?TIME_PLAYED'>TIME PLAYED</a>
		<a href='?INFO'>INFO</a>
	</div>
	<center>
		<table>
			<?
				if (!$logged) {
					echo "<tr><th>SECURITY</th><tr>";
					echo "<tr><td>When you login with steam we can only see your public info and cant edit any of your data.</td></tr>";
					return;
				} else if ( isset($_GET['BOARD_GAMES']) || count($_GET) == 0 ) {
					echo "<tr><th>RECOMMENDED BOARD GAMES</th></tr>";
					foreach ($usergames['recs'] as $tag => $amu) {
						echo "<tr><td>".$tag."</td></tr>";
					}
				} else if ( isset($_GET['MY_TAGS']) ) {
					echo "<tr><th>TAG NAME</th><th># OF GAME'S</th></tr>";
					foreach ($usergames['tags'] as $tag => $amu) {
						echo "<tr><td>".$tag."</td><td>".$amu."</td></tr>";
					}
				} else if ( isset($_GET['INFO']) ) { ?>
					<tr><th>GAMES</th><tr>
					<tr><td>If you do not see all you games, try reloading the page a couple of times. It only loads in ten new games per load for efficiency reasons.</td></tr>
					<tr><td><? echo ($usergames['loaded'] - 1); ?> / <? echo (count($usergames['games']) - 1); ?> games loaded</td></tr>
					<tr><th>SECURITY</th><tr>
					<tr><td>When you login with steam we can only see your public info and cant edit any of your data.</td></tr>
					<tr><th>ABOUT SITE</th><tr>
					<tr><td>We created a website that recommends boards games based off of games you play via steam. You log into your steam account using the steam API. The website then looks through your top played games and recommends board games based off of the tags they have.</td></tr>
					<tr><th>ABOUT US</th></tr>
					<tr><td>Our names are Max Fritsch and Felix Moses and we have a background in HTML, CSS, Java Script, PHP, Lua, Python, Java, C#, C++, Ruby, and Swift.</td></tr>
					<tr><th>ABOUT YOU</th></tr>
					<?
					foreach ( $steamprofile as $key => $value ) {
						if ( gettype($value) == "string" || gettype($value) == "number" ) {
							echo "<tr><td>".$key.": ".$value."</td></tr>";
						}
					}
				} else if ( isset($_GET['TIME_PLAYED']) ) {
					echo "<tr><th>GAME</th><th>MINUTES</th><th>HOURS</th><th>ID</th></tr>";
					echo "<tr><td>total</td><td>".$usergames["total_time"]."</td><td>".(floor($usergames["total_time"]/6)/10)."</td><td>-----<td></tr>";
					foreach ($usergames['games'] as $game => $info) {
						if ( isset($info["playtime_forever"]) ) {
							echo "<tr><td>".$info["name"]."</td><td>".$info["playtime_forever"]."</td><td>".(floor($info["playtime_forever"]/6)/10)."</td><td>".$info["appid"]."<td></tr>";
						}
					}
				}
			?>
		</table>
	</center>
</body>