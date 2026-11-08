<?php

include_once("Database.php");

$db=new database();

$conn=$db->connection();


echo$name=$_POST["name"];
echo$price=$_POST["price"];
echo$discraption=$_POST["discraption"];
echo$stock_quantity=$_POST["stock_quantity"];
echo$category_id=$_POST["category_id"];

 echo$sql="insert into entity_asad_product (name,price,discraption,stock_quantity,category_id)
 values('".$name."',".$price.",'".$discraption."',".$stock_quantity.",".$category_id.")";

 $res=$conn->query($sql);

 if($res){
	header("location:product_view.php");
 //echo"Your Record is inserted............";
 }
 else{
 echo"Sorry try agin sorry";
 }



?>