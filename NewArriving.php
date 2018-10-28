<!DOCTYPE html>
<?php  

/*Connection details for the appropriate server and database*/
$server = "localhost";
$username = "20500057";
$password = "2\$QtTF55";
$database = "DB_20500057";


/*Connect to Database*/
$conn=mysqli_connect($server, $username, $password, $database);


if(!$conn){

die("Connection failed: " . mysqli_connect_error());

}
echo "<font size=3>Database \"" . $database . "\" Connection <font color=#32CD32>SUCCESSFUL</font></font>";


/*Create The Form*/
?>
<html>
<head>
<style>
body{
font-size:20px;
}
table{
font-size:20px;
}
input, select, textarea{
font-size:20px;
}
</style>
<title>NewArriving</title>
</head>
<body bgcolor="#f0e68c">
<h1>Product Stock Alteration</h1><hr>
<form method="post" action="NewArriving.php">
<table>
<tr>
<td><b>Product Quantity (Required):</b></td>
<td><input type="text" name="ProdQuan"></td>
</tr><tr>
<td><br /></td>
</tr><tr>
<td><input type="radio" name="Existing" value=1 checked><b>Modify Product Stock</b></td>
</tr><tr>
<td>Enter PId:</td>
<td><input type="text" name="ProdCode1"></td>
</tr><tr>
<td><b>OR</b></td>
</tr><tr>
<td>Product Name:</td>
<td><input type="text" name="ProdName1"></td>
</tr><tr>
<td>Product Cost:</td>
<td><input type="text" name="ProdCost1"></td>
</tr><tr>
<td><br /></td>
</tr><tr>
<td><input type ="radio" name="Existing" value=0><b>Insert New Product Data</b></td>
</tr><tr>
<td>Product Name:</td>
<td><input type="text" name="ProdName2"></td>
</tr><tr>
<td>Product Cost:</td>
<td><input type="text" name="ProdCost2"></td>
</tr><tr>
<td>PId (opt, auto gen):</td>
<td><input type="text" name=ProdCode2></td>
</tr><tr>
<td>Description (optional):</td>
<td><textarea rows="5" cols="20" name="ProdDesc"></textarea></td>
</tr><tr>
<td><br /></td>
</tr>
</table>
<input type="submit" value="Submit Stock Alteration" name="submitAttempt">
</form>
</body>
</html>
<?php
echo "<br />";


/*The Button Has Been Pressed*/
if(isset($_POST['submitAttempt'])){


/*Set the variable values based on input, as existing or new product*/
$ProdQuan = $_POST['ProdQuan'];
$ExistProd = $_POST['Existing'];
if($ExistProd){
$ProdName = $_POST['ProdName1'];
$ProdCost = $_POST['ProdCost1'];
$ProdCode = $_POST['ProdCode1'];
$ProdName = str_replace("'","\'",$ProdName);
}
elseif(!$ExistProd){
$ProdName = $_POST['ProdName2'];
$ProdCost = $_POST['ProdCost2'];
$ProdCode = $_POST['ProdCode2'];
$ProdDesc = $_POST['ProdDesc'];
$ProdDesc = str_replace("'","\'",$ProdDesc);
$ProdName = str_replace("'","\'",$ProdName);
}


/*Error parameter initialisation*/
/*For not enough fields filled*/
$emptyError = 0;
/*For invalid data types*/
$validError = 0;
/*For invalid PId values*/
$PIDError = 0;

/*Check if required fields are empty*/
if(($ExistProd) && ($ProdQuan==NULL || ($ProdCode==NULL && ($ProdName == NULL || $ProdCost == NULL)))){
$emptyError = 1;
}
elseif((!$ExistProd) && ($ProdName == NULL || $ProdCost == NULL)){
$emptyError = 1;
}


/*Check if filled fields have valid data*/
if(!$emptyError){
if(!filter_var($ProdQuan, FILTER_VALIDATE_INT)){
$validError = 1;
}
elseif(
($ProdCode != NULL && !filter_var($ProdCode,FILTER_VALIDATE_INT)) 
|| ($ProdCost != NULL && !filter_var($ProdCost,FILTER_VALIDATE_FLOAT))
){
$validError = 1;
}


/*Check if a provided PID is acceptable*/
$PIDTestQuery="SELECT * from Products where PId = '$ProdCode'";
if(!$validError && $ProdCode != NULL){
$PIDTestResult=mysqli_query($conn,$PIDTestQuery);
$PIDArray = mysqli_fetch_array($PIDTestResult);
if(mysqli_num_rows($PIDTestResult) && !$ExistProd){
$PIDError = 1;
}
elseif(!mysqli_num_rows($PIDTestResult) && $ExistProd){
$PIDError = 1;
}
}


/*Find a PId from the given name if required*/
if($ExistProd && $ProdCode == NULL && !$emptyError && !$validError){
$GetPIDQuery = "SELECT PId FROM Products WHERE Name = '$ProdName' AND Cost = '$ProdCost'";
$GetPIDResult = mysqli_query($conn, $GetPIDQuery);
$GetPIDArray = mysqli_fetch_array($GetPIDResult);
$ProdCode = $GetPIDArray['PId'];
}


/*If there are no errors, modify or add product data*/
if(!$validError && !$emptyError && !$PIDError){


/*Find current stock level for existing products*/
if($ExistProd){
$GetStockQuery = "SELECT Stock, Name FROM Products WHERE PId = '$ProdCode'";
$GetStockResult = mysqli_query($conn,$GetStockQuery);
$GetStockArray = mysqli_fetch_array($GetStockResult);
$CurrentStock = $GetStockArray['Stock'];
$ProdName = $GetStockArray['Name'];
}


/*Add product data*/
if(!$ExistProd){
$AddProductQuery="INSERT INTO Products(Name, Cost, Description, Stock, PId)
VALUES('$ProdName','$ProdCost','$ProdDesc','$ProdQuan','$ProdCode')";
$ModificationResult = mysqli_query($conn, $AddProductQuery);
$ModType = 1;
}


/*Modify existing stock value*/
else{
$UpdateStock = $CurrentStock + $ProdQuan;
$ModifyStockQuery="UPDATE Products SET Stock = '$UpdateStock' WHERE PId = '$ProdCode'";
$ModificationResult = mysqli_query($conn, $ModifyStockQuery);
$ModType = 0;
}
if($ModificationResult){
echo "<br />Product stock table updated <font color=#32CD32>SUCCESSFULLY</font>";
if($ModType){
echo "<br />New product \"$ProdName\" added to Products table";
}
else{
echo "<br />$ProdName inventory increased by $ProdQuan to $UpdateStock";
}



}
}
}
/*Print any errors*/
if($emptyError){
echo "<br /><font color=#FF6347>Error: Please fill required fields</font>";
}
elseif($validError){
echo "<br /><font color=#FF6347>Error: Please ensure the entries are the appropriate data type</font>";
}
elseif($PIDError){
echo "<br /><font color=#FF6347>Error: Invalid product ID</font>";
}
}


/*Close the connection*/
mysqli_close($conn);
?>