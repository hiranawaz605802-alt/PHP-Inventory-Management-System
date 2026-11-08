<?php

include_once("Database.php");


$db=new database();

$conn=$db->connection();

echo$id=$_POST["id"];
echo$name=$_POST["name"];
echo$price=$_POST["price"];
echo$discraption=$_POST["discraption"];
echo$stock_quantity=$_POST["stock_quantity"];
echo$category_id=$_POST["category_id"];
$sql="update entity_asad_product set name='".$name."',price=".$price.",discraption='".$discraption."',stock_quantity=".$stock_quantity.",category_id=".$category_id." where id=".$id."";
$res=$conn->query($sql);

if($res){
	
	header("location:product_view.php");
}else{
	echo"--------------";
}


?>