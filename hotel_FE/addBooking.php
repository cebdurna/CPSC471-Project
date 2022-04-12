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

    $post = ["customerID"=>$cust_id,
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
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    header("Location: bookings.php");
    exit();
?>