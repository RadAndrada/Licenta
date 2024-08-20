<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location:login.php');
    exit;
}

if(isset($_POST['nume'], $_POST['email'], $_POST['username'], $_POST['parola'])) {
    require 'conectare.php';

    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $parola = $_POST['parola'];

    $hashed_password = password_hash($parola, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare('SELECT id FROM user WHERE username = ? OR email = ?');
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $eroare = 'Numele de utilizator sau email-ul este deja folosit.';
    } else {
        $stmt = $mysqli->prepare('INSERT INTO user (nume, email, username, parola) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $nume, $email, $username, $hashed_password);
        if($stmt->execute()) {
            header('Location:login.php');
            exit;
        }else {
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
    <title>Înregistrare</title>
    <style>
        body {
            background-image: url('index.jpg'); 
            background-size: cover; 
            background-position: center; 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px #444;
            width: 300px;
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
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .buton:hover {
            background-color: #45a049;;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Înregistrare</h2>
        <form action="register.php" method="POST">
            <div class="form">
                <label for="nume">Nume:</label>
                <input type="text" id="nume" name="nume" required>
            </div>
            <div class="form">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form">
                <label for="username">username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form">
                <label for="password">Parolă:</label>
                <input type="password" id="parola" name="parola" required>
            </div>
            
            <button type="submit"  class="buton">Înregistrare</button>
        </form>
        <p>Ai deja un cont? <a href="login.php">LogIn</a>.</p>
    </div>
</body>
</html>