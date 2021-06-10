<?php
session_start();
require_once("config.php");
function getNumber($qew){
    $qew = preg_replace('/[^0-9]/', '', $qew);
    return intval($qew);
}    

//Cart
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	//adding product in cart
    
	case "add":
		if(!empty($_POST["quantity"])) {
			$pid=getNumber($_GET["pid"]);
            $_POST["quantity"] = getNumber($_POST["quantity"]);
			$result=mysqli_query($con,"SELECT * FROM tblproduct WHERE id='$pid'");
	          while($productByCode=mysqli_fetch_array($result)){
			$itemArray = array($productByCode["code"]=>array('name'=>$productByCode["name"], 'code'=>$productByCode["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode["price"], 'image'=>$productByCode["image"]));
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			}  else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	}
	break;

	//removing product from cart
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	// if cart is empty
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>
<HTML>
<HEAD>
<TITLE>PHP Shopping Cart</TITLE>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="shopping cart,php,Mahesh Surapaneni,Mahesh Kumar Surapaneni,web developer">
     <link rel="shortcut icon" href="https://res.cloudinary.com/dzo3hbemf/image/upload/v1623215079/bg/mah_yskjlm.jpg" type="image/jpeg">
    <meta name="google-site-verification" content="ZKyT8ToCwRmktXMN-ebErWjlWTl7VdsN2ftrPj3E2Go" />   
    <meta name="description" content="Shopping Cart with PHP">
    <meta charset="utf-8">
<style>

body {
	font-family: Arial;
	color: #211a1a;
	font-size: 0.9em;
}

#shopping-cart {
	margin: 40px;
}

#product-grid {
	margin: 40px;
}

#shopping-cart table {
	width: 100%;
	background-color: #F0F0F0;
}

#shopping-cart table td {
	background-color: #FFFFFF;
}

.txt-heading {
	color: #211a1a;
	border-bottom: 1px solid #E0E0E0;
	overflow: auto;
}

#btnEmpty {
	background-color: #ffffff;
	border: #d00000 1px solid;
	padding: 5px 10px;
	color: #d00000;
	float: right;
	text-decoration: none;
	border-radius: 3px;
	margin: 10px 0px;
}

.btnAddAction {
    padding: 5px 10px;
    margin-left: 5px;
    background-color: #efefef;
    border: #E0E0E0 1px solid;
    color: #211a1a;
    float: right;
    text-decoration: none;
    border-radius: 3px;
    cursor: pointer;
}

#product-grid .txt-heading {
	margin-bottom: 18px;
}

.product-item {
	float: left;
	background: #ffffff;
	margin: 30px 30px 0px 0px;
	border: #E0E0E0 1px solid;
}

.product-image {
	height: 155px;
	width: 250px;
	background-color: #FFF;
}

.clear-float {
	clear: both;
}

.demo-input-box {
	border-radius: 2px;
	border: #CCC 1px solid;
	padding: 2px 1px;
}

.tbl-cart {
	font-size: 0.9em;
}

.tbl-cart th {
	font-weight: normal;
}

.product-title {
	margin-bottom: 20px;
}

.product-price {
	float:left;
}

.cart-action {
	float: right;
}

.product-quantity {
    padding: 5px 10px;
    border-radius: 3px;
    border: #E0E0E0 1px solid;
}

.product-tile-footer {
    padding: 15px 15px 0px 15px;
    overflow: auto;
}

.cart-item-image {
	width: 30px;
    height: 30px;
    border-radius: 50%;
    border: #E0E0E0 1px solid;
    padding: 5px;
    vertical-align: middle;
    margin-right: 15px;
}
.no-records {
	text-align: center;
	clear: both;
	margin: 38px 0px;
}
a[href^="https://www.000webhost.com/"]{display:none;}
</style>
</HEAD>
<BODY>


<!-- Cart ---->
<div id="shopping-cart">
<div class="txt-heading">Shopping Cart</div>

<a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Name</th>
<th style="text-align:left;">Code</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["code"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "₹ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "₹ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr>
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "₹ ".number_format($total_price, 2); ?></strong></td>
<td></td>
</tr>
</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>
</div>




<div id="product-grid">
	<div class="txt-heading">Products</div>
	<?php
	$product= mysqli_query($con,"SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product)) { 
		while ($row=mysqli_fetch_array($product)) {
		
	?>
		<div class="product-item">
			<form method="post" action="index.php?action=add&pid=<?php echo $row["id"]; ?>">
			<div class="product-image"><img src="<?php echo $row["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $row["name"]; ?></div>
			<div class="product-price"><?php echo "₹".$row["price"]; ?></div>
			<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
			</div>
			</form>
		</div>
	<?php
		}
	}  else {
 echo "No Records.";

	}
	?>
</div>



</BODY>
</HTML>