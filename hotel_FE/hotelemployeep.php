<?php echo '<link rel="stylesheet" type="text/css" href="style.css" media="screen" />'; ?>
<html>
    <head>
        <title>Employee Page</title>
    </head>
    <body>
        <?php
            echo '<p style="text-align: left;">';
            echo '<a href="landingPage.php">Landing Page</a>';
            echo '<span style="float: right;">';
            echo '<a href="hotelemployeep.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
            echo '<a href="Logout.php">Logout</a>';
            echo '</span>';
            echo '</p>';
        ?>
        <h1> Employee Dashboard </h1> <!-- Fetch hotel data from database of employee?-->
        <h3> View/Edit </h3>
        <li> <a href="./hotelrooms.php">Room Information </a> </li>
        <li> <a href="./bookings.php">Bookings </a> </li>
        <li> <a href="./Services.php">Available Services </a> </li>
        <li> <a href="./Invoices.php">Invoices </a> </li>
        <br>
    </body>
</html>

