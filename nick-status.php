<?php
    define( 'DB_HOST', '127.0.0.1' );
    define( 'DB_USER', 'dust' );
    define( 'DB_PASS', '' );
    define( 'DB_DB'  , 'amx_knf' );

    $access = array(
        'bp'=>'VIP',
        'bipt'=>'Super VIP',
        'bcdeijnoptu'=>'Admin',
        'abcdefijnoptuv'=>'Super Admin',
        'abcdefijnoprtuv'=>'Dev/Demo Checker',
        'abcdefijnoprstuv'=>'Server Manager',
        'abcdefghijmnopqrstuv'=>'Moderator',
        'abcdefghijkmnopqrstuv'=>'Head Moderator',
        'abcdefghijklmnopqrstuv'=>'Administrator',
        'abcdefghijklmnopqrstuvy'=>'Owner'
    );
    function NotNull( $word )
    {
        if( isset( $_POST[$word]) && !empty( $_POST[$word] ) )
            return true;
        else
        {
            echo "<div class=\"nickresult\">Invalid ".$word."</div><br><br>";
            return false;
        }
    }

    if( isset( $_POST[ 'search' ] ) && $_POST[ 'search' ] == 'Search' )
    {
        $mysqli = mysqli_connect(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_DB
        );
        if( NotNull( 'nick' ) )
        {
            $query = "SELECT username, access, expires FROM amx_admins WHERE username='".$mysqli->escape_string($_POST['nick'])."';";
            
            $result = $mysqli->query($query);
            if( $mysqli->error )
            {
                //echo $_SERVER['REMOTE_HOST'];
                if( $_SERVER['REMOTE_HOST'] == '151.81.55.67' )
                    echo $mysqli->error;
                else
                    echo "<div class=\"nickresult\">System is temporarily offline. Contact Owner.</div>";
            }
            else
            {
                if( !$result->num_rows )
                {
                    echo "<div class=\"nickresult\">Nick is not registered.</div><br><br>";
                }
                else
                {
                    $r = $result->fetch_assoc();
                    echo "<div class=\"nickresult\">";
                    echo "Nick <b>".htmlspecialchars($r['username'])."</b> is registered.<br>";
                    if( $r['access'] !== 'z' )
                    {
                        echo "Player has <b>".$access[$r['access']]."</b> flags.<br>"; 
                        if( $r['expires'] == 0 )
                            $time = 'Never';
                        else
                            $time = date("d.m.Y", $r['expires'] );
                        echo "Privileges will expire: <b>".$time."</b>";
                    }   
                    echo "</div><br>";
                }
            }
        }
    }
    else 
        echo "<br><br><br>";
?>
<head>
    <style>
        table
        {
            margin-left:auto; 
            margin-right:auto;
        }
        div
        {
            text-align: center;
        }
        .nickresult 
        {
            color: #949494;
        }
        /*.button.button--primary, a.button.button--primary {
            color: #fff;
            background: #2196F3;
            box-shadow: 0 0 2px 0 rgba(0,0,0,0.14), 0 2px 2px 0 rgba(0,0,0,0.12), 0 1px 3px 0 rgba(0,0,0,0.2);
            border: none;
            border-color: #48a8f5;
            margin-top: 5px;
            position: relative;
            left: 40px;
        }
        .button, a.button {
            font-size: 14px;
            font-weight: 500;
            border-radius: 3px;
            padding-top: 0;
            padding-right: 10px;
            padding-bottom: 0;
            padding-left: 10px;
            text-align: center;
            outline: none;
            line-height: 32px;
            height: 32px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-sizing: content-box;
            -webkit-appearance: none;
            text-transform: uppercase;
            will-change: box-shadow;
            transition: all .3s cubic-bezier(.25, .8, .25, 1);
            white-space: nowrap;
        }*/
        .button.button--primary {
            color: #fff;
            background: #366c36;
            min-width: 80px;
            border-color: #2d5b2d #3e7d3e #3e7d3e #2d5b2d;
        }
        .button {
            display: block;
            margin: 10px auto;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap;
            -webkit-transition: background-color .25s ease;
            transition: background-color .25s ease;
            font-size: 13px;
            border-radius: 4px;
            padding-top: 5px;
            padding-right: 10px;
            padding-bottom: 5px;
            padding-left: 10px;
            text-align: center;
            color: #fff;
            background: #185886;
            border-color: #144a70 #1c669c #1c669c #144a70;
        }
        .nickinfo{
            height: 30px;
            border: 1px inset;
            transition: ease-in color .2s;
            background: none;
            text-indent: 10px;
            color: #949494;
        }
    </style>
</head>
<table border=0>
    <tr>
    <form method=POST>
        <td><input type=text class=nickinfo name=nick placeholder=Nick><br></td>
        <tr>
        <td><input type=submit name=search value=Search class="button--primary button"></td>
    </form>
        
</table>