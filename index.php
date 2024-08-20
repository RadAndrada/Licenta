<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenți Imobiliari</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            position: relative;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center; 
            font-family: Arial, sans-serif;     
        }

        .bunvenit {
            padding: 50px ; 
            background-color: rgba(0, 0, 0, 0.5); 
            text-align: center; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }

        .bunvenit p {
            max-width: 888px;
            font-size: 20px;
            color: #e0e0e0;
            margin: 0 auto; 
            font-weight: bold; 
        }

        .butoane {
            position: absolute;
            top: 140px; 
            right: 20px; 
        }

        .buton {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #444;
            text-decoration: none;
            border-radius: 5px;
        }

        .buton:hover {
            background-color: #e5e5e5;
        }

        .agent-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 50px;
        }

        .agent {
            margin: 20px;
            padding: 60px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            background-color: rgba(255, 255, 255, 0.58);
            width: 300px; 
            text-align: center;
        }

        .agent-poza img {
            width: 120px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            object-fit: cover;
            border: 1px solid #444;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .agent-nume {
            margin-top: 10px;
            font-weight: bold;
            color: #444;
            font-size: 18px;
        }

        .date {
            color: #556B5F;
            font-style: italic;
            margin-top: 10px;
            font-weight: 600;
        }

        .detalii {
            color: #444;
            font-weight: 600;
            margin-top: 10px;
            font-size: 14px;
            line-height: 1.4; 
        }

        .subsol-desprecompanie {
            background-color: rgba(0, 0, 0, 0.5);
            color: #ffffff;
            padding: 40px;
            text-align: center;
        }

        .subsol-desprecompanie h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .motivatie {
            width: 20%; 
            display: inline-block; 
            margin-right: 80px;
            vertical-align: top;
        }

        .EstateCluj {
            font-size: 48px;
            font-weight: bold;
            color: #ffffff; 
            text-shadow: 0 0 10px #ffffff; 
            font-style: italic;
        }

    </style>
</head>
<body>
<div class="bunvenit">
    <h3 class="EstateCluj">Estate Cluj</h3><br>
    <p>Bine ai venit pe platforma noastră de imobiliare!</p>
    <p>Suntem încântați să te avem alături în căutarea locuinței perfecte sau a investiției ideale.
    Echipa noastră este aici să te sprijine în fiecare pas al procesului, pentru a face ca experieța ta să fie una memorabilă.</p>
    <div class="butoane">
        <a href="portofoliu.php" class="buton">Portofoliu</a>
    </div>
</div>
<div class="agent-container">
    <?php
    include("conectare.php");

    $sql = "SELECT * FROM agent";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='agent'>";
            echo "<div class='agent-poza'><img src='poze_agent/" . $row['fotografie'] . "' alt='agent'></div>";
            echo "<div class='agent-nume'>" . $row['nume'] . " " . $row['prenume'] . "</div>";
            echo "<div class='date'>" . $row['telefon'] . " | " . $row['email'] . "</div>";
            echo "<div class='detalii'>" . $row['detalii'] . "</div>";
            echo "</div>";
        }
    } else {
        echo "Nu există date disponibile.";
    }

    $mysqli->close();
    ?>
</div>
<div class="subsol-desprecompanie">
    <h2>De ce să ne alegi pe noi pentru a-ți căuta locuința ideală?</h2>
    <div class="motivatie">
        <p>📌Oferim consultanță personalizată pentru fiecare client și suntem dedicați satisfacției tale. </p>
    </div>
    <div class="motivatie">
        <p>📌Noi avem o vastă experiență în domeniul imobiliar și cunoaștem piața locală în profunzime. Suntem la curent cu cele mai recente tehnologii și inovații. </p>
    </div>
    <div class="motivatie">
        <p>📌Avem o echipă de agenți experimentați, pregătiți să îți găsească locuința perfectă în cel mai scurt timp posibil. </p>
    </div>
</div>
</body>
</html>
