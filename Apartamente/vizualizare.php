<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Proprietăți</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
     <style>
        body {
            background-image: url('../index.jpg'); 
            background-size: cover; 
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 20px #444;        
        }
        h1 {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 24px;
            text-shadow: 2px 2px 4px #444;
        }
        table {
            background-color: rgba(255, 255, 255, 0.8);
            border-collapse: collapse;
            margin-top: 40px;
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
        img {
            max-width: 100%;
            max-height: 100px;
        }
        .add-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .add-link a {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .add-link a:hover {
            background-color: #45a049;
        }
        .buton {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .buton button {
            background-color: #4CAF50;
            color: #444;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
        }
    </style>
</head>
<body>
<?php include("C:/wamp64/www/Licenta/header.php"); ?>
<h1>Panou de control al proprietăților</h1>
<?php
include("conectare.php");
if ($result = $mysqli->query("SELECT apartamente.*, agent.nume, agent.prenume FROM apartamente LEFT JOIN agent ON apartamente.id_agent = agent.id ORDER BY apartamente.cod")) {
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Cod</th><th>Titlu</th><th>Descriere</th><th>Adresa</th><th>Suprafata Totala</th><th>Suprafata Utila</th><th>Etaj</th><th>Camere</th><th>Dormitoare</th><th>Balcon</th><th>Bai</th><th>Garaj</th><th>Curte</th><th>Parcare</th><th>An Constructie</th><th>Tip Incalzire</th><th>Dotari</th><th>Tip Apartament</th><th>Pret</th><th>Comision</th><th>Disponibilitate</th><th>Nume proprietar</th><th>Telefon proprietar</th><th>Cartier</th><th>Agent</th><th colspan='5'>Fotografii</th><th colspan='2'>Acțiuni</th></tr>";
        while ($row = $result->fetch_object()) {
            echo "<tr>";
            echo "<td>" . $row->cod . "</td>";
            echo "<td>" . $row->titlu . "</td>";
            echo "<td>" . $row->descriere . "</td>";
            echo "<td>" . $row->adresa . "</td>";
            echo "<td>" . $row->suprafata_totala . "</td>";
            echo "<td>" . $row->suprafata_utila . "</td>";
            echo "<td>" . $row->etaj . "</td>";
            echo "<td>" . $row->camere . "</td>";
            echo "<td>" . $row->dormitoare . "</td>";
            echo "<td>" . ($row->balcon ? 'Da' : 'Nu') . "</td>";
            echo "<td>" . $row->bai . "</td>";
            echo "<td>" . ($row->garaj ? 'Da' : 'Nu') . "</td>";
            echo "<td>" . ($row->curte ? 'Da' : 'Nu') . "</td>";
            echo "<td>" . ($row->parcare ? 'Da' : 'Nu') . "</td>";
            echo "<td>" . $row->anul_constructiei . "</td>";
            echo "<td>" . $row->tip_incalzire . "</td>";
            echo "<td>" . $row->dotari . "</td>";
            echo "<td>" . $row->tip_apartament . "</td>";
            echo "<td>" . $row->pret . "</td>";
            echo "<td>" . $row->comision . "</td>";
            echo "<td>" . $row->disponibilitate . "</td>";
            echo "<td>" . $row->nume . " " . $row->prenume . "</td>"; 
            echo "<td>" . $row->nume_proprietar . "</td>";
            echo "<td>" . $row->telefon_proprietar. "</td>";
            echo "<td>" . $row->cartier . "</td>";
            echo "<td><img src='../poze_apartamente/" . $row->fotografie0 . "' alt='Fotografie 0' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td><img src='../poze_apartamente/" . $row->fotografie1 . "' alt='Fotografie 1' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td><img src='../poze_apartamente/" . $row->fotografie2 . "' alt='Fotografie 2' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td><img src='../poze_apartamente/" . $row->fotografie3 . "' alt='Fotografie 3' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td><img src='../poze_apartamente/" . $row->fotografie4 . "' alt='Fotografie 4' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td><div class='buton'><button onclick=\"window.location.href='modificare.php?cod=" . $row->cod . "'\">Modificare</button></div></td>";
            echo "<td><div class='buton'><button onclick=\"window.location.href='stergere.php?cod=" . $row->cod . "'\">Stergere</button></div></td>";            
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nu există apartamente!";
    }
} else {
    echo "Eroare: " . $mysqli->error;
}
$mysqli->close();
?>
<div class='buton'>
<button><a href="adaugare.php">Adăugare proprietate nouă</a></button>
</div>
</body>
</html>
