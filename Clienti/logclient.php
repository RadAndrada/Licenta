<?php
session_start();
$eroare = ''; 

if(isset($_POST['email'], $_POST['parola'])) {
    require 'conectare.php';

    $stmt = $mysqli->prepare('SELECT id, parola FROM client WHERE email = ?');
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $parola);
        $stmt->fetch();
        
        if(password_verify($_POST['parola'], $parola)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $_POST['email'];
            header('Location: ../panouclient.php'); 
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
    <title>LogIn</title>
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
            flex-direction: column;
        }
        .container {
            margin: 20px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            background-color: rgba(255, 255, 255, 0.58); 
            width: 350px;
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
            align-items: center;
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
            display: flex;
            justify-content: space-between; 
            margin-bottom: 10px;
        }
        .btn {
            background-color: #4CAF50;
            color: #444;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: auto;
            text-align: center; 
        }
        .btn:hover {
            background-color: #45a049;
        }
        .loginbuton {
            width: 100%;
            text-align: center; 
        }
        .login-btn {
            max-width: 150px;
        }
        .error-mesaj {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="buton">
    <a href="../index.php" class="btn" style="margin-right: 5px;">Despre</a>
    <a href="../portofoliu.php" class="btn" style="margin-left: 5px;">Portofoliu</a>
</div>

<div class="container">
    <p>Mai întâi vom începe prin a ne crea un cont sau ne vom loga dacă avem un cont existent.🍀</p>
    <h2>LogIn</h2>
    <?php
    if (!empty($eroare)) {
        echo '<div class="error-mesaj">' . $eroare . '</div>';
    }
    ?>
    <form action="logclient.php" method="POST">
        <div class="form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form">
            <label for="password">Parolă:</label>
            <input type="password" id="password" name="parola" required>
        </div>
        <div class="loginbuton">
            <button type="submit" class="btn loginbuton">LogIn</button>
        </div>
    </form>
    <p>Încă nu ai cont? <a href="../Clienti/registerclient.php">Creează unul!</a></p>
    </div>
</body>
</html>