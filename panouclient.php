<?php
session_start();
$welcome_message = "Bine ai venit!";

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Principală</title>
    <style>
        body {
            background-image: url('background.jpg');
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
        .logare{
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .buton-container {
            margin-bottom: 50px; 
        }
        .container {
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px #444;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        .buton{
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .buton button, .b {
            background-color: rgba(255, 255, 255, 0.6);
            color: #444;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .buton button:hover, .b:hover {
            background-color: #e5e5e5;
        }
        
    </style>
</head>
<body>
<div class="logare">
    <div class="buton-container">
        <a href="index.php" class="b">Despre</a>
        <a href="portofoliu.php" class="b">Portofoliu</a>
        <a href="../Licenta/Clienti/logoutclient.php" class="b">LogOut</a>
    </div>
    <div class="container">
        <div>
            <h1><?php echo $welcome_message; ?></h1>
        </div>
        <div class="buton">
            <div>
                <button onclick="location.href='chatbot.php'">Agent virtual</button>
            </div>
            <div>
                <button onclick="location.href='programari.php'">Fă o programare!</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
