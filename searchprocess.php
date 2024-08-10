<!-- Mark Soluiman
18039781
 -->

  <!-- This file is responsible for fetching data from the admin user, validate them and then sending these data 
to the sql server to be processed there. This file is also responsible for showing the data that was 
required from the user in an html table -->

<?php

require_once "../config/sqlSettings.php";
$bookingSearch = $_POST["bsearch"];
$valid = false;

//try to connect to database:
($dbConnect = @mysqli_connect($host, $user, $pswd, $dbName)) or
    die("<p> The database server is not available </p>");

echo "<p style= 'display:none'> Successfully connected to the database server </p>";

//Try to open the drk3695 database after a successful connection:

@mysqli_select_db($dbConnect, $dbName) or
    die("<p> The database is not available </p>");

echo "<p style= 'display:none'> Successfully opened the database.</p>";

//Getting rid of any excess white spaces from the user's input:

$bookingSearch = trim($bookingSearch);

//Check for the booking reference format validity:

$pattern = '/^BRN\d{5}$/'; //BRN00001 for example

if (preg_match($pattern, $bookingSearch) || empty($bookingSearch)) {
    $valid = true;
} else {
    $valid = false;
}

//if the BRN format is valid:

if ($valid) {
    //if the booking search is not empty:
    if (!empty($bookingSearch)) {
        $sqlString = "select Booking_Reference , Customer_Name , Phone_Number,Suburb ,Destination_Suburb , concat(date_format(Date, '%d/%m/%Y') ,' ',Time) ,Status from $table
            where Booking_Reference like '$bookingSearch'";
        ($results = @mysqli_query($dbConnect, $sqlString)) or
            die(
                "<p>Unable to execute the first query.</p>" .
                    "<p>Error code " .
                    mysqli_errno($dbConnect) .
                    ": " .
                    mysqli_error($dbConnect) .
                    "</p>"
            );

        $row = mysqli_fetch_row($results);
        
        //if there was no booking under the searched BRN:
        if ($row[0]==""){
            echo "No booking was found. Try to enter another BRN";
        }
        //else we display the results in a table
        else{
        
        //Displaying the data in an html table
        echo "<table border=1>
            <tr><th> BRN </th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Pick Up Suburb</th>
            <th>Destination Suburb </th>
            <th> Pick Up Date and Time</th>
            <th>Status</th>
            <th>Assign</th></tr>
        ";

        
        while ($row) {
            echo "<tr> <td> {$row[0]}</td>";
            echo "<td>{$row[1]}</td>";
            echo "<td>{$row[2]}</td>";
            echo "<td>{$row[3]}</td>";
            echo "<td>{$row[4]}</td>";
            echo "<td>{$row[5]}</td>";
            echo "<td id='assigntext'>{$row[6]}</td>";
            echo "<td><input type='button' value='Assign' id='assign'></td> </tr>";
            $row = mysqli_fetch_row($results);
        }
        echo "</table>";
    }
}

    //if the search is empty:
    else {
        $sqlString = "select Booking_Reference , Customer_Name , Phone_Number,Suburb ,Destination_Suburb , concat(date_format(Date, '%d/%m/%Y') ,' ',Time),Status from $table where TIME >= TIME( NOW()) - INTERVAL 2 HOUR
            AND TIME <= TIME( NOW())
            and Date(Date)=Date(Now()) ;";//getting the bookings that happened in the last 2 hours

        ($results = @mysqli_query($dbConnect, $sqlString)) or
            die(
                "<p>Unable to execute the first query.</p>" .
                    "<p>Error code " .
                    mysqli_errno($dbConnect) .
                    ": " .
                    mysqli_error($dbConnect) .
                    "</p>"
            );

        $row = mysqli_fetch_row($results);

        //if there was no booking happened in the last 2 hours:
            if ($row[0]==""){
                echo "No booking was placed in the last 2 hours";
            }
            //else we display the results in a table:
            else{

            

        //Displaying the data in an html table
        echo "<table border=1>
            <tr><th> BRN </th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Pick Up Suburb</th>
            <th>Destination Suburb </th>
            <th> Pick Up Date and Time</th>
            <th>Status</th>
            <th>Assign</th></tr>
        
        ";

        while ($row) {
            echo "<tr> <td> {$row[0]}</td>";
            echo "<td>{$row[1]}</td>";
            echo "<td>{$row[2]}</td>";
            echo "<td>{$row[3]}</td>";
            echo "<td>{$row[4]}</td>";
            echo "<td>{$row[5]}</td>";
            echo "<td>{$row[6]}</td>";
            echo "<td><input type='button' value='Assign' id='assign'></td> </tr>";
            $row = mysqli_fetch_row($results);
        }
        echo "</table>";
    }
}
}
//if the BRN format is not valid, show this to the user as an error message
else {
    echo " <p>Not a valid Booking Reference Number, Please enter a BRN in this format: BRN00001.  </p>";
}

?>
