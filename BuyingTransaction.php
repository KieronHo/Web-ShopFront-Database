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
?>

<?php


/*Get data for the product drop down list*/
$GetProdList="SELECT Name, Description, PId, Cost FROM Products";
$ProdList = mysqli_query($conn, $GetProdList);


/*Create the entry form*/
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
<title>BuyingTransaction</title>
</head>
<body bgcolor="#f0e68c">
<h1>Welcome To Couch Potato&#8482 Online Store!</h1><hr>
<form method="post" action="BuyingTransaction.php">
<table>
<tr>
<td><b>Purchase Details</b></td>
</tr><tr>
<td>Select Product: </td>
<td><select name="ProdID">
<option value=""></option>
<?php


/*Make the Dynamically Loaded Drop down list*/
foreach($ProdList as $ProdID){
?>
<option value="<?php echo $ProdID['PId'];?>"><?php echo $ProdID['Name'];?>
 -- $<?php echo $ProdID['Cost'];?>
 -- <?php echo $ProdID['Description'];?></option>
<?php
}
?>
</select></td>
<?php


/*Make the rest of the data entry fields*/
?>
</tr><tr>
<td>Quantity:</td>
<td><input type="text" name = "ProdQuan"></td>
</tr><tr>
<td><br></td>
</tr><tr>
<td><b>Do you have a Customer ID?</b></td>
</tr><tr>
<td><input type="radio" name="RetCust" value=1><b>YES</b> (Please enter #CID): </td><td><input type="text" name="CustId"></td>
</tr><tr>
<td><input type="radio" name="RetCust" value=0 checked><b>NO</b> (For new customer or forgotten #CID):</td>
</tr><tr>
<td>First Name: </td><td><input type"text" name="FName"></td>
</tr><tr>
<td>Last Name: </td><td><input type="text" name="LName"></td>
</tr><tr>
<td>Phone Number: </td><td><input type="text" name="PNumber"></td>
</tr><tr>
<td>Address: </td><td><input type="text" name="Addr"></td>
</tr><tr>
<td><br></td>
</tr>
</table>
<input type="submit" value="Submit Purchase" name="submitAttempt">
</form>
</body>
</html>
<?php



/*If the submit button has been pressed before*/
if(isset($_POST['submitAttempt'])){
$ProdID = $_POST['ProdID'];
$ProdQuan = $_POST['ProdQuan'];
$RetCust = $_POST['RetCust'];
$FirstName = $_POST['FName'];
$LastName = $_POST['LName'];
$PhoneNumber = $_POST['PNumber'];
$Address = $_POST['Addr'];
$CustId = $_POST['CustId'];


/*Prevent errors occurring through use of punctuation*/
$FirstName = str_replace("'","\'",$FirstName);
$LastName = str_replace("'","\'",$LastName);
$PhoneNumber = str_replace("'","\'",$PhoneNumber);
$Address = str_replace("'","\'",$Address);



/*Get the chosen product name*/
$GetNameQuery = "SELECT Name FROM Products WHERE PId = '$ProdID'";
$GetNameResult = mysqli_query($conn,$GetNameQuery);
$GetNameArray = mysqli_fetch_array($GetNameResult);
$ProdName = $GetNameArray['Name'];


/*Initialise Error Checks*/
$ErrorProdDataBlank = 0;
$ErrorNewCustBlank = 0;
$ErrorInvalidQuan = 0;
$ErrorReturningID = 0;
$ErrorLowStock = 0;

/*Check for blank entries in the product details*/
if($ProdID == NULL || $ProdQuan == NULL){
$ErrorProdDataBlank = 1;
}


/*Check for blank entries in new customer essential fields*/
if(($FirstName == NULL || $LastName == NULL || $PhoneNumber == NULL || $Address == NULL) && !$RetCust){
$ErrorNewCustBlank = 1;
}


/*Check if customer already exists in the system*/
$CustForgot=0;
if(!$RetCust){
$CustExistQuery = "SELECT CId FROM Customers 
WHERE FirstName='$FirstName' AND LastName='$LastName' AND PhoneNumber='$PhoneNumber' AND Address='$Address'";
$CustExistResult = mysqli_query($conn,$CustExistQuery);
if(mysqli_num_rows($CustExistResult)){
$RetCust = 1;
$CustExistArray = mysqli_fetch_array($CustExistResult);
$CustId = $CustExistArray['CId'];
$CustForgot=1;
}
else{
}
}


/*Check if a returning customer entered their customer ID and its validity*/
if($RetCust == 1){
if($CustId == NULL || !filter_var($CustId,FILTER_VALIDATE_INT)){
$ErrorReturningID = 1;
}
else{
$CheckID = "SELECT * FROM Customers WHERE CId = '$CustId'";
$result = mysqli_query($conn,$CheckID);
$NumRows = mysqli_num_rows($result);
if(!$NumRows){
$ErrorReturningID = 1;
}
}
}


/*Check for invalid quantity amount*/
if($ProdQuan != NULL && (!filter_var($ProdQuan,FILTER_VALIDATE_INT))){
$ErrorInvalidQuan = 1;
}
elseif(filter_var($ProdQuan,FILTER_VALIDATE_INT) && $ProdQuan <=0 && $ProdQuan != NULL){
$ErrorInvalidQuan = 1;
}


/*Check if there is enough stock*/
if(!$ErrorInvalidQuan && !$ErrorProdDataBlank){
$StockCheck = "SELECT Stock FROM Products WHERE PId='$ProdID'";
$result = mysqli_query($conn, $StockCheck);
$resultArray = mysqli_fetch_array($result);
$InStock = $resultArray['Stock'];

if($InStock <= $ProdQuan){
$ErrorLowStock = 1;
}
}


/*Run Table Queries if there are no errors*/
if(!$ErrorProdDataBlank && !$ErrorNewCustBlank && !$ErrorInvalidQuan && !$ErrorReturningID && !$ErrorLowStock){


/*Add the new customer details*/
$AddCustomer = "INSERT INTO Customers (FirstName, LastName, PhoneNumber, Address) VALUES ('$FirstName', '$LastName', '$PhoneNumber', '$Address')";
if(!$RetCust){
$AddCustResult = mysqli_query($conn,$AddCustomer);
$GenCId = mysqli_insert_id($conn);
}


/*Find the relevant Customer ID*/
if($RetCust){
$CurrentCId = $CustId;
}
else{
$CurrentCId = $GenCId;
}


/*Get the Customer's Name*/
$GetNameQuery = "SELECT FirstName, LastName FROM Customers WHERE CId = '$CurrentCId'";
$GetNameResult = mysqli_query($conn,$GetNameQuery);
$GetNameArray = mysqli_fetch_array($GetNameResult);
$FirstName = $GetNameArray['FirstName'];
$LastName = $GetNameArray['LastName'];


/*Add the transaction to the Transactions table*/
$ProductCostQuery = "SELECT Cost FROM Products WHERE PId = '$ProdID'";
$ProdCostResult = mysqli_query($conn,$ProductCostQuery);
$ProdCostArray = mysqli_fetch_assoc($ProdCostResult);
$ProdCost = $ProdCostArray['Cost'];
$TotalCost = $ProdCost * $ProdQuan;
$AddTransaction = "INSERT INTO Transactions (PId, CId, NetCost, Number, Date)
 VALUES ('$ProdID', '$CurrentCId', '$TotalCost', '$ProdQuan', CURDATE())";
$AddTransactionResult = mysqli_query($conn, $AddTransaction);
$GeneratedTID = mysqli_insert_id($conn);


/*Reduce the Stock value in the Products table*/
$NewStockVal = $InStock - $ProdQuan;
$ReduceStock = "UPDATE Products SET Stock = '$NewStockVal' WHERE PId = '$ProdID'";
$ReduceStockResult = mysqli_query($conn,$ReduceStock);


/*If all goes well*/
if($AddTransactionResult && $ReduceStockResult){
echo "<br />Transaction <font color=#32CD32>SUCCESSFUL</font>
<br />Thankyou for your purchase<b> $FirstName $LastName! </b>";


/*Provide returning customer with forgotten CID*/
if($CustForgot){
echo "<br />Existing customer details found!
<br />Your returning <b>CUSTOMER ID</b> is <font color=#0066ff><b>$CurrentCId</b></font>";
}


/*Send the result of new customer query*/
if(!$RetCust){
if($AddCustResult){
echo "<br />Your returning <b>CUSTOMER ID</b> is <font color=#0066ff><b>$CurrentCId</b></font>
<br />Customer $FirstName $LastName recorded <font color=#32CD32>SUCCESSFULLY</font>";
}
else{
echo "<br /><font color=#FF1212>Warning</font>: Customer details not recorded";
}
}


/*Give a brief reciept of the purchase*/
echo "<br /><br />Purchase Reciept";
echo "<br />#TID: $GeneratedTID";
echo "<br />$ProdQuan X \"$ProdName\"";
echo "<br />Total Cost: \$$TotalCost";


}
else{
echo "<br /><font color=#FF1212>Warning Unknown Error</font>: Transaction processing UNSUCCESSFUL";
}
}


/*Print off any error messages*/
else
{

if($ErrorProdDataBlank){
echo "<br /><font color=#FF1212>Error: Incomplete product fields</font>";
}
elseif($ErrorInvalidQuan){
echo "<br /><font color=#FF1212>Error: Invalid quantity value</font>";
}

if($ErrorNewCustBlank){
echo "<br /><font color=#FF1212>Error: Customer details incomplete</font>";
}

if($ErrorReturningID){
echo "<br /><font color=#FF1212>Error: #CID not found</font>";
}


if($ErrorLowStock){
echo "<br /><font color=#FF1212>Error: Insufficient \"$ProdName\" in stock<br />Current inventory is $InStock</font>";
}


}
}

/*close the connection*/
mysqli_close($conn);
?>