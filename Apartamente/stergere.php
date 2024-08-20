<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ștergere Apartament</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../index.jpg'); 
            background-size: cover;           
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .mesaj {
            width: 30%;
            background-color: rgba(255, 255, 255, 0.8);
            text-align: center;
        }
        .mesaj div {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php
    global $mysqli;
    include("conectare.php");
    if (isset($_GET['cod']) && is_numeric($_GET['cod'])) {
        $cod = $_GET['cod'];
        if ($stmt = $mysqli->prepare("DELETE FROM apartamente WHERE cod = ? LIMIT 1")) {
            $stmt->bind_param("i", $cod);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "ERROR: Nu se poate executa delete.";
        }
        $mysqli->close();
    ?>
    <div class="mesaj">
        <div>Proprietate stearsă cu succes!</div>
        <div><a href="vizualizare.php">Vizualizează proprietățile</a></div>
    </div>
    <?php } ?>
</body>
</html>
