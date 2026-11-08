<?php


include_once("Database.php");

$db = new Database();
$conn = $db->connection();

echo$customer_id=$_POST["customer_id"];
// $product_id=$_POST["product_id"];
// $quantity=$_POST["quantity"];
// $unit_price=$_POST["unit_price"];

 $sql="insert into entity_asad_shooping_cart(customer_id)values(".$customer_id.")";

$res=$conn->query($sql);
 if($res){
header("location:product_cart_item_view.php");	
// echo"Welcoem to record insert-------------";
 }
 else
 {
 echo"sorry try agin your record not isnert.";
 }
?>