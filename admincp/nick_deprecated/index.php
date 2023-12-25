<html>
    <head>
        <title>Password Reset</title>
        <style>
        table
        {
            margin-top:15px;
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
        .button-text {
            /*display: inline-block;*/
            display: block;
            margin: 0 auto;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap;
            -webkit-transition: background-color .25s ease;
            transition: background-color .25s ease;
            font-size: 15px;
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
       
        .input {
            font-size: 15px;
            margin: 0 auto;
            color: #dedede;
            background: #414141;
            border-width: 1px;
            border-style: solid;
            border-top-color: #373737;
            border-right-color: #414141;
            border-bottom-color: #414141;
            border-left-color: #373737;
            border-radius: 4px;
            padding: 6px;
            display: block;
            width: 192px;
            line-height: 1.4;
            text-align: left;
            word-wrap: break-word;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -webkit-transition: all .25s ease;
            transition: all .25s ease;
        }
    </style>
    </head>
    <body>
        <table id=tblnicks border=0>
        <tr>
        <?php
        // Import PHPMailer classes into the global namespace
        // These must be at the top of your script, not inside a function
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        // Load Composer's autoloader
        require 'vendor/autoload.php';

        const EXP_TIME = 3600; 
        const MAX_LEN = 30;
        const BASE_URL = 'https://bans.csamx.net/nick/resetnick.php?code=';

        function GetRandomCode( $len = MAX_LEN ) {
            $arrlet = array_merge( range( 'a', 'z' ),range( 'a', 'z' ), range( '0', '9' ), range( '0', '9' ));
            //$word = $word + $numbers;
            shuffle( $arrlet );
            return substr( implode( $arrlet ), 0, $len );    
        }

        if( isset( $_POST['nick'] ) && !empty( $_POST['nick'] ) )
        {
            $conn = mysqli_connect( "127.0.0.1", "dust", "", "amx_knf" ) or die( "Database error. Contact https://steamcommunity.com/id/SwDusT/" );
            $result = $conn->query( "SELECT `id`,`email`,`username`,`reset_expiration` FROM `amx_admins` WHERE `username`='".$conn->real_escape_string($_POST['nick'])."';") or die( "Query Error.Contact https://steamcommunity.com/id/SwDusT/");
            if( $result->num_rows === 1 )
            {
                $r = $result->fetch_array();
                $id = $r['id'];
                $name = $r['username'];
                $reset_expiration = $r['reset_expiration'];
                $email = $r['email'];
                if( !empty( $reset_expiration ) && $reset_expiration > time() )
                {
                    echo "<td><div class='nickresult'>Email already sent! Wait at least 1hour before sending another email.</div></td><tr>";
                }
                else if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
                {
                    echo "<td><div class='nickresult'>Invalid registered email for this user. Contact Staff members.</div></td><tr>";
                }
                else
                {
                    $reset_expiration = time() + EXP_TIME;
                    $code = GetRandomCode();
                    
                    $url = BASE_URL . $code;
                    //echo $code;
                    //echo $url; 
                    $conn->query("UPDATE `amx_admins` SET reset_code='".$code."', reset_expiration=".$reset_expiration." WHERE `id`=".$id.";");
                    SendMail();
                }
            }
            else
            {
                echo "<td><div class='nickresult' id=extraDiv>This username is not registered.</div></td><tr>";
                //die();
            }
        }
        else
            echo "<td style='height:20px;'></td><tr>";
        function SendMail()
        {
            global $name, $url;
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();

                $mail->Host = 'mail.nfoservers.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'support@csamx.net';
                $mail->Password = ''; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('support@csamx.net', 'AmX Gaming');

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Password Reset';
                $mail->Body    = 'Password Reset Request for Nick: ' . htmlspecialchars($name) . '<br>
                                Follow to be able to reset your password (Link will expire in 1 hour: <a href="'.$url.'">' . $url . '</a><br>
                                <br><br>For any problem, write to this email. <br> Thank you, AmX Gaming Staff.';
                $mail->AltBody = 'Password Reset Request for Nick: ' . $name .'. Link will expire in 1 hour: '.$url.'.
                                For any problem, write to this email. <br> Thank you, AmX Gaming Staff.';

                $mail->send();
                echo '<div class="nickresult">Password reset mail sent. Check your email.</div>';
            } catch (Exception $e) {
                echo "Message could not be sent. Contact Staff members.";
            }
        }
        
    ?>
            <form method=POST>
                <td><input type=text class="input" name="nick" placeholder="Nick" ><br></td>
                <tr>
                <td><input type=submit value=Reset class="button-text"></td>
            </form>
        </table>
    </body>
    <script>
        let table = document.getElementById('tblnicks');
        let extraDiv = document.getElementByid('extraDiv');
        if( extraDiv )
            table.style.marginTop  = "15px";
    </script>
</html>
