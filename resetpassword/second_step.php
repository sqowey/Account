<?php 
    session_start();
    $displayname = $_SESSION['pw_reset_displayname'];
    $usermail= $_SESSION['pw_reset_usermail'];

    if (!isset($displayname) || !isset($usermail)) {
        header('Location: ./index.html?c=01');
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sqowey - Passwort</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="themeButton">
        <button id="themeToggleButton" onclick="toggleTheme()">Darkmode/Lightmode</button>
    </div>


    <!-- The Container in which the Error output is pasted -->
    <div id="errorOutputContainer">
    </div>

    <!-- container -->
    <div id="container">
        <h1>Passwort zurücksetzen</h1>
        <!-- The form that gets sent to the server -->
        <form id="pw_resetform_two" action="./pwreset.php" method="POST">
            <input class="disabled_form_field" type="text" name="username" placeholder="Nutzername*" id="username" value="<?=$displayname?>" disabled>
            <input class="disabled_form_field" type="text" name="mail" placeholder="E-Mail*" id="mail" value="<?=$usermail?>" disabled>
            <input type="text" name="code" placeholder="Verifikationscode*" id="code" required>
            <input type="password" name="new_password" placeholder="Neues Passwort*" id="code" required>
            <input type="password" name="new_password_repeat" placeholder="Neues Passwort wiederholen*" id="code" required>
            <p>Falls E-Mail und nutzername übereinstimmen, wird dir in Kürze eine Mail mit Code geschickt, den du nach Empfang hier eingeben musst</p>
            <input type="submit" id="submit" value="Code Absenden"> <br>
            <a href="./index.html" id="back">Zurück zur Nutzerdateneingabe</a>
        </form>

    </div>
    <script src="themes.js"></script>
    <script src="message_script.js"></script>
</body>

</html>