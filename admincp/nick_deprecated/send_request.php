<html>
    <head>
        <title>Password Reset</title>
    </head>
    <body>
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
            $result = $conn->query( "SELECT `id`,`username`,`reset_expiration` FROM `amx_admins` WHERE `username`='".$conn->real_escape_string($_POST['nick'])."';") or die( "Query Error.Contact https://steamcommunity.com/id/SwDusT/");
            if( $result->num_rows === 1 )
            {
                $r = $result->fetch_array();
                $id = $r['id'];
                $name = $r['username'];
                $reset_expiration = $r['reset_expiration'];
                if( !empty( $reset_expiration ) && $reset_expiration > time() )
                {
                    echo "<div>Email already sent! Wait at least 1hour before sending another email.</div>";
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
                echo "<div>No username found</div>";
                //die();
            }
        }
        
        function SendMail()
        {
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
                echo '<div class="success">Password reset mail sent. Check your email.</div>';
            } catch (Exception $e) {
                echo "Message could not be sent. Contact Staff members.";
            }
        }
        
    ?>
        <form name="f" method="POST">
            <input type="text" name="nick" id="nick" placeholder="Type nick">
            <input type="submit" value="Reset">
        </form>
    </body>
