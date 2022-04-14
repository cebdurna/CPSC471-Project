<html>
    <head>
        <title>Customer Page</title>
    </head>
    <body>
        <?php
                echo '<p style="text-align: left;">';
                echo '<a href="landingPage.php">Homepage</a>';
                echo '<a href="InvoicesCustomer.php">&nbsp; &nbsp; Invoices</a>';
                echo '<span style="float: right;">';
                echo '<a href="hotelcustomerp.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
                echo '<a href="Logout.php">Logout</a>';
                echo '</span>';
                echo '</p>';
            ?>
        <h2> <a href="booking.php"> Book Now </a> </h2>
        <h1>Bookings</h1>

        <?php
            echo "<table border='1'> <tr>
            <th>Booking Number</th>
            <th>Room Number</th>
            <th>Check In Date</th>
            <th>Check Out Date</th>
            <th>Credit Card Number</th>
            <th>Invoice ID</th>
            <th>Total</th>
            </tr>";

            $customerId = $_COOKIE["userID"];
            $url = "http://localhost:8000/customer/booking?customerID=" . $customerId;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            // stub response for thesting
            // $response = array(
            //     'bookings' => array(
            //         array("bookingNumber" => 'bookingNumber', "roomNumber" => 'roomNumber',
            //         "check_in_date" => 'check_in_date', "check_out_date" => 'check_out_date',
            //         "cc_number" => 'cc_number', "invoiceID" => 'invoiceID',"total" => 'total'),
            //     )
            // );
            // if($httpcode == 200){
                $response = json_decode($response);
                    foreach ($response as $booking){
                        $bookingNumber =  $booking->Number;
                        $roomNumber =  $booking->Room_Number;
                        $check_in_date =  $booking->Check_In_Date;
                        $check_out_date =  $booking->Check_Out_Date;
                        $cc_number =  $booking->CC_Number;
                        $invoiceID =  $booking->Invoice_ID;
                        $total = $booking->Total;
                        echo "<tr>
                        <td>" . $bookingNumber . "</td>
                        <td>" . $roomNumber . "</td>
                        <td>" . $check_in_date . "</td>
                        <td>" . $check_out_date . "</td>
                        <td>" . $cc_number . "</td>
                        <td>" . $invoiceID . "</td>
                        <td>" . $total . "</td>
                        </tr>";
                        
                    }
            // else{
            //       echo "<br><font color='red'>Unable to display services</font>" . 'HTTP code: ' . $httpcode;
            // }
                

        ?>
        <br>
        <!-- Update functionality to fetch booking matching ID where date is in past--> 
    </body>
</html>

