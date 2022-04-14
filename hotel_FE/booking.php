<!DOCTYPE html>
<html>

<head>
  <title>Booking</title>
</head>

<body>

	<?php
        echo "<a href='landingPage.php'>Homepage</a>";
        echo '&emsp;&emsp;<a href="hotelcustomerp.php">Customer Page</a>';
        echo "<span style='float: right;'>";
        echo "<a href='hotelcustomerp.php'>Logged in as ". $_COOKIE['userName'] . "</a>&nbsp; &nbsp; &nbsp";
        echo "<a href='Logout.php'>Logout</a>";
        echo "</span>";
        echo "<hr />";
        echo "<H2>Customer Room Booking</H2>";
        echo"<br></br>
        </br>";
    if (!isset($_POST['startdate'])) {
        echo "
        <p>Please select a check-in and check-out date for your reservation:</p>
	
        <form action=\"booking.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            <br>
            <input type=\"submit\" value=\"Check Availability\">
        </form>
        
        ";
    }
	elseif(isset($_POST['startdate']) && isset($_POST['enddate']) && !isset($_POST['roomnumber']))
	{
        echo "
        <p>Please select a check-in and check-out date for your reservation:</p>
	
        <form action=\"booking.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            <br>
            <input type=\"submit\" value=\"Check Availability\">
        </form>
        
        ";
		$startdate = $_POST["startdate"];
		$enddate = $_POST["enddate"];
        
        if (strtotime($startdate) >= strtotime($enddate)) {
            echo "<label>Please ensure your end date comes after your start date</label>";
        }
        else {
        echo "<label>The following rooms are available during the selected time period:</label>";
        echo "<table border='1'> <tr>
		<th>Room Number</th>
		<th>Room Type</th>
		<th>Number of Beds</th>
		<th>Floor</th>
		<th>Furniture</th>
		<th>Capacity</th>
		<th>Orientation</th>
		<th>Rate</th>
        <th>Select</th>
		</tr>";
		
		$url = "http://localhost:8000/customer/availability?checkInDate=" . $startdate ."&checkOutDate=" . $enddate;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_GET, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
        curl_close($curl);
		//$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		// stub response for thesting
        /*
		$response = array(
			'rooms' => array(
				array("roomNumber" => 'roomNumber', "type" => 'type',
				"beds" => 'beds', "floor" => 'floor',
				"furniture" => 'furniture', "capacity" => 'capacity',
				"orientation" => 'orientation', 'rate' => 'rate'),
			)
		);
        */

        $response = json_decode($response);
        foreach ($response as $room){
            $roomNumber =  $room->Number;
            $type =  $room->Type;
            $beds =  $room->Beds;
            $floor =  $room->Floor;
            $furniture =  $room->Furniture;
            $capacity =  $room->Capacity;
            $orientation =  $room->Orientation;
            $rate =  $room->Rate;
            echo "<tr>
            <td>" . $roomNumber . "</td>
            <td>" . $type . "</td>
            <td>" . $beds . "</td>
            <td>" . $floor . "</td>
            <td>" . $furniture . "</td>
            <td>" . $capacity . "</td>
            <td>" . $orientation . "</td>
            <td>" . "$" . $rate . "</td>
            <td>
                <form action=\"booking.php\" method=\"post\">
                    <input type=\"submit\" value=\"Select\"/>
                    <input type=\"hidden\" name=\"roomnumber\" value=\"" . $roomNumber . "\" />
                    <input type=\"hidden\" name=\"startdate\" value=\"" . $startdate . "\" />
                    <input type=\"hidden\" name=\"enddate\" value=\"" . $enddate . "\" />
                    <input type=\"hidden\" name=\"rate\" value=\"" . $rate . "\" />
                </form>
            </td>
            </tr>";
				
			}
        }
		
	}
    elseif (isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['roomnumber']))
    {
        if (isset($_POST['cc#']) && isset($_POST['cardName']) && isset($_POST['expiry']) && isset($_POST['cvv']) && isset($_POST['street']) && isset($_POST['postalcode']) && isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['roomnumber'])) {
            if (empty($_POST['cc#']) || empty($_POST['cardName']) || empty($_POST['expiry']) || empty($_POST['cvv']) || empty($_POST['street']) || empty($_POST['postalcode']) || empty($_POST['startdate']) || empty($_POST['enddate']) || empty($_POST['roomnumber'])) {
                        echo "
        <p>Please select a check-in and check-out date for your reservation:</p>
	
        <form action=\"booking.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            <br>
            <input type=\"submit\" value=\"Check Availability\">
        </form>
        
        ";
                echo "<label>One or more of the credit card fields were not entered.  Please enter in values for all of the credit card fields.</label>";
            }
            else {
                //$url = urlencode("http://localhost:8000/customer/booking?customerID=1&roomNumber=101&checkInDate=2022-01-01&checkOutDate=2022-01-02&ccNumber=1111 1111 1111 1111&ccName=John Doe&ccExpiry=2027-07-07&cvv=111&ccAddress=123 Test Drive&ccPostal=A1A 1A1");
                $curl = curl_init();
                
                $customerID = curl_escape($curl, $_COOKIE["userID"]);
                $roomNumber = curl_escape($curl, $_POST["roomnumber"]);
                $checkInDate = curl_escape($curl, $_POST["startdate"]);
                $checkOutDate = curl_escape($curl, $_POST["enddate"]);
                $ccNumber = curl_escape($curl, $_POST["cc#"]);
                $ccName = curl_escape($curl, $_POST["cardName"]);
                $ccExpiry = curl_escape($curl, $_POST["expiry"]);
                $cvv = curl_escape($curl, $_POST["cvv"]);
                $ccAddress = curl_escape($curl, $_POST["street"]);
                $ccPostal = curl_escape($curl, $_POST["postalcode"]);
                
                $url = "http://localhost:8000/customer/booking?customerID={$customerID}&roomNumber={$roomNumber}&checkInDate={$checkInDate}&checkOutDate={$checkOutDate}&ccNumber={$ccNumber}&ccName={$ccName}&ccExpiry={$ccExpiry}&cvv={$cvv}&ccAddress={$ccAddress}&ccPostal={$ccPostal}";

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                curl_close($curl);
                
                echo "<label>Successfully created booking.  Click the button below to create another booking, or return to the home page.</label>";
                echo "  <form action=\"booking.php\" method=\"post\">
                            <input type=\"submit\" value=\"Create another booking\">
                        </form>";
            }
        }
        else {
                    echo "
        <p>Please select a check-in and check-out date for your reservation:</p>
	
        <form action=\"booking.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            <br>
            <input type=\"submit\" value=\"Check Availability\">
        </form>
        
        ";
            $startdate = strtotime($_POST["startdate"]);
            $enddate = strtotime($_POST["enddate"]);
            $rate = $_POST["rate"];
            
            $datediff = $enddate - $startdate;
            
            $datediff = round($datediff / (60 * 60 * 24));
            
            echo "<label> Room " . $_POST['roomnumber'] . " from " . $_POST['startdate'] . " to " . $_POST['enddate'] . "</label><br>";
            
            echo "<label>Total price: $" . $datediff * $rate . "</label><br>";
            
            echo "<label>Please enter credit card information to complete your booking:</label><br>";
            
            echo "
            <form action=\"booking.php\" method=\"post\">
            Credit Card #: <input type=\"text\" name=\"cc#\" placeholder = \"XXXX XXXX XXXX XXXX\"><br>
            Cardholder's Full Name: <input type=\"text\" name=\"cardName\" placeholder = \"John Smith\"><br>
            Expiry date: <input type=\"date\" name=\"expiry\" placeholder = \"yyyy-mm-dd\"><br>
            CVV: <input type=\"text\" name=\"cvv\" placeholder = \"XXX\"><br>
            Billing Address Street: <input type=\"text\" name=\"street\" placeholder = \"\"><br>
            Billing Address Postal Code: <input type=\"text\" name=\"postalcode\" placeholder = \"\"><br>
            <input type=\"hidden\" name=\"roomnumber\" value=\"" . $_POST['roomnumber'] . "\" />
            <input type=\"hidden\" name=\"startdate\" value=\"" . $_POST['startdate'] . "\" />
            <input type=\"hidden\" name=\"enddate\" value=\"" . $_POST['enddate'] . "\" />
            <input type=\"hidden\" name=\"rate\" value=\"" . $rate . "\" />
            <button type=\"submit\">Complete Booking</button>
            </form>";
        }
        
    }
	?>	

</body>

</html