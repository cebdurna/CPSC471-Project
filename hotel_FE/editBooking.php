<h1>Edit Booking</h1>
<?php

   $booking_no = $_POST['booking_no'];
   $check_in = $_POST['check_in'];
   $check_out = $_POST['check_out'];
   $room_no = $_POST['room_no'];
   $customerID = $_POST['customerID'];
   $invoiceID = $_POST['invoiceID'];
   $cc_no = $_POST['cc_no'];

    if (isset($_POST[updated]))
    {
        $curl = curl_init();
        
        $customerID = curl_escape($curl, $_COOKIE["userID"]);
        $bookingNo = curl_escape($curl, $_POST["booking_no"]);
        $roomNumber = curl_escape($curl, $_POST["room_no"]);
        $checkInDate = curl_escape($curl, $_POST["check_in"]);
        $checkOutDate = curl_escape($curl, $_POST["check_out"]);
        $ccNumber = curl_escape($curl, $_POST["cc_no"]);
        $ccName = curl_escape($curl, $_POST["cc_name"]);
        $ccExpiry = curl_escape($curl, $_POST["cc_expiry"]);
        $cvv = curl_escape($curl, $_POST["cvv"]);
        $ccAddress = curl_escape($curl, $_POST["cc_address"]);
        $ccPostal = curl_escape($curl, $_POST["postal_code"]);
        
        $url = "http://localhost:8000/employee/bookings?bookingNo={$bookingNo}&roomNumber={$roomNumber}&checkInDate={$checkInDate}&checkOutDate={$checkOutDate}&ccNumber={$ccNumber}&ccName={$ccName}&ccExpiry={$ccExpiry}&cvv={$cvv}&ccAddress={$ccAddress}&ccPostal={$ccPostal}";

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_PUT, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        
        echo "<form action='bookings.php' method='post'>
            <input type='submit' value='Return to bookings'>
        </form>";
    }
    else
    {
           echo "<form action='editBooking.php' method='post'>
                Booking Number: <input type='text' name='booking_no' value = '" . $booking_no . "' readonly><br>
                Customer ID: <input type='text' name='customerID' value = '" . $customerID . "' readonly><br>
                Invoice ID: <input type='text' name='invoiceID' value = '" . $invoiceID . "' readonly><br><br>
                Check In Date: <input type='date' name='check_in' value = '". $check_in ."'><br>
                Check Out Date: <input type='date' name='check_out' value = '" . $check_out ."'><br>
                Room Number: <input type='text' name='room_no' value = '" . $room_no . "'><br><br>
                CC Info Optional <br>
                Credit Card Number: <input type='text' name='cc_no' value = '" . $cc_no . "'><br>
                Name on card: <input type='text' name='cc_name' placeholder= 'John Doe'><br>
                Expiry Date: <input type='date' name='cc_expiry' placeholder= 'yyyy-mm-dd'><br>
                CVV: <input type='number' name='cvv' placeholder= '123'><br>
                CC Address: <input type='text' name='cc_address' placeholder = ' Queens Drive'><br>
                Postal Code: <input type='text' name='postal_code' placeholder = 'H7K 0P2'><br>
                <input type='hidden' name='updated' value = ''>
            <input type='submit' value='Update'>
        </form>";

    echo '<form action="bookings.php">
        <input type="submit" value="Cancel" />
        </form>';
    }

?>