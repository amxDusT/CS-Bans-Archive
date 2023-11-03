<?php 
session_start();

if(isset($_GET['ip']) && isset($_GET['port']) && isset($_GET['rcon']))
{
    $_SESSION['ip'] = $_GET['ip'];
    $_SESSION['port'] = $_GET['port'];
    $_SESSION['rcon'] = $_GET['rcon'];
    $_SESSION['is_first'] = 1;
}

if(isset($_SESSION['ip']))
{
    header("Location: home.php");
    exit();
}
?>
<head>
    <title>Rcon Access</title>
    <?php include('header.php'); ?>
</head>
<body>
    <h1>RCON ACCESS</h1>
    <form method=GET autocomplete="on">
            <input type=text name=ip placeholder="Server IP (without port)" class="textbox textbox-login"><br>
            <input type=number name=port placeholder="Server Port" value="27015" class="textbox textbox-login"><br>
            <input type=text name=rcon placeholder="Server RCON" class="textbox textbox-login"><br>
            <input type=submit name=Submit class="button">
    </form>
</body>