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
$identifying_param = "username";
if(str_contains($input_username,"@")) $identifying_param = "email";
if ($stmt = $con->prepare("SELECT username, displayname, id, password, email, account_version FROM ".$config_account_db_account_table." WHERE ".$identifying_param." = ?")) {
    $stmt->bind_param('s', $input_username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) exit ("Account not found");
    if ($stmt->num_rows > 1) exit ("Too many accounts found!<br>Please log in using your username");
    $stmt->bind_result($username, $displayname, $id, $password_hash, $email, $account_version);
    $stmt->fetch();
}

// Verify password
if(password_verify($input_password, $password_hash) != 1) exit("Wrong password");

// Set session variables
session_start();
session_unset();
$_SESSION["user_id"] = $id;
$_SESSION["user_email"] = $email;
$_SESSION["user_usernam"] = $username;
$_SESSION["user_displayname"] = $displayname;

// Redirect
if(!isset($_GET["redirect"])) {
    header("Location: https://app.sqowey.de");
    exit();
}
$redirection = $_GET["redirection"];
if(str_ends_with(parse_url($redirection, PHP_URL_HOST), "sqowey.de"));
if(str_starts_with($redirection, ".")){
    header("Location: ".$redirection);
    exit();
}
exit ("Redirection failed!");

?>