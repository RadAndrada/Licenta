<?php
session_start();

$eroare = ''; 

if(isset($_POST['username'], $_POST['parola'])) {
    require 'conectare.php';

    $stmt = $mysqli->prepare('SELECT id, parola FROM user WHERE username = ?');
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $parola);
        $stmt->fetch();
        
        if(password_verify($_POST['parola'], $parola)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $_POST['username'];
            header('Location: paginaprincipala.php'); 
            exit;
        } else {
            $eroare = 'Parolă incorectă!';
        }
    } else {
        $eroare = 'Nume de utilizator sau parolă incorecte!';
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        body {
            background-image: url('index.jpg');
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
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px #444;
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
            background-color: #45a049;
        }
        .error-mesaj {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>LogIn</h2>
        <?php
        if (!empty($eroare)) {
            echo '<div class="error-mesaj">' . $eroare . '</div>';
        }
        ?>
        <form action="login.php" method="POST">
            <div class="form">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form">
                <label for="password">Parolă:</label>
                <input type="password" id="password" name="parola" required>
            </div>
            <button type="submit" class="buton">LogIn</button>
        </form>
        <p>Nu ai încă un cont? <a href="register.php">Creează unul acum!</a></p>
    </div>
</body>
</html>
