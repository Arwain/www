<?php 
if(!isset($_SESSION)){
    session_start();
}

// SET $page_type = 'customer','owner','public'
$page_type = 'customer';
require('inc.header.php');

?>
 
<body>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h2 class="panel-title">Welcome to TSS445 Project Demo</h2>
    </div>
    <div class="panel-body">
      This mini project leverages Bootstrap 3.3.7 for HTML/CSS/JS, PHP7 and MariaDB 10.1.20
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <ul class="nav nav-pills nav-stacked">
<!--  ************************** -->
<!--  SET NAVIGATION ACTIVE HERE -->
<!--  ************************** -->
          <li role="presentation" class="inactive">  <a href="CustomerProfile.php">Customer Profile</a></li>
          <li role="presentation" class="inactive"><a href="ManageShoppingCart.php">Manage Shopping Cart</a></li>
          <li role="presentation" class="active">  <a href="ContactOwner.php">Contact Owner</a></li>
          <li role="presentation" class="inactive"><a href="Logout.php">Logout</a></li>
          </ul>	   
      </div>
      <div class="col-sm-8">
        This is where the page content goes!
      </div>
    </div>
 </div>
 <?php include("./inc.footer.php");?>
 