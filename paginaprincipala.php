<?php
session_start();
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $welcome_message = "Bine ai revenit, $username!";
} else {
    $welcome_message = "Bine ai venit!";
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Principală</title>
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
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        .buton {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .buton button {
            background-color: #4CAF50;
            color: #444;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }
        .buton button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $welcome_message; ?></h1>
        <div class="buton" >
    <div>
        <button onclick="location.href='Agent/vizualizare.php'">Vizualizează Agent</button>
    </div>
    <div>
    
        <button onclick="location.href='Apartamente/vizualizare.php'">Vizualizare Portofoliu</button>
    </div>
    <div>
        <button onclick="location.href='Vanzari/vizualizare.php'">Vizualizare Vanzari</button>
    </div>
    <div>
        <button onclick="location.href='confirmareprogramari.php'">Programari</button>
    </div>
</div>
<a href="logout.php">LogOut</a>
</body>
</html>
