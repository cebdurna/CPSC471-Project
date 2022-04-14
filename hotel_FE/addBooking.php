<?php
    $cust_id = $_POST['cust_id'];
    $room_no = $_POST['room_no'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $cc_no = $_POST['cc_no'];
    $cc_name = $_POST['cc_name'];
    $cc_expiry = $_POST['cc_expiry'];
    $cvv = $_POST['cvv'];
    $cc_address = $_POST['cc_address'];
    $postal_code = $_POST['postal_code'];

    $curl = curl_init();
    $customerID = curl_escape($curl, $cust_id);
    $room_no = curl_escape($curl, $room_no);
    $check_in = curl_escape($curl, $check_in);
    $check_out = curl_escape($curl, $check_out);
    $cc_no = curl_escape($curl, $cc_no);
    $cc_name = curl_escape($curl, $cc_name);
    $cc_expiry = curl_escape($curl, $cc_expiry);
    $cvv = curl_escape($curl, $cvv);
    $cc_address = curl_escape($curl, $cc_address);
    $postal_code = curl_escape($curl, $postal_code);

    $url = "http://localhost:8000/employee/bookings?customerID=$cust_id&roomNumber=$room_no&checkInDate=$check_in&checkOutDate=$check_out&ccNumber=$cc_no&ccName=$cc_name&ccExpiry=$cc_expiry&cvv=$cvv&ccAddress=$cc_address&ccPostal=$postal_code";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    header("Location: bookings.php");
    exit();
?>