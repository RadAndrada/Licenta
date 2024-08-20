<!DOCTYPE html>
<html lang="ro">
<head>
    <title>Confirmare/Anulare programari</title>
    <style>
        body {
            background-image: url('index.jpg'); 
            background-size: 100% auto;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 24px;
            text-shadow: 2px 2px 4px #444;
        }
        table {
            background-color: rgba(255, 255, 255, 0.8);
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .buton-antet {
            margin-top: 10px;
            overflow: auto; 
        }
        .buton-antet a, .buton-antet button {
            display: block; 
            margin-bottom: 10px; 
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            background-color: #f4f4f4;
            transition: background-color 0.3s;
            max-width: 50px; 
            width: 100%; 
        }
        .buton-antet a:hover, .buton-antet button:hover {
            background-color: #ccc; 
        }
    </style>
</head>
<body>
<div class="buton-antet">
    <a href="paginaprincipala.php">Meniu</a>
    <a href="logout.php">Logout</a>
</div>
<h1>Programarile mele:</h1>

<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); 
    exit;
}

include('conectare.php');

$query = "SELECT programari.*, client.telefon AS telefon FROM programari INNER JOIN client ON programari.id_client = client.id ORDER BY numar_programare ASC";
$result = $mysqli->query($query);

$message = "";

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Numar Programare</th><th>Proprietate</th><th>Agent imobiliar</th><th>Contact client</th><th>Data</th><th>Ora</th><th>Comentarii</th><th>Stare</th><th colspan='2'>Acțiuni</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $query_agent = "SELECT nume, prenume FROM agent WHERE id = ?";
        $stmt_agent = $mysqli->prepare($query_agent);
        $stmt_agent->bind_param('i', $row['id_agent']);
        $stmt_agent->execute();
        $stmt_agent->bind_result($nume_agent, $prenume_agent);
        $stmt_agent->fetch();
        $stmt_agent->close();
        
        echo "<tr>";
        echo "<td>".$row["numar_programare"]."</td>";
        echo "<td>".$row["titlu"]."</td>";
        echo "<td>".$nume_agent." ".$prenume_agent."</td>";
        echo "<td>".$row["telefon"]."</td>";
        echo "<td>".$row["data"]."</td>";
        echo "<td>".$row["ora"]."</td>";
        echo "<td>".$row["comentarii"]."</td>";
        echo "<td>".$row["Stare_Programare"]."</td>";
        echo "<td><form action='confirmareprogramari.php' method='post'>
                  <input type='hidden' name='numar_programare' value='".$row["numar_programare"]."'>
                  <input type='submit' name='confirmare' value='Confirmare'  style='background-color: #4CAF50;'>
              </form>
              </td>";
        echo "<td><form action='confirmareprogramari.php' method='post'>
                <input type='hidden' name='numar_programare' value='".$row["numar_programare"]."'>
                <input type='submit' name='anulare' value='Anulare' style='background-color: #4CAF50;'>
            </form>
            </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Nu există programări înregistrate.";
}

if (isset($_POST['numar_programare'])) {
    $numar_programare = $_POST['numar_programare'];
    if (isset($_POST['confirmare'])) {
        $query = "UPDATE programari SET Stare_Programare = 'Confirmata' WHERE numar_programare = ?";
        $action = "confirmată";
    } elseif (isset($_POST['anulare'])) {
        $query = "UPDATE programari SET Stare_Programare = 'Anulată' WHERE numar_programare = ?";
        $action = "anulată";
    }
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $numar_programare);
    if ($stmt->execute()) {
        $message = "Programare ".$action." cu succes.";
    } else {
        $message = "Eroare la ".$action." programării: " . $stmt->error;
    }
    $stmt->close();
} 

$mysqli->close();
?>

<div style="text-align: center; margin-top: 20px;">
    <?php echo $message; ?>
</div>

</body>
</html>
