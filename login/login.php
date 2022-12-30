<?php 

// Check for input
if(!isset($_POST["username"]) || strlen($_POST["username"]) == 0) exit ("No username input");
if(!isset($_POST["password"]) || strlen($_POST["password"]) == 0) exit ("No password input");
$input_username = strtolower($_POST["username"]);
$input_password = $_POST["password"];

// Connect to the db
require_once("../../config.php");
$con = mysqli_connect(
    $config_account_db_server,
    $config_account_db_user,
    $config_account_db_password,
    $config_account_db_name
);
if(mysqli_connect_errno()) exit("Error with the Database");

// Check if account exists
if ($stmt = $con->prepare("SELECT username, displayname, id, salt, password, email, account_version FROM accounts WHERE username = ?")) {
    $stmt->bind_param('s', $input_username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) exit ("Account not found");
    $stmt->bind_result($username, $displayname, $id, $salt, $password_hash, $email, $account_version);
    $stmt->fetch();
}

// Verify password
if(password_verify($salt.$input_password, $password_hash) != 1) exit("Wrong password");

// Set session variables
session_start();
session_unset();
$_SESSION["user_id"] = $id;
$_SESSION["user_email"] = $email;
$_SESSION["user_usernam"] = $username;
$_SESSION["user_displayname"] = $displayname;

// Redirect


?>