<?php 
if(!isset($_SESSION)){
    session_start();
}

// SET $page_type = 'customer','owner','public'
$page_type = 'customer';
$message = '';
require('inc.header.php');


if (isset($_POST['update'])) // HANDLE THE FORM
{
  if(!isset($db))  // CONNECT TO DATABASE
  {
    require_once('inc.dbc.php');
    $db = get_connection();
  }

  // PREVENT SQL INJECTION
  $q = $db->prepare('UPDATE User SET PreferredName = :name
                                    , Email          = :email 
                                WHERE UserID         = :uid');
  $q->execute(array(':name'  => stripslashes($_POST['pref_name']),
                    ':email' => stripslashes($_POST['email']),
                    ':uid'   => $_SESSION['userid']));
  
  if (!$q)
    $message = '<p class="alert-warning">Problem Handling Form</p>';
  else
    $message = '<p class="alert-success">Updated Successfully</p>';
    
}

# CONNECT TO DATABASE TO GET STUENT INFO
if (!isset($db)) {
    require_once('inc.dbc.php');
    $db = get_connection();
}

# BUILD QUERY
$q = 'SELECT UserID, Username, PreferredName, Email 
       FROM User
      WHERE UserID = ' . $_SESSION['userid'];

$r = $db->query($q);

$row = $r->fetch(); // GET A SINGLE ROW

$username = $row['Username'];
$userid   = $row['UserID'];
$name     = $row['PreferredName'];
$email    = $row['Email'];


?>
 
<body>
<style>
    body {
        background-image: url("images/mountain.jpg");
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
          <li role="presentation" class="inactive"><a href="ManageShoppingCart.php">Manage Shopping Cart</a></li>
          <li role="presentation" class="inactive"><a href="Logout.php">Logout</a></li>
          </ul>	   
      </div>
      <div class="col-sm-8">
        <div class="panel panel-default">
          <div class="panel-heading">Welcome, <?php echo $name; ?>.  Update your info below.</div>
          <div class="panel-body">
            <h3>Username: <?php echo $username; ?></h3>
        		<?php echo $message; ?>
                <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
		        <input type="text" class="form-control" value="<?php echo $name; ?>" placeholder="enter preferred name" name="pref_name" autofocus />
		        <input type="email" class="form-control" value="<?php echo $email; ?>" placeholder="enter email" name="email"/>
		        <button class="btn btn-lg btn-primary btn-block" type="submit" name="update">
			       Update
		        </button>
		      </div>
            </form>
          </div>        
        </div>
      </div>
    </div>
 </div>
 <?php include("./inc.footer.php");?>
 
