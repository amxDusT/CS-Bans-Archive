<?php
	$dir = dirname(realpath(__FILE__));
	require_once $dir . '/config.php';
	define( 'MAX_COOKIE_SIZE', 32 );

	$table_check = "db_ccheck";

	//mySQL
	$mysqli = mysqli_connect(
		DB_HOST,
		DB_USER,
		DB_PASS,
		DB_DB
	);

	// cookie stuff
	$cookie_name = "ban";
	$userindex = ( isset($_GET[ 'uid' ] ) )? $_GET[ 'uid' ]:0;
	$server = ( isset($_GET[ 'srv' ] ) )? $_GET[ 'srv' ]:'0';
	if( !is_numeric( $userindex ) )
		$userindex = 0;
	function GetRandomWord( $len = MAX_COOKIE_SIZE ) {
		$word = range( 'a', 'z' );
		shuffle( $word );
		return substr( implode( $word ), 0, $len );
	}

	if( $userindex != 0 )
	{
		if( !isset( $_COOKIE[ $cookie_name ] ) )
		{
			$cookie = GetRandomWord();
			setcookie( $cookie_name, $cookie, time() + ( 31536000 * 2 ) );
		}
		else
		{
			$cookie = $_COOKIE[$cookie_name];
		}
		$query = "REPLACE INTO ".$table_check." VALUES ( NULL, ".$userindex.", '".$mysqli->real_escape_string($cookie)."', '".$mysqli->real_escape_string($server)."' );";
		$mysqli->query($query);
	}
	
	include('index.html');
?>
