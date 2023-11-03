<?php
session_start();
if(!isset($_SESSION['ip']))
{
    exit();
}
require __DIR__ . '/SourceQuery/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;
define( 'SQ_TIMEOUT',     1 );
define( 'SQ_ENGINE',      SourceQuery::GOLDSOURCE );
$Query = new SourceQuery( );


try
{
    $Query->Connect( $_SESSION['ip'], $_SESSION['port'], SQ_TIMEOUT, SQ_ENGINE );
    $Query->SetRconPassword( $_SESSION['rcon'] );
    echo htmlspecialchars( $Query->Rcon( $_POST['command'] ) ) ;

}
catch( Exception $e )
{
    echo "Could not connect to server. Check IP and Port";
    //echo $e->getMessage( );
    exit();
}
finally
{
    $Query->Disconnect( );
}