<?php
	require 'SteamConfig.php';
	$url = file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=".$steamauth['apikey']."&steamid=".$_SESSION['steamid']."&format=json&include_appinfo='true'"); 
	$content = json_decode($url, true);

	$usergames['game_count'] = $content['response']['game_count'];
	$usergames['games'] = $content['response']['games'];

	require '/home/felixmoses/mosegames.com/BoardGam/steamauth/simplehtmldom/simple_html_dom.php';

	usort( $usergames['games'], function($a, $b ) {
		if ($a["playtime_forever"] == $b["playtime_forever"]) {
	        return 0;
	    }
	    return ($a["playtime_forever"] > $b["playtime_forever"]) ? -1 : 1;
	} );

	$tags = json_decode(file_get_contents("../tags.json"), true);

	$i = 0;
	foreach ($usergames['games'] as &$game) {
		$usergames['total_time'] += $game["playtime_forever"];

		$usergames['loaded']++;

		if ( !isset( $tags[$game["appid"]]  ) ) {
			if ($i == 15) {
		    	continue;
		    }

			$html = file_get_html("http://store.steampowered.com/app/".$game["appid"]);

			$tags[$game["appid"]] = [];
			foreach ($html->find('a.app_tag') as $tag) {
				$tg = str_replace("  ","",str_replace("\t","",$tag -> innertext()));
				array_push($tags[$game["appid"]], $tg);
				$usergames['tags'][ $tg ]++;
			}

			$i++;
		} else {
			foreach ($tags[$game["appid"]] as $tag) {
				$usergames['tags'][ str_replace("\t","",$tag) ]++;
			}
		}
	}

	arsort( $usergames['tags'] );
	
	if ( isset($_GET['BOARD_GAMES']) || count($_GET) == 0 ) {
		file_put_contents( "../tags.json", json_encode($tags) );

		$i = 0;
		foreach ($usergames['tags'] as $tag => $num) {
			$str = str_replace(" ","_",$tag);
			$html = file_get_html("https://boardgamegeek.com/tag/".$str);

			foreach ($html->find('div.tagouter') as $el) {
				if (strpos($el->innertext(), 'Board Game:') !== false) {
					$usergames['recs'][ $el->find('a')[0]->text() ] += $num;
				}
			}

			$i++;
			if ($i == 10) {
		    	break;
		    }
		}

		arsort( $usergames['recs'] );
	}
?>