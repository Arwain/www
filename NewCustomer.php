<?php
/**
 * Created by Arwain.
 * User: Arwain
 * Date: 12/5/2017
 * Time: 11:35 PM
 */
if(!isset($_SESSION)){
    session_start();
}

// SET $page_type = 'customer','owner','public'
$page_type = 'customer';
require('inc.header.php');


# CONNECT TO DATABASE
if (!isset($db)) {
    require_once('inc.dbc.php');
    $db = get_connection();
}

// Echo variables
$password = "";
$name     = "";
$email    = "";

$sql = "INSERT INTO User($name, $password, $email)";

?>

<body>
<style>
    body {
        background-image: url("images/userBackground.jpg");
    }

</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Welcome to Mario Cart!</h2>
    </div>
    <div class="panel-body">
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <ul class="nav nav-pills nav-stacked">
                <!--  ************************** -->
                <!--  SET NAVIGATION ACTIVE HERE -->
                <!--  ************************** -->
                <li role="presentation" class="active">  <a href="CustomerProfile.php">Customer Profile</a></li>
                <li role="presentation" class="inactive"><a href="Login.php">Login</a></li>
            </ul>
        </div>
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome, enter your info below.</div>
                <div class="panel-body">
<!--                    --><?php //echo $message; ?>
                    <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-group">
                            <input type="text" class="form-control" value="<?php echo $name; ?>" placeholder="enter preferred name" name="pref_name" autofocus />
                            <input type="text" class="form-control" value="<?php echo $password; ?>" placeholder="enter password" name="pref_name" autofocus />
                            <input type="email" class="form-control" value="<?php echo $email; ?>" placeholder="enter email" name="email"/>
                            <button class="btn btn-lg btn-primary btn-block" type="submit" name="update">
                                Create Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("./inc.footer.php");?>
