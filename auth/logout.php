<?php
session_start(); 
session_unset(); 
session_destroy(); 
 
if (isset($_GET['reason']) && $_GET['reason'] == 'inactive') {
    header("Location: ../index.php?status=timedout");
} else {
    header("Location: ../index.php?status=loggedout");
}

header("Location: /medical-c2c-platform/index.php"); 
exit();
?>


