<?php
session_start();
if(!isset($_SESSION['ip']))
{
    header("Location: index.php");
    exit();
}

$value = isset($_GET['cmd'])? $_GET['cmd']:'';

require __DIR__ . '/SourceQuery/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;

//Header( 'Content-Type: text/plain' );
//Header( 'X-Content-Type-Options: nosniff' );

define( 'SQ_TIMEOUT',     1 );
define( 'SQ_ENGINE',      SourceQuery::GOLDSOURCE );
$Query = new SourceQuery( );

function rcon_command($command,$test=false) {
    global $Query;
    try
    {
        $Query->Connect( $_SESSION['ip'], $_SESSION['port'], SQ_TIMEOUT, SQ_ENGINE );
        $Query->SetRconPassword( $_SESSION['rcon'] );
        $string = htmlspecialchars( $Query->Rcon( $command ) ) ;
        //echo $string;
        if($test==false)
            echo $string;
        else
        {
            if( str_contains( $string, "testing" ) )
            {
                echo "<div id='to-hide'>Rcon: OK</div>";
            }
            else 
            {
                echo "Bad Rcon Password.";
                exit();
            }
        }
        //var_dump( $Query->Rcon( $command ) );

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
}

function GetName()
{
    global $Query;
    if(isset($_SESSION['hostname']) && !empty($_SESSION['hostname']))
        return $_SESSION['hostname'];

    try{
        $Query->Connect( $_SESSION['ip'], $_SESSION['port'], SQ_TIMEOUT, SQ_ENGINE );
        if(isset($_SESSION['hostname']) && empty($_SESSION['hostname'])==1)
        {
            //echo "here";
            $Query->SetRconPassword( $_SESSION['rcon'] );
            $string = $Query->Rcon( "hostname" );
            $_SESSION['hostname']=htmlspecialchars(substr($string, 15, -1));
            return $_SESSION['hostname'];
        }
        $info = $Query->GetInfo();
        //var_dump($info);
        if(isset($info['HostName']) && !empty($info['HostName']))
        {
            $_SESSION['hostname'] = $info['HostName'];
            return $_SESSION['hostname'];
        }
        
    }
    catch(Exception $e)
    {
        $_SESSION['hostname']='';
        return GetName();
        //return $e->getMessage( );
    }
    finally{
        $Query->Disconnect();
    }

}
?>
<header>
    <title>Rcon Access</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <?php include('header.php'); ?>
</header>
<label class="info-label">Server:</label> <div class="info-div"><?php echo $_SESSION['ip'].":".$_SESSION['port'];?></div>
<div>
<label class="info-label">Server Name:</label> <div class="info-div"><?php echo GetName();?></div>
</div>
<br>
<div class="wrapper">
<a href='logout.php' class="button btn-link">RESET SERVER INFO</a>
<div>
<br><br>

<?php 

if($_SESSION['is_first']==1)
{
    $_SESSION['is_first'] = 0;
    rcon_command('echo testing', true );
}


?>
<br>
<input type=text name=cmd placeholder="RCON Command" class="textbox" id="textbox"/>
<span class="button" id="btn-submit">OK</span>
<span class="button" id="btn-clear">Clear</span>
<div>
<pre class="display-console" id="preid">
</pre>
</div>

<script>
var pre = document.getElementById('preid');
var textbox = document.getElementById('textbox')
var hide = document.getElementById('to-hide');
var btn = document.getElementById('btn-submit')
btn.onclick = function(){
    //alert(document.getElementById('textbox').value);
    if( hide !== null )
        hide.style.display = 'none';
    $.ajax({
      type: "POST",
      url: "/rcon/rcon_use.php",
      data: "&command="+textbox.value,
      success:function(html)
      {
        pre.innerHTML += "<br>> "+textbox.value+"<br>";
        pre.innerHTML += html+"<br>";
        //pre.animate({scrollTop: pre.scrollHeight}, 500);
        pre.scrollTop = pre.scrollHeight;
      }
    });
}
document.getElementById('btn-clear').onclick = function(){
    pre.innerHTML = "";
}

// Execute a function when the user releases a key on the keyboard
textbox.addEventListener('keyup', function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    btn.click();
    textbox.blur();
  }
});

</script>