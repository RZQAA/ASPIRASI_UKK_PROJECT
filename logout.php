<?php
session_start();
session_destroy();

// balik ke login
header("Location: index.php");
exit;
