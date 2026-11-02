<?php
session_start();
session_unset();
session_destroy();

header("Location: ../main/index.php"); // redirect to homepage
exit();

?>