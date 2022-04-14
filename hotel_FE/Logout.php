<?php
    setcookie("userID", "", time()-3600);
    setcookie("userName", "", time()-3600);
    header("Location: landingPage.php");
    exit();
?>