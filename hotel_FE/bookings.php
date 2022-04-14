<html>
	<body>
		<?php
            echo '<p style="text-align: left;">';
            echo '<a href="landingPage.php">Landing Page</a>';
			echo '&emsp;&emsp;<a href="hotelemployeep.php">Employee Dashboard</a>';
            echo '<span style="float: right;">';
            echo '<a href="hotelemployeep.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
            echo '<a href="Logout.php">Logout</a>';
            echo '</span>';
            echo '</p>';

        
    if (isset($_POST['delete'])) {
        $curl = curl_init();
        
        $book_no = curl_escape($curl, $_POST["book_no"]);
        $hotel_id = curl_escape($curl, $_POST["hotel_id"]);
        
        $url = "http://localhost:8000/employee/bookings?book_no={$book_no}&hotel_id={$hotel_id}";
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        
        echo "<label> Successfully deleted </label>
        <form action='bookings.php' method='post'>
            <input type='submit' value='Return to services'>
        </form>
        ";
    }
    
    elseif (!isset($_POST['startdate'])) {
        echo "
        <p>Create a booking for a customer:</p>
	
        <form action=\"bookings.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            Customer ID: <input type=\"string\" name=\"customerID\"><br>
            <br>
            <input type=\"submit\" value=\"Check Availability\">
        </form>
        
        ";
    }
    
	elseif(isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['customerID']) && !isset($_POST['roomnumber']))
	{
        echo "
        <p>Create a booking for a customer:</p>
	
        <form action=\"bookings.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            Customer ID: <input type=\"string\" name=\"customerID\"><br>
            <br>
            <input type=\"submit\" value=\"Check Availability\">
        </form>
        
        ";
		$startdate = $_POST["startdate"];
		$enddate = $_POST["enddate"];
        $customerID = $_POST['customerID'];
        
        if (strtotime($startdate) >= strtotime($enddate)) {
            echo "<label>Please ensure your end date comes after your start date</label>";
        }
        elseif (empty($_POST['customerID'])){
            echo "<label>Please enter a customerID";
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
            <td>" . "$".$rate . "</td>
            <td>
                <form action=\"bookings.php\" method=\"post\">
                    <input type=\"submit\" value=\"Select\"/>
                    <input type=\"hidden\" name=\"roomnumber\" value=\"" . $roomNumber . "\" />
                    <input type=\"hidden\" name=\"startdate\" value=\"" . $startdate . "\" />
                    <input type=\"hidden\" name=\"enddate\" value=\"" . $enddate . "\" />
                    <input type=\"hidden\" name=\"rate\" value=\"" . $rate . "\" />
                    <input type=\"hidden\" name=\"customerID\" value=\"" . $customerID . "\" />
                </form>
            </td>
            </tr>";	
			} echo "</table>";
            echo "<br><br>";
        }
		
	}
    elseif (isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['roomnumber']))
    {
        if (isset($_POST['cc#']) && isset($_POST['cardName']) && isset($_POST['expiry']) && isset($_POST['cvv']) && isset($_POST['street']) && isset($_POST['postalcode']) && isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['roomnumber'])) {
            if (empty($_POST['cc#']) || empty($_POST['cardName']) || empty($_POST['expiry']) || empty($_POST['cvv']) || empty($_POST['street']) || empty($_POST['postalcode']) || empty($_POST['startdate']) || empty($_POST['enddate']) || empty($_POST['roomnumber'])) {
                        echo "
        <p>Please select a check-in, check-out date, and a customer ID for your reservation:</p>
	
        <form action=\"bookings.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            Customer ID: <input type=\"string\" name=\"customerID\"><br>
            <br>
            <input type=\"submit\" value=\"Check Availability\">
        </form>
        
        ";
                echo "<label>One or more of the credit card fields were not entered.  Please enter in values for all of the credit card fields.</label>";
            }
            else {
                $curl = curl_init();
                
                $customerID = curl_escape($curl, $_POST["customerID"]);
                $roomNumber = curl_escape($curl, $_POST["roomnumber"]);
                $checkInDate = curl_escape($curl, $_POST["startdate"]);
                $checkOutDate = curl_escape($curl, $_POST["enddate"]);
                $ccNumber = curl_escape($curl, $_POST["cc#"]);
                $ccName = curl_escape($curl, $_POST["cardName"]);
                $ccExpiry = curl_escape($curl, $_POST["expiry"]);
                $cvv = curl_escape($curl, $_POST["cvv"]);
                $ccAddress = curl_escape($curl, $_POST["street"]);
                $ccPostal = curl_escape($curl, $_POST["postalcode"]);
                
                $url = "http://localhost:8000/employee/bookings?customerID={$customerID}&roomNumber={$roomNumber}&checkInDate={$checkInDate}&checkOutDate={$checkOutDate}&ccNumber={$ccNumber}&ccName={$ccName}&ccExpiry={$ccExpiry}&cvv={$cvv}&ccAddress={$ccAddress}&ccPostal={$ccPostal}";

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                curl_close($curl);
                
                echo "<label>Successfully created booking.  Click the button below to create another booking, or return to the home page.</label>";
                echo "  <form action=\"bookings.php\" method=\"post\">
                            <input type=\"submit\" value=\"Create another booking\">
                        </form>";
            }
        }
        else {
                    echo "
        <p>Please select a check-in, check-out date, and a customer ID for your reservation:</p>
	
        <form action=\"bookings.php\" method=\"post\">
            Check-in Date: <input type=\"date\" name=\"startdate\" placeholder = \"yyyy-mm-dd\"><br>
            Check-out Date: <input type=\"date\" name=\"enddate\" placeholder = \"yyyy-mm-dd\"><br>
            Customer ID: <input type=\"string\" name=\"customerID\"><br>
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
            <form action=\"bookings.php\" method=\"post\">
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
            <input type=\"hidden\" name=\"customerID\" value=\"" . $_POST['customerID'] . "\" />
            <input type='hidden' name='add' value='true'>
            <button type=\"submit\">Complete Booking</button>
            </form>";
        }
        
    }
    if (!isset($_POST['delete']) && !isset($_POST['add'])) {
            $hotelID = $_COOKIE['hotelID'];
			echo "<table border='1'> <tr>
            <th>Booking Number</th>
            <th>Check In Date</th>
            <th>Check Out Date</th>
            <th>Room Number</th>
            <th>Customer ID</th>
            <th>Invoice ID</th>
			<th>Credit Card Number</th>
            <th>Edit</th>
            <th>Delete</th>
            </tr>";
            $curl = curl_init();
            
            $url = "http://localhost:8000/employee/bookings?hotelID={$hotelID}";
            
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_GET, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
                
            // if($httpcode == 200){
                $response = json_decode($response);
                    foreach ($response as $booking){
                        $booking_no =  $booking->Number;
                        $check_in =  $booking->Check_In_Date;
                        $check_out =  $booking->Check_Out_Date;
                        $room_no =  $booking->Room_Number;
                        $customerIDD =  $booking->Customer_ID;
                        $invoiceID =  $booking->Invoice_ID;
                        $cc_no =  $booking->CC_Number;
                        echo "<tr>
                        <td>" . $booking_no . "</td>
                        <td>" . $check_in . "</td>
                        <td>" . $check_out . "</td>
                        <td>" . $room_no . "</td>
                        <td>" . $customerIDD . "</td>
                        <td>" . $invoiceID . "</td>
                        <td>" . $cc_no . "</td>
						<td>
						<form action='editBooking.php' method='post'>
							<input type='hidden' name='booking_no' value = '".$booking_no."'>
							<input type='hidden' name='check_in' value = '". $check_in ."'>
							<input type='hidden' name='check_out' value = '" . $check_out ."'>
							<input type='hidden' name='room_no' value = '" . $room_no . "'>
							<input type='hidden' name='customerID' value = '" . $customerIDD . "'>
							<input type='hidden' name='invoiceID' value = '" . $invoiceID . "'>
							<input type='hidden' name='cc_no' value = '" . $cc_no . "'>
							<input type='submit' value='Edit'>
						</form>
						</td>
                        <td>
                        <form action='bookings.php' method='post'>
                            <input type='hidden' name='book_no' value='" . $booking_no . "'>
                            <input type='hidden' name='hotel_id' value='" . $_COOKIE['hotelID'] . "'>
                            <input type='hidden' name='delete' value='true'>
                            <input type='submit' value='Delete'>
                        </form>
                        </td>
                        </tr>";
                    } echo "</table>";
            // else{
            //       echo "<br><font color='red'>Unable to display services</font>" . 'HTTP code: ' . $httpcode;
            // }

			
    }
        ?>
		
  	</body>
</html>