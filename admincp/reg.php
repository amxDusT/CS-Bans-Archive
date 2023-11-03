<?php
    define( 'HASH_SALT', 'H@Sh4mX%' );

    function ShowMessage( $message, $noerror = false )
    {
        if($noerror)
            echo "<div class=success>".htmlspecialchars($message)."</div>";
        else
            echo "<div class=error>".htmlspecialchars($message)."</div>";
    }
    function SqlEsc( $string )
    {
        global $conn;
        if( $string == false )
            return NULL;
        
        return $conn->real_escape_string($string);
    }
    $nick = false; $pass = false; $email = false; $steam = false;
    if(isset($_GET['Nick']))
        $nick = $_GET['Nick'];
    if(isset($_GET['Password']))
        $pass = $_GET['Password'];
    if(isset($_GET['Email']))
        $email = $_GET['Email'];
    if(isset($_GET['SteamID']))
        $steam = $_GET['SteamID'];
    if(isset($_GET['submit']) && $_GET['submit'] == 'Submit' )
    {
        
        if( $nick == false )
            ShowMessage( 'Nick not provided' );
        else if( $email == false )
            ShowMessage( 'Email not provided' );
        else if( $pass == false )
            ShowMessage( 'Password not provided' );
        else if( strlen($nick) <= 2 )
            ShowMessage( 'Nick is too short' );
        else if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
            ShowMessage( 'Email bad' );
        else if( strlen($pass) < 3 )
            ShowMessage( 'Password is too short' );
        else
        {

            $conn = mysqli_connect( "127.0.0.1", "dust", "", "amx_knf" ) or die("database error");
            //insert into amx_admins (username,password,email,access,flags,created,expires) values ('tes','asd','ads','z','a',unix_timestamp(),0)
            $password = md5( md5( $pass ) . HASH_SALT );
            $query = 'INSERT IGNORE INTO `amx_admins` (username,steamid,password,email,access,flags,created,expires) VALUES
            (\''.SqlEsc($nick).'\',\''.SqlEsc($steam).'\',\''.SqlEsc($password).'\',\''.SqlEsc($email).'\',\'z\',\'a\','.time().',0);';
            $conn->query($query);
            if($conn->error)
            {
                echo $query;
                echo "<br><br>". $conn->error;
                return 0;
            }
            if($conn->affected_rows == 1)
                ShowMessage('Nick registered.', true);
            else
            {
                ShowMessage('Nick or email already registered. Check manually.');
                $res = $conn->query('SELECT `username`,`email` FROM amx_admins WHERE `username`=\''.SqlEsc($nick).'\' OR `email`=\''.SqlEsc($email).'\';');
                if( $res->num_rows > 0 )
                {
                    $r = $res->fetch_assoc();
                    
                    if( strcasecmp($r['username'],$nick) == 0 )
                    {
                        ShowMessage('Nickname already used.');
                    }
                    else if( strcasecmp($r['email'],$email) == 0 )
                    {
                        ShowMessage('Email already used by nick: '.htmlspecialchars($r['username']));
                    }
                }
            }
                
            
        }
        
    }
?>

<form method=GET>
    <table border=0>
        <tr>
        <td>Nick:</td>
        <td><input type=text name=Nick value="<?php echo $nick==false?'':$nick ?>" ></td><tr>
        <td>Email:</td>
        <td><input type=text name=Email value="<?php echo $email==false?'':$email ?>" ></td><tr>
        <td>Pass:</td>
        <td><input type=text name=Password value="<?php echo $pass==false?'':$pass ?>"></td><tr>
        <td>SteamID:</td>
        <td><input type=text name=SteamID value="<?php echo $steam==false?'':$steam ?>"></td><tr>
        <td colspan=2><input type=submit name=submit value=Submit></td>
    </table>
</form>