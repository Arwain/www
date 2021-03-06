<?php
if (!isset($_SESSION)) {
    session_start();
}

// SET $page_type = 'customer','owner','public'
$page_type = 'customer';
require('inc.header.php');

if (!isset($db)) {
    require('inc.dbc.php');
    $db = get_connection();
}
$abc = "Enter the amount of the item you'd like to buy. Must be more than 0, up to the maximum amount in stock.";

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
$message = '';

// Handle Creation/Updates of Items
if (isset($_POST['accept']))
{
    if (strcmp($_POST['item'], "--- Select an Item Below ---") == 0)
    {
        $message .= '<p class="alert-danger">Please Select An Item</p>';
    }
    if ($_POST['quantity'] <= 0 || $_POST['quantity'] >= 100)
    {
        $message .= '<p class="alert-danger">Item Quantity Invalid: ' . $_POST['quantity'] . '</p>';
    }
    else if ($_POST['quantity'] > 0 && ( $_POST['quantity'] < 100 && strcmp($_POST['item'], "--- Select an Item Below ---") != 0))
    {
        $message .= '<p class="alert-success">Trying to do something here</p>';
        $new = $db->prepare("REPLACE INTO ShoppingCart (ItemName, CustomerID, Quantity)
        VALUES (:ItemName, :CustomerID, :Quantity)");
        if ($new->execute(array(':ItemName' => $_POST['item'], ':CustomerID' => $_SESSION['userid'], ':Quantity' => $_POST['quantity'], )))
        {
            $message = '<p class="alert-success">Successfully added or updated item '. $_POST['item'] .'</p>' ;
        }
    }
}
// HANDLE DELETE ACTION
if (isset($_GET['action'])) {
    // MAKE SURE THE SESSION USER IS THE SAME AS THE USER REQUEST.
    if ($_GET['uid'] == $_SESSION['userid']) {
        $remove = $db->prepare('DELETE FROM ShoppingCart WHERE ItemName = :item AND CustomerID = :uid');
        if ($remove->execute(array(':item' => $_GET['it'], ':uid' => $_SESSION['userid']))) {
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
if (count($c_res) > 0) {  // THERE ARE ITEMS, DRAW THE FORM
    $item_list = '<table class="table table-striped"><thead><tr><th>ItemName</th><th>Quantity</th><th>Action</th></tr></thead><tbody>';
    foreach ($c_res as $item) {
        $item_list .= '<tr><td>' . $item['ItemName'] . '</td>';
        $item_list .= '<td>' . $item['Quantity'] . '</td>';
        $item_list .= '<td><a href="' . $_SERVER['PHP_SELF'] . '?action=del&it=' . $item['ItemName'] . '&uid=' . $_SESSION['userid'] . '">Delete</a></td></tr>';
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
$items->execute(array(':uid' => $_SESSION['userid']));

$c_res = $items->fetchAll();

// GET ACTIVE SELECTING INFORMATION
if (count($c_res) > 0) {
    // BUILD THE DROPDOWN LIST
    $select_form = '<form role="form" method="POST" action="' . $_SERVER['PHP_SELF'] . '"><select class="form-control" name="item">';
    $select_form .= "<option>" . ' --- Select an Item Below --- ' . "</option>";
    foreach ($c_res as $item)
        $select_form .= "<option>" . $item['ItemName'] . "</option>";
} else {
    $select_form = '<p class="alert-warning">There are no available items.  Try again later</p>';
}


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
                <li role="presentation" class="inactive"><a href="CustomerProfile.php">Customer Profile</a></li>
                <li role="presentation" class="active"><a href="ManageShoppingCart.php">Manage Shopping Cart</a></li>
                <li role="presentation" class="inactive"><a href="Logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome, <?php echo $name; ?>. View your shopping cart below!</div>
                <div class="panel-body">
                    <?php echo $item_list; ?>
                    <hr>
                    <?php echo $message; ?>
                        <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            Select the item you'd like to purchase below.
                            <?php echo $select_form; ?>
                            <div class="form-group">
                                <?php echo
                                "Enter the amount of the item you'd like to buy. Must be more than 0, up to the maximum amount in stock."; ?>
                                <input type="number" placeholder="enter item quantity" name="quantity" class="form-control" />
                                <button class="form-group btn btn-lg btn-primary" type="submit" name="accept" value="active">Accept</button>
                            </div>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
<?php include("./inc.footer.php"); ?>
