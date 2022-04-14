<!DOCTYPE html>
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
?>

<?php
    
    if (isset($_POST['editComplete'])) {
        $curl = curl_init();
        
        $serviceID = curl_escape($curl, $_POST["serviceID"]);
        $description = curl_escape($curl, $_POST["description"]);
        $hotelID = curl_escape($curl, $_COOKIE["hotelID"]);
        $price = curl_escape($curl, $_POST["price"]);
        
        $url = "http://localhost:8000/employee/services?service_id={$serviceID}&hotel_id={$hotelID}&description={$description}&price={$price}";

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_PUT, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        
        echo "<label> Successfully edited </label>
        <form action='Services.php' method='post'>
            <input type='submit' value='Return to services'>
        </form>
        ";
    }
    
    elseif (isset($_POST['edit'])) {
        $serviceID = $_POST['serviceID'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        
        echo "<form action='Services.php' method='post'>
            Service ID: <input type='text' name='serviceID' value='" . $serviceID . "' readonly><br>
            Description: <input type='text' name='description' value='" . $description . "'><br>
            Price: <input type='text' name='price' value='" . $price . "'><br>
            <input type='hidden' name='editComplete' value='true'>
            <input type='submit' value='Update'>
        </form>
        ";
    }
    
    elseif (isset($_POST['delete'])) {
        $curl = curl_init();
        
        $serviceID = curl_escape($curl, $_POST["serviceID"]);
        
        $url = "http://localhost:8000/employee/services?service_id={$serviceID}";
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        
        echo "<label> Successfully deleted </label>
        <form action='Services.php' method='post'>
            <input type='submit' value='Return to services'>
        </form>
        ";
    }
    
    elseif (isset($_POST['add'])) {
        
        if (empty($_POST['description']) || empty($_POST['price'])) {
            echo "<label>Please enter a description and price for the service you want to add.</label>";
        }
        else {
            $curl = curl_init();
            
            $description = curl_escape($curl, $_POST['description']);
            $price = curl_escape($curl, $_POST['price']);
            $hotelID = curl_escape($curl, $_COOKIE['hotelID']);
            
            $url = "http://localhost:8000/employee/services?hotel_id={$hotelID}&description={$description}&price={$price}";
            
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            
            echo "<label>Successfully added service</label>";
        }
        
        echo "
        <form action='Services.php' method='post'>
            <input type='submit' value='Return to services'>
        </form>
        ";
        
    }
    
    else {    
        echo "<h1>SERVICES</h1>";
        echo "<table border='1'> <tr>
        <th>Description</th>
        <th>Price</th>
        <th>Edit</th>
        <th>Delete</th>
        </tr>";
        $servicesURL = "http://localhost:8000/employee/services";
        $curl = curl_init($servicesUrl);
            curl_setopt($curl, CURLOPT_URL, $servicesURL);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $response = json_decode($response);
            foreach ($response as $service){
                $serviceID = $service->Service_ID;
                $description =  $service->Description;
                $price =  $service->Price;
                echo "<tr>
                <td>" . $description . "</td>
                <td>" . "$".$price . "</td>
                <td><form action='Services.php' method='post'>
                        <input type='submit' value='Edit'>
                        <input type='hidden' name='serviceID' value='" . $serviceID . "'>
                        <input type='hidden' name='description' value='" . $description . "'>
                        <input type='hidden' name='price' value='" . $price . "'>
                        <input type='hidden' name='edit' value='true'>
                </form></td>
                <td><form action='Services.php' method='post'>
                        <input type='hidden' name='serviceID' value='" . $serviceID . "'>
                        <input type='hidden' name='description' value='" . $description . "'>
                        <input type='hidden' name='price' value='" . $price . "'>
                        <input type='hidden' name='delete' value='true'>
                        <input type='submit' value='Delete'>
                </form></td>
                </tr>";
                }
                
        echo "</table><br><br>
        <table border='1'> <tr>
            <th>Description</th>
            <th>Price</th>
            <th>Add</th>
        </tr>
        <tr>
            <form action='Services.php' method='post'>
            <th><input type='text' name='description' value=''></th>
            <th><input type='number' name='price' value=''></th>
            <th><input type='submit' value='Add'></th>
            <input type='hidden' name='add' value=''>
            </form>
        </tr>
        </table>
        ";
    }
	// else{
	//       echo "<br><font color='red'>Unable to display services</font>" . 'HTTP code: ' . $httpcode;
	// }
		

?>

  	</body>
</html>