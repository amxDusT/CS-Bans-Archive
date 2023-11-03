<?php 

    if( !isset( $_GET['s'] ) )
    {
        header('Location: https://www.csamx.net');
        exit();
    }
    else
    {
        $s = steam_convert( $_GET['s'] );
        if( $s !== false )
        {
            header('Location: ' . $s );
            exit();
        }
        else
        {
            echo "Not a valid steam SteamID. Redirecting in 3 seconds...";
            echo '<meta http-equiv="refresh" content="1;url=https://www.csamx.net">';
            //header("refresh:1;Location:https://www.csamx.net");
            exit();
        }
    }
	function steam_convert($id, $url = true) {

		$RightSteam = "/^(STEAM_0)\:([0-1])\:([0-9]{4,11})$/";

		if (!$id) { return false; }

		if(preg_match($RightSteam, $id, $match)) {

			$newst1 = $match[2];
			$newst2 = $match[3];
			$const1 = 7656119;
			$const2 = 7960265728;
			$answer = $newst1 + $newst2 * 2 + $const2;

			if($url) {
				return 'http://steamcommunity.com/profiles/'.$const1 . $answer;
			}
			return $const1 . $answer;
		}
		return false;
	}
?>