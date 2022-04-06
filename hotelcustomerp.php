<?php echo '<link rel="stylesheet" type="text/css" href="style.css" media="screen" />'; ?>
<html>
    <head>
        <title>Customer Page</title>
    </head>
    <body>
        <nav>
            <footer>
                <left>
                    <a href="./hotellandingpage.php">Homepage</a>
                </left>
                <right>
                    <a>Hello, Username</a>
                    <a href="./hotellogoutpage.php">Logout</a>
                </right>
            </footer>
        </nav>
        <h2> <a href="./placeHolder"> Book Now </a> </h2>
        <h1> Current Bookings</h1>
        <table style="border-collapse: collapse; width: 100%; height: 117px;" border = "1">
            <tr>
                <th scope="col"> Booking Number</th>
                <th scope="col"> Room Number</th>
                <th scope="col"> Check-In Date (MM-DD-YY)</th>
                <th scope="col"> Check-Out Date (MM-DD-YY)</th>
                <th scope="col"> Credit Card Number </th>
                <th scope="col"> Invoice ID </th>
                <th scope="col"> Total </th>
            </tr>
            <tr>
                <td style="text-align: center; height: 66px;">001525</td>
                <td style="text-align: center; height: 66px;">503</td>
                <td style="text-align: center; height: 66px;">07-12-22</td>
                <td style="text-align: center; height: 66px;">07-15-22</td>
                <td style="text-align: center; height: 66px;">4728***0978</td>
                <td style="text-align: center; height: 66px;"><a title="Placeholder" href="./Placeholder.php">105612</a></td>
                <td style="text-align: center; height: 66px;">$357.31</td>
            </tr>
        </table>
        <br>
        <h1> Past Bookings </h1>
        <table style="border-collapse: collapse; width: 100%; height: 117px;" border = "1">
            <tr>
                <th scope="col"> Booking Number</th>
                <th scope="col"> Room Number</th>
                <th scope="col"> Check-In Date (MM-DD-YY)</th>
                <th scope="col"> Check-Out Date (MM-DD-YY)</th>
                <th scope="col"> Credit Card Number </th>
                <th scope="col"> Invoice ID </th>
                <th scope="col"> Total </th>
                <th scope="col"> View </th>
            </tr>
            <tr>
                <td style="text-align: center; height: 66px;">012042</td>
                <td style="text-align: center; height: 66px;">207</td>
                <td style="text-align: center; height: 66px;">12-09-21</td>
                <td style="text-align: center; height: 66px;">12-15-21</td>
                <td style="text-align: center; height: 66px;">4743***5476</td>
                <td style="text-align: center; height: 66px;"><a title="Placeholder" href="./Placeholder.php">015372</a></td>
                <td style="text-align: center; height: 66px;">$957.15</td>
                <td style="text-align: center; height: 66px;"><a href="./view.php">View</a></td>
            </tr>
        </table> <br>
        <!-- Update functionality to fetch booking matching ID where date is in past--> 
    </body>
</html>

