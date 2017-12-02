<?php
session_start();
session_destroy();

if (isset($_POST['login'])) // HANDLE THE FORM
{

    // CHECK TO ENSURE INPUTS ARE VALID
    if (strlen($_POST['username']) == 0 || strlen($_POST['pwd']) == 0)
    {
        $errors = '<p class="alert-danger">Please enter both a username and a password</p>';
    } else {
        // CHECK USER IN DATABASE, THIS IS SUSEPTABLE TO SQL INJECTION AND FAILURE DUE TO QUOTES
        if(!isset($db))
        {
            require('inc.dbc.php');
            $db = get_connection();
        }

        // QUERY TO VALIDATE USER
        $q = "SELECT userid, username, pwd, role 
           FROM User 
          WHERE username = '".$_POST['username']."' 
            AND pwd = '"     .md5($_POST['pwd'])."'";

        // EXECUTE THE QUERY
        $r = $db->query($q);
        $rr = $r->fetchAll();
        if(count($rr) == 1) // ASSUMES UNIQUENESS OF USERID SET IN DATABASE
        {
            //DATA RETURNED SETUP MY SESSION
            @session_start();
            $_SESSION['userid']   = $rr[0]['userid'];
            $_SESSION['role']     = $rr[0]['role'];

            // REDIRECT TO THE CORRECDT PORTAL
            $location = ($_SESSION['role'] == 'owner') ? "ManageItems.php" : "CustomerProfile.php";
            header("Location: " . $location);
        } else { // THROW ERROR
            $errors = '<p class="alert-danger">Invalid Username/Password</p>';
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TCSS445 Project Page Sign In</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/signin.css" rel="stylesheet">
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
</head>

<body>
<div class="container">
    <!-- BEGIN CONTENT -->
    <h2 class="text-center">Thanks for shopping with MarioCart!</h2>

    <form class="form-signin" role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h4 class="form-signin-heading text-center">Left some Mushrooms in your cart?</h4>
        <input type="text" class="form-control" placeholder="Username" name="username" required autofocus />
        <input type="password" class="form-control" placeholder="Password" required name="pwd">

        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">
            Sign in
        </button>
    </form>
</div>
<div class="overlay"></div>
<style>
    .overlay {
        opacity: .3;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("images/McLogo.png") repeat;
    }body {
        background-image: url("images/mario.gif"), url("images/McLogo.png");
    }

    h2 {
        color: red;
        font-family: Comic Sans MS;
        font-size: 54px;
    }
    h4{
        color: #4581ff;
        font-family: Comic Sans MS;
        font-size: 24px;
    }</style>
<audio autoplay>
    <source src="sounds/startheme.mp3" type="audio/mp3">
</audio>
