<?php
session_start(); // Start the session

// Destroy the session to log the user out
session_destroy();

// Redirect to the root of the application
header('Location: /Networky/'); // Redirect to the home page
exit(); // Ensure no further code is executed after the redirect
?>
