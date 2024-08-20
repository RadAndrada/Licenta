<?php
include("conectare.php");

$sql_vanzari= "SELECT MONTH(data_vanzare) AS luna, SUM(suma) AS suma_totala FROM (SELECT 1 AS luna UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) AS luni LEFT JOIN vanzari ON MONTH(vanzari.data_vanzare) = luni.luna GROUP BY luni.luna ORDER BY luni.luna";
$result_vanzari = $mysqli->query($sql_vanzari);

$vanzari_lunare = [];

while ($row_vanzari = mysqli_fetch_assoc($result_vanzari)) {
    $vanzari_lunare[$row_vanzari['luna']] = $row_vanzari['suma_totala'];
}

for ($i = 1; $i <= 12; $i++) {
    if (!isset($vanzari_lunare[$i])) {
        $vanzari_lunare[$i] = 0;
    }
}

ksort($vanzari_lunare);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graficul vânzărilor lunare</title>
    <style>
        body {
            background-image: url('../index.jpg'); 
            background-size: cover;
        }
        h2 {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 24px;
            text-shadow: 2px 2px 4px #444;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px; 
            padding: 20px; 
            margin: 50px auto; 
            width: 80%; 
        }
        .buton {
            text-align: center;
            margin-top: 20px; 
        }

        .buton a {
            background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 5px;
            padding: 10px 20px; 
            text-decoration: none;
            color: #444;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class='container'>
<h2>Evoluției vânzărilor pe parcursul anului 2024</h2>
<canvas id="vanzariChart" width="800" height="200"></canvas>

<script>
    const vanzari = <?php echo json_encode(array_values($vanzari_lunare)); ?>;
    const ctx = document.getElementById('vanzariChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['', 'Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'], 
            datasets: [
                {
                    label: 'Vanzari',
                    data: vanzari,
                    fill: false,
                    borderColor: 'rgb(88, 88, 88)',
                    tension: 0.1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true 
                }
            }
        }
    });
</script>
</div>
<div class="buton">
    <a href="vizualizare.php">Vizualizare vânzări</a>
</div>
</body>
</html>
