<!-- Mark Soluiman
18039781
 -->

 <!-- This file is responsible for fetching data from the user, validate them and then sending these data 
to the sql server to be stored there. -->
<?php
require_once "../config/sqlSettings.php";

	// get info from client
	$customerName=$_POST["cname"];
	$phone=$_POST["phone"];
	$unitNumber=$_POST["unumber"];
	$streetNumber=$_POST["snumber"];
	$streetName=$_POST["stname"];
	$suburb=$_POST["sbname"];
	$destinationSuburb=$_POST["dsbname"];
	$date=$_POST["date"];
	$time=$_POST["time"];

	
	$bookingCode="BRN";
	$BRN="";
	$validInput=false;
	$timeNow=date('H:i');//time now in HH:MM format
	$today=date("Y-m-d"); //todays date in YYYY/MM/DD format
	$validTime=false;


	



//try to connect to database:
$dbConnect=@mysqli_connect($host,$user,$pswd,$dbName)
    or die ("<p> The database server is not available </p>");

echo "<p style= 'display:none'> Successfully connected to the database server </p>";





//Try to open the drk3695 database after a successful connection:

@mysqli_select_db($dbConnect,$dbName)
    or die ("<p> The database is not available </p>");

echo "<p style= 'display:none'> Successfully opened the database.</p>";

//Getting rid of any excess white spaces from the user's input:

$customerName=trim($customerName);
$phone=trim($phone);
$unitNumber=trim($unitNumber);
$streetNumber=trim($streetNumber);
$streetName=trim($streetName);
$suburb=trim($suburb);
$destinationSuburb=trim($destinationSuburb);



//Check for user's input validation:

	if(empty($customerName)||empty($phone)||empty($streetName)||empty($streetNumber)){
		$validInput=false;
	}

	else{

		$validInput=true;
	}

	if ( $timeNow>$time || $today>$date){
		
		$validTime=false;
	}
	else{
		$validTime=true;
	}

	




//if the user has fill every required field and has both valid date and time


if($validInput){
	
	if ($validTime){

	
	

//check if table exist:
$sqlString= "select * from $table";

$exist=mysqli_query($dbConnect,$sqlString);



//if table already exist, we insert data into the sql table named booking :

	if($exist){
		
		//Create the unique booking code:
		
		//to get the last record id from the booking table
		$sqlString= " select id from $table ORDER BY id DESC LIMIT 1;";
		$result=mysqli_query($dbConnect,$sqlString);
		$row=mysqli_fetch_row($result);
		
		//simple logic using if else statements to create the Booking Reference Number (BRN)
		if($row[0]+1<10){
			$BRN=$bookingCode."0000".($row[0]+1);
		}
		elseif($row[0]+1>=10 && $row[0]+1<100){
			$BRN=$bookingCode."000".($row[0]+1);
		}
		elseif($row[0]+1>=100 && $row[0]+1<1000){

			$BRN=$bookingCode."00".($row[0]+1);
		}
		elseif ($row[0]+1>=1000 && $row[0]+1<10000){
			$BRN=$bookingCode."0".($row[0]+1);
		}
		else{
			$BRN=$bookingCode. ($row[0]+1);
		}


		//Every booking will be unassigned initially.
		$sqlString = "INSERT INTO $table (`Booking_Reference`, `Customer_Name`, `Phone_Number`, `Unit_Number`, `Street_Number`, `Street_Name`, `Suburb`, `Destination_Suburb`, `Date`, `Time`,`Status`)
		VALUES ('$BRN', '$customerName', '$phone', '$unitNumber', '$streetNumber', '$streetName', '$suburb', '$destinationSuburb', '$date', '$time','Unassigned')";


		@mysqli_query($dbConnect,$sqlString)
or die ("<p >Unable to execute first the query.</p>"
. "<p>Error code " . mysqli_errno($dbConnect)
. ": " . mysqli_error($dbConnect) . "</p>");
	}

	// if the table doesn't already exist,we create it :
	else{
		$BRN="BRN00001";

		$sqlString="create table $table (
			id int primary key auto_increment,
			Booking_Reference varchar(8) unique,
			Customer_Name varchar(50) not null,
			Phone_Number varchar(12),
			Unit_Number varchar(5),
			Street_Number varchar(6) not null,
			Street_Name varchar(50) not null,
			Suburb varchar(50),
			Destination_Suburb varchar(50),
			Date date not null,
			Time time not null,
			Status varchar(50) not null

		)";

		@mysqli_query($dbConnect,$sqlString)
or die ("<p>Unable to execute the query.</p>"
. "<p>Error code " . mysqli_errno($dbConnect)
. ": " . mysqli_error($dbConnect) . "</p>");

			

$sqlString="INSERT INTO $table (`Booking_Reference`, `Customer_Name`, `Phone_Number`, `Unit_Number`, `Street_Number`, `Street_Name`, `Suburb`, `Destination_Suburb`, `Date`, `Time`,`Status`)
VALUES ('$BRN', '$customerName', '$phone', '$unitNumber', '$streetNumber', '$streetName', '$suburb', '$destinationSuburb', '$date', '$time','Unassigned')";

		@mysqli_query($dbConnect,$sqlString)
		or die ("<p>Unable to execute the second query.</p>"
		. "<p>Error code " . mysqli_errno($dbConnect)
		. ": " . mysqli_error($dbConnect) . "</p>");



	}
	
	//We reverse the date before displaying it

	$date=date("d/m/Y", strtotime($date));

	echo "<p><h2> Thank you for your booking</h2></p> ";
	echo " <p>Booking Reference number: $BRN  </p>";
	echo "<p> Pick up time : $time </p>";
	echo " <p> Pick up date: $date </p>";


}
//if the user tried to make a booking that is in the past. 
else{
	echo "Your booking cant be in the past, please change the date or time";
}
}
//if the user missed one or more of the required fields 
else{
	echo "Please fill the required fields";
}








	
	

?>
