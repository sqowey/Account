<?php 

// Functions to generate ID
function generateIDsection($length){
    $id_charset = "abcdefghijklmnopqrstuvwxyz123465789";
    $generated = "";
    for ($i=0; $i < $length; $i++) { 
        $new_char = substr($id_charset, mt_rand(0, strlen($id_charset)),1);
        $generated .= $new_char;
    }
    return $generated;
}
function generateID(){
    $gen_id = generateIDsection(8) . "-";
    $gen_id .= generateIDsection(4) . "-";
    $gen_id .= generateIDsection(4) . "-";
    $gen_id .= generateIDsection(4) . "-";
    $gen_id .= generateIDsection(12);
    return $gen_id;
}

// Check input
if(!isset($_POST["username"]) || strlen($_POST["username"]) == 0) exit ("No username input");
if(!isset($_POST["password"]) || strlen($_POST["password"]) == 0) exit ("No password input");
if(!isset($_POST["password_repeat"]) || strlen($_POST["password_repeat"]) == 0) exit ("No repeated password input");
if(!isset($_POST["email"]) || strlen($_POST["email"]) == 0) exit ("No email input");
if($_POST["password"] != $_POST["password_repeat"]) exit ("Passwords do not match");
$input_displayname = $_POST["username"];
$input_username = strtolower($input_displayname);
$input_password = $_POST["password"];
$input_email = $_POST["email"];
if(!filter_var($input_email, FILTER_VALIDATE_EMAIL)) exit ("Email not recognized as email");
if(!preg_match("/^[a-zA-Z0-9_]{4,12}$/", $input_displayname)) exit ("Displayname not valid");
if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,64}$/", $input_password)) exit ("Invalid password");


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
if ($stmt = $con->prepare("SELECT username FROM accounts WHERE username = ?")) {
    $stmt->bind_param('s', $input_username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows != 0) exit ("An account with that username already exists");
    $stmt->close();
}
if ($stmt = $con->prepare("SELECT email FROM accounts WHERE email = ?")) {
    $stmt->bind_param('s', $input_email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 4) exit ("You already own to many Accounts!");
    $stmt->close();
}

// Generate ID
while (true) {
    $generated_id = generateID();
    if($stmt = $con->prepare("SELECT id FROM accounts WHERE id = ?")) {
        $stmt->bind_param('s', $generated_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) break;
    }
}

// Insert into DB
$hashed_pw = password_hash($input_password, PASSWORD_BCRYPT);
if($stmt = $con->prepare("INSERT INTO accounts (id, username, displayname, email, password, account_version) VALUES (?, ?, ?, ?, ?, 3)")){    
    $stmt->bind_param('sssss', $generated_id, $input_username, $input_displayname, $input_email, $hashed_pw);
    $stmt->execute();
    $stmt->close();
    $con->close();
}


// Redirect
header("Location: /login");

?>