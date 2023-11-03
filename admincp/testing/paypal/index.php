<?php 
require 'SourceQuery/bootstrap.php';

use xPaw\SourceQuery\SourceQuery;

$server = new SourceQuery();
try
{
    $server->Connect( '185.107.96.231', 27015, 3, SourceQuery::GOLDSOURCE );
    
    $Info    = $server->GetInfo( );
    $Players = $server->GetPlayers( );
    $Rules   = $server->GetRules( );
}
catch( Exception $e )
{
    $Exception = $e;
}
finally
{
    $server->Disconnect( );
}
var_dump($Info);
echo "<hr>";
var_dump($Players);
echo "<hr>";
var_dump($Rules);
echo "<hr>";