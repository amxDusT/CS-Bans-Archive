<?php
    // len = 24
    define( 'MAX_LEN', 16 );
    $types_global = array(
        -1 => "Random",
        0 => "VIP 1 Day",
        1 => "VIP 7 Days",
        2 => "VIP 30 Days",
        3 => "ADMIN 1 Day",
        4 => "ADMIN 7 Days",
        5 => "ADMIN 30 Days",
        6 => "+1h Playtime",
        7 => "+10h Playtime"
    );
    $conn = mysqli_connect( "127.0.0.1", "dust", "", "amx_knf" ) or die;

    function GetRandomWord( $len = MAX_LEN ) {
        $arrlet = array_merge( range( 'A', 'Z' ),range( 'A', 'Z' ), range( '0', '9' ), range( '0', '9' ));
        //$word = $word + $numbers;
        shuffle( $arrlet );
        return "CODEAMX-" . substr( implode( $arrlet ), 0, $len/2 ) . "-" . substr( implode( $arrlet ), $len/2, $len/2 ) ;    
    }
    function InsertData( $string, $type, $codename )
    {
        global $conn;
        $time = time() + 86400*30;
        $query = "INSERT INTO `promo_codes` VALUES(null,'".$conn->real_escape_string($codename)."','".$conn->real_escape_string($string)."', $type, 0, ".time().", $time, NULL,NULL,NULL );";
        $conn->query($query);
        if( $conn->error )
        {
                echo "<br/><br/>Error:".$conn->error."<br/>";
            return 0;
        }
    }
    function GenerateNumCodes( $num, $type = -1, $codename )
    {
        global $types_global;
        for( $i = 0; $i < $num; $i++ )
        {
            $string = GetRandomWord();
            $type_t = $type==-1? random_int(0,7):$type;
            echo $string ." | ".$types_global[$type_t] ." | ".$codename."<br>";
            InsertData( $string, $type_t , $codename );
        }
            
    }

    if( isset($_POST['codename']) && isset($_POST['times']) && (1<=$_POST['times'] && $_POST['times']<=10) && isset($_POST['type']) )
    {
        $t = array_search($_POST['type'],$types_global);
        if( $t !== false )
            GenerateNumCodes( $_POST['times'], $t, $_POST['codename'] );
    }
?>

<form name=f method=POST id=codeform>
    <label for="codename">Codename:</label><br>
    <input type=text id=codename name=codename placeholder="Codename">
    <br><br>
    <label for="times">Codes to generate:</label><br>
    <input type=number id=times name=times min=1 max=10 value=1>
    <br><br>
    <label for="codetype">Type of code:</label><br>
    <select name="type" form=codeform id=codetype>
        <?php
            foreach( $types_global as $t )
                echo "<option value='".$t."'>".$t."</option>";
        ?>
    </select>

    <input type=submit value=Submit>
</form>
