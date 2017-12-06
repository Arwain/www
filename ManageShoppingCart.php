<?php
if(!isset($_SESSION)){
    session_start();
}

// SET $page_type = 'customer','owner','public'
$page_type = 'customer';
require('inc.header.php');

if(!isset($db))
{
    require('inc.dbc.php');
    $db = get_connection();
}

// HANDLE ENROLLMENT FORM
/*
$message = '';
if(isset($_POST['enroll']))
{
  $updateq = $db->prepare('INSERT INTO Registration (course_number,student_id) VALUES (:course , :uid)');
  if($updateq->execute(array(':course' => $_POST['course'], ':uid' => $_SESSION['userid'])))
  {
    $message = '<p class="alert-success">Enrolled Successfully in ' . $_POST['course'] . '!!!</p>';
  } else { // THERE WAS AN ERROR!
    $message = '<p class="alert-warning">There was an issue</p>.';
  }
}
*/

// HANDLE SELECTING FORM
$message = '';
if(isset($_POST['select']))
{
    $updateq = $db->prepare('INSERT INTO ShoppingCart VALUES (:item , :uid, :quantity)');
    if($updateq->execute(array(':item' => $_POST['item'], ':uid' => $_SESSION['userid'], ':quantity' => $_SESSION['quantity'] + 1)))
    {
        $message = '<p class="alert-success">Selected Successfully in ' . $_POST['item'] . '!!!</p>';
    } else { // THERE WAS AN ERROR!
        $message = '<p class="alert-warning">There was an issue</p>.';
    }
}

// HANDLE DELETE ACTION
if(isset($_GET['action']))
{
    // MAKE SURE THE SESSION USER IS THE SAME AS THE USER REQUEST.
    if($_GET['uid'] == $_SESSION['userid'])
    {
        $remove = $db->prepare('DELETE FROM ShoppingCart WHERE ItemName = :item AND CustomerID = :uid');
        if($remove->execute(array(':item' => $_GET['it'], ':uid' => $_SESSION['userid'])))
        {
            echo $_GET['it'];
            $message = '<p class="alert-success">Successfully remove an item.</p>';
        } else {
            $message = '<p class="alert-warning">Error removing item.  Try again later.</p>';
        }

    } else {
        $message = '<p class="alert-warning">Unable to take the desired action</p>';
    }

}


// DRAW THE LIST OF ITEMS ALREADY IN SHOPPING CART
$c = $db->prepare('SELECT ItemName, Quantity FROM ShoppingCart WHERE CustomerID = :uid');
$c->execute(array(':uid' => $_SESSION['userid']));

$c_res = $c->fetchAll();
if (count($c_res) > 0)
{  // THERE ARE COURSES, DRAW THE FORM
    $item_list = '<table class="table table-striped"><thead><tr><th>ItemName</th><th>Quantity</th><th>Action</th></tr></thead><tbody>';
    foreach($c_res as $item)
    {
        $item_list .= '<tr><td>' . $item['ItemName'] . '</td>';
        $item_list .= '<td>' . $item['Quantity'] . '</td>';
        $item_list .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?action=del&it='.$item['ItemName'] . '&uid='.$_SESSION['userid'].'">Delete</a></td></tr>';
    }

    $item_list .= "</table>";
} else {
    $item_list = '<p class="alert-warning">You haven\'t selected any item yet.  Select Below</p>';
}


// DRAW THE ITEM FORM (FOR THE CUSTOMER TO SELECT)
$items = $db->prepare('SELECT I.ItemName, I.Quantity FROM Item I WHERE I.Quantity > 0
                  AND NOT EXISTS (SELECT S.ItemName FROM ShoppingCart S
                           WHERE I.ItemName = S.ItemName
                             AND I.Quantity = S.Quantity )');
$items->execute(array(':uid'   => $_SESSION['userid']));

$c_res = $items->fetchAll();

// GET ACTIVE SELECTING INFORMATION
if (count($c_res) > 0)
{
    // BUILD THE DROPDOWN LIST
    $select_form = '<form role="form" method="POST" action="'. $_SERVER['PHP_SELF']. '"><div class="form-group">Choose an item to select:<br><select class="form-control" name="item">';
    foreach ($c_res as $item)
        $select_form  .= "<option>".$item['ItemName']."</option>";

    $select_form  .= '</select><button class="btn btn-lg btn-primary" type="submit" name="select">Select</button>';

} else {
    $select_form  = '<p class="alert-warning">There are no available items.  Try again later</p>';
}

?>

<body>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Welcome to Mario Cart!</h2>
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
                <li role="presentation" class="active">    <a href="ManageShoppingCart.php">Manage Shopping Cart</a></li>
                <li role="presentation" class="inactive">  <a href="Logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome, <?php echo $name; ?>.  Update your registration below.</div>
                <div class="panel-body">
                    <?php echo $item_list; ?>
                    <hr>
                    <?php echo $message; ?>
                    <?php echo $select_form; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("./inc.footer.php");?>
