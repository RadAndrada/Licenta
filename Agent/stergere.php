<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ștergere Agent</title>
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
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        if ($stmt = $mysqli->prepare("DELETE FROM agent WHERE id = ? LIMIT 1")) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "ERROR: Nu se poate executa delete.";
        }
        $mysqli->close();
    ?>
    <div class="mesaj">
        <div>Agentul a fost șters!</div>
        <div><a href="vizualizare.php">Vizualizare agenți imobiliari</a></div>
    </div>
    <?php } ?>
</body>
</html>
