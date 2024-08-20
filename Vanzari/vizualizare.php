<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

    <title>Vânzări</title>
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
            margin-bottom: 20px;
        }
        .buton button {
            background-color: #4CAF50;
            color: #444;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
        }
        .total-suma {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
        .download {
            text-align: center;
            margin-top: 20px;
            color: #444;
            background-color: rgba(255, 255, 255, 0.8);
        }
            
    </style>
</head>
<body>
<?php include("C:/wamp64/www/Licenta/header.php"); ?>
<h1>Vânzări</h1>

<div class="download">
    <a href="contracte_de_descarcat/Acord_de_colaborare.pdf" download class="download">Descarcă acordul de colaborare</a><br>
    <a href="contracte_de_descarcat/Contract_vânzare_cumpărare.pdf" download class="download">Descarcă contractul de vânzare-cumpărare</a><br>

</div>
<?php
include("conectare.php");
$sql_sum = "SELECT SUM(suma) AS suma_totala FROM vanzari";
$result = $mysqli->query($sql_sum);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $suma_totala = $row['suma_totala'];
    echo "<div class='total-suma'>Suma totală a vânzărilor de până acum: " . $suma_totala . "</div>";
    } else {
        echo "<div class='total-suma'>Nu există vânzări înregistrate încă.</div>";
}
if ($result = $mysqli->query("SELECT vanzari.*, agent.nume, agent.prenume FROM vanzari LEFT JOIN agent ON vanzari.id_agent = agent.id  ORDER BY vanzari.id"))
{ 
    if ($result->num_rows > 0)
    {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Id</th><th>Suma</th><th>Cod</th><th>Agent imobiliar</th><th>Comision</th><th>Data_vanzare</th><th>Contract agent-vanzator</th><th>Contract vanzator-cumparator</th><th>Detalii</th><th colspan='2'>Acțiuni</th></tr>";
        while ($row = $result->fetch_object())
        {
            echo "<tr>";
            echo "<td>" . $row->id . "</td>";
            echo "<td>" . $row->suma . "</td>";
            echo "<td>" . $row->cod . "</td>";
            echo "<td>" . $row->nume . " " . $row->prenume . "</td>"; 
            echo "<td>" . $row->comision . "</td>";
            echo "<td>" . $row->data_vanzare . "</td>";
            echo "<td>" . $row->contract_agent_vanzator . "</td>";
            echo "<td>" . $row->contract_vanzator_cumparator . "</td>";
            echo "<td>" . $row->detalii . "</td>";
            echo "<td><div class='buton'><button onclick=\"window.location.href='modificare.php?id=" . $row->id . "'\">Modificare</button></div></td>";
            echo "<td><div class='buton'><button onclick=\"window.location.href='stergere.php?id=" . $row->id . "'\">Stergere</button></div></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else
    {
        echo "Nu exista nici o vanzare!";
    }
}
else
{ echo "Error: " . mysqli_error($mysqli); }
$mysqli->close();
?>
<div class='buton'>
   <button><a href="adaugare.php">O noua vânzare? Adaugă detaliile aici!</a></button>
</div>
<div class='buton'>
   <button><a href="istoricvanzari.php">Vizualizează graficul vânzărilor lunare.</a></button>
</div>
</body>
</html>
