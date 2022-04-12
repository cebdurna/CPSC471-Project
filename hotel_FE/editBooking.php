<h1>Edit Booking</h1>
<?php

   $booking_no = $_POST['booking_no'];
   $check_in = $_POST['check_in'];
   $check_out = $_POST['check_out'];
   $room_no = $_POST['room_no'];
   $customerID = $_POST['customerID'];
   $invoiceID = $_POST['invoiceID'];
   $cc_no = $_POST['cc_no'];

   echo "<form action='editBooking.php' method='post'>
                Booking Number: <input type='text' name='booking_no' value = '" . $booking_no . "' readonly><br>
                Customer ID: <input type='text' name='customerID' value = '" . $customerID . "' readonly><br>
                Invoice ID: <input type='text' name='invoiceID' value = '" . $invoiceID . "' readonly><br><br>
                Check In Date: <input type='date' name='check_in' value = '". $check_in ."'><br>
                Check Out Date: <input type='date' name='check_out' value = '" . $check_out ."'><br>
                Room Number: <input type='text' name='room_no' value = '" . $room_no . "'><br>
                Credit Card Number: <input type='text' name='cc_no' value = '" . $cc_no . "'><br><br>
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

    if (isset($_POST[updated]))
    {
        $booking_no = $_POST['booking_no'];
        $room_no = $_POST['room_no'];
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $cc_no = $_POST['cc_no'];
        $cc_name = $_POST['cc_name'];
        $cc_expiry = $_POST['cc_expiry'];
        $cvv = $_POST['cvv'];
        $cc_address = $_POST['cc_address'];
        $postal_code = $_POST['postal_code'];

        $postData = ["customerID"=>$cust_id,
            "roomNumber"=> $room_no,
            "checkInDate"=>$check_in,
            "checkOutDate"=>$check_out,
            "ccNumber"=>$cc_no,
            "ccName"=>$cc_name,
            "ccExpiry"=> $cc_expiry,
            "cvv"=>$cvv,
            "ccAddress"=>$cc_address,
            "ccPostal"=>$postal_code,
        ];

        $url = "http://localhost:8000/employee/bookings";

        $data = array("a" => $a);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($data));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpcode == 200){
            header("Location: bookings.php");
            exit();
        }
        else {
            echo "<br><font color='red'>Unable to update booking, </font>" . 'HTTP code: ' . $httpcode;
        }
    }

?>