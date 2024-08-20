<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: registerclient.php');
    exit;
}

if(isset($_POST['nume'], $_POST['email'], $_POST['telefon'], $_POST['parola'])) {
    require 'conectare.php';

    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $parola = $_POST['parola'];

    $hashed_password = password_hash($parola, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare('SELECT id FROM client WHERE email = ?');
    $stmt->bind_param('s', $email); 
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $eroare = 'Email-ul este deja folosit.';
    } else {
        $stmt = $mysqli->prepare('INSERT INTO client (nume, email, telefon, parola) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $nume, $email, $telefon, $hashed_password);
        if($stmt->execute()) {
            header('Location: logclient.php');
            exit;
        } else {
            $eroare = 'Eroare la înregistrare. Vă rugăm să încercați din nou mai târziu.';
        }
    }
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare client</title>
    <style>
        body {
            background-image: url('../background.jpg');
            background-size: cover; 
            background-position: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            margin: 20px;
            padding : 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.58); 
            width: 350px;
            height: 450px;
        }
        h2 {
            text-align: center;
        }
        .form {
            margin-bottom: 20px;
        }
        .form label {
            display: block;
            margin-bottom: 5px;
        }
        .form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .buton {
            background-color: #4CAF50;
            color: #444;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .buton:hover {
            background-color: #4CAF49;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Înregistrare client </h2>
        <form action="registerclient.php" method="POST">
            <div class="form">
                <label for="nume">Nume complet:</label>
                <input type="text" id="nume" name="nume" required>
            </div>
            <div class="form">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form">
                <label for="telefon">Telefon:</label>
                <input type="telefon" id="telefon" name="telefon" required>
            </div>
            <div class="form">
                <label for="password">Parolă:</label>
                <input type="password" id="parola" name="parola" required>
            </div>
            
            <button type="submit"  class="buton">Înregistrare</button>
        </form>
        <p>Ai deja un cont? <a href="logclient.php">LogIn</a>.</p>
    </div>
</body>
</html>
