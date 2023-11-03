<?php 
    $isGet = true;
    if( !isset($_GET['code']) || empty($_GET['code']) )
    {
        echo "<div>Invalid Request</div>";
        die();
    }
    
    $code = $_GET['code'];
    if( isset($_POST['submit'] ) )
        $isGet = false;

    if( $isGet==false && (!isset($_POST['password']) || empty($_POST['password'])))
    {
        echo "<div>Invalid Password</div>";
        die();
    }
    $conn = mysqli_connect( "127.0.0.1", "dust", "", "amx_knf" ) or die( "Database error. Contact https://steamcommunity.com/id/SwDusT/" );
    $result = $conn->query("SELECT `id`,`username`,`reset_expiration` FROM `amx_admins` WHERE reset_code='".$conn->real_escape_string($code)."';" );
    if( $result->num_rows === 1 )
    {
        $r = $result->fetch_array();
        $name = $r['username'];
        $reset = $r['reset_expiration'];
        if( $reset < time() )
        {
            echo "<div>Request Expired</div>";
            die();
        }
    }
    else
    {
        echo "<div>Invalid link or request expired.</div>";
        die();
    }
    
    if( $isGet==true )
    {
        echo "<div>Username: " . htmlspecialchars( $name ) . ".<br>";
        echo "<form name=\"f\" method=\"POST\">";
        echo "<input type=password name=password id=password>";
        echo "<input type=submit value=\"Submit\" name='submit' value='submit'>";     
        echo "</form>";
        die();
    }

    $conn->query("UPDATE `amx_admins` SET `reset_expiration`=0, `password`='".$conn->real_escape_string($_POST['password'])."' WHERE `reset_code`='".$conn->real_escape_string($code)."';");
    echo "<div>Password for nick ". htmlspecialchars($name) . " successfully changed! Wait for map restart to be able to use the new password.";
    die();
?>