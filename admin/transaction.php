<?php
    session_start();
    include("../db.php");
    include("header.php");
    if(!isset($_SESSION["AID"])){
        header("location:../admin_login.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Home</title>
    <link rel="stylesheet" type="text/css" href="../css/tables.css">
    <link rel="stylesheet" type="text/css" href="../css/register.css">
    
</head>
<body>

<h3 style='text-align: center;'>CUSTOMERS BILLING HISTORY</h3>
<form action="<?php echo $_SERVER["PHP_SELF"];?>" method = "post">
  <div class="container content" style="width: 500px; height: 50%; margin:auto; margin-top: 40px; background-color:ivory">
    <h1>Search Billing History</h1>
    <hr>

    <label for="searchby">Search by&ensp;&ensp;</label>
    <select name="searchby" style="background-color: rgb(235, 235, 235);width:80%;height:40px;" >
          <option value="book.bname">Book</option>
          <option value="customer.cus_name">Customer</option>
    </select>
    <br><br>

    <label for="name"><b>Search</b></label>
    <input type="text" placeholder="Enter name of book or customer" name="name">
    <br><hr style="width:46%; float: left"><span style="text-align:center">&ensp;or&nbsp;</span><hr style="width:46%; float: right"><br><br>

    <label for="pricebelow"><b>Price Range</b></label><br>
    <input type="number" placeholder="Above" name="pricebelow" style="background-color: rgb(235, 235, 235);width:48%;height:40px; float:left">
    <input type="number" placeholder="Below" name="priceabove" style="background-color: rgb(235, 235, 235);width:48%;height:40px; float:right">
    <br><br><br>

    <div class="clearfix" style="padding-left: 3px"> 
      <button type="submit" class="signup" name="search" style="width: 48%;">Search</button>
      <a href="<?php echo $_SERVER["PHP_SELF"];?>"><button class="signup" style="background-color: red;width:48%;float:right;">Clear</button></a>
    </div>
  </div>
</form>
<br>
<?php
    if (!isset($_POST['search'])) {
        $sql="SELECT customer.cus_name, book.bname, book.price, payments.txn_id, payments.logs
        from payments inner join book on book.bid=payments.bid
        inner join customer on customer.cus_id=payments.cus_id 
        order by payments.logs DESC;";
    }
    else {
        if ($_POST['pricebelow']) {
            $sql = "SELECT customer.cus_name, book.bname, book.price, payments.txn_id, payments.logs
            from payments inner join book on book.bid=payments.bid
            inner join customer on customer.cus_id=payments.cus_id where book.price > {$_POST['pricebelow']} 
            order by payments.logs DESC;";
        }
        elseif ($_POST['priceabove']) {
            $sql = "SELECT customer.cus_name, book.bname, book.price, payments.txn_id, payments.logs
            from payments inner join book on book.bid=payments.bid
            inner join customer on customer.cus_id=payments.cus_id where book.price < {$_POST['priceabove']}
            order by payments.logs DESC;";
        }
        elseif ($_POST['pricebelow'] && $_POST['pricebelow']) {
            $sql = "SELECT customer.cus_name, book.bname, book.price, payments.txn_id, payments.logs
            from payments inner join book on book.bid=payments.bid
            inner join customer on customer.cus_id=payments.cus_id where book.price BETWEEN {$_POST['pricebelow']} and {$_POST['priceabove']}
            order by payments.logs DESC;";
        }
        else {
            $sql = "SELECT customer.cus_name, book.bname, book.price, payments.txn_id, payments.logs
            from payments inner join book on book.bid=payments.bid
            inner join customer on customer.cus_id=payments.cus_id where {$_POST['searchby']} LIKE '%{$_POST['name']}%'
            order by payments.logs DESC;";
        }
     }
    $res=$db->query($sql);

    if($res->num_rows>0)
    {
        echo "
        <div>
        <table class='container'>
            <thead>
            <tr>
                <th>TRANSACTION ID</th>
                <th>CUSTOMER NAME</th>
                <th>BOOK NAME</th>
                <th>PRICE</th>
                <th>LOGS</th>
            </tr>
            </thead>
            <tbody>
        ";
        while($rows=$res->fetch_assoc())
        {
            echo"
                <tr>
                    <td>{$rows["txn_id"]}</td>
                    <td>{$rows["cus_name"]}</td>
                    <td>{$rows["bname"]}</td>
                    <td>{$rows["price"]}</td>
                    <td>{$rows["logs"]}</td>
                </tr>
            ";
        }

        echo"
        </thread>
        </table>
        </div>
        ";
    }
    else
    {
        echo "<p style='color: red'>No Transactions</p>";
    }
?>
    

</div>
</body>
</html>