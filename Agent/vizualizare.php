<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Agent</title>
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
            padding: 20px;
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
            transition: background-color 0.3s ease;
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
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
<?php include("C:/wamp64/www/Licenta/header.php"); ?>
<h1>Agenți imobiliari</h1>
<?php
include("conectare.php");
if ($result = $mysqli->query("SELECT * FROM agent ORDER BY id"))
{ 
    if ($result->num_rows > 0)
    {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Id</th><th>Nume</th><th>Prenume</th><th>Fotografie</th><th>Email</th><th>Telefon</th><th>Detalii</th><th colspan='2'>Acțiuni</th></tr>";
        while ($row = $result->fetch_object())
        {
            echo "<tr>";
            echo "<td>" . $row->id . "</td>";
            echo "<td>" . $row->nume . "</td>";
            echo "<td>" . $row->prenume . "</td>";
            echo "<td><img src='../poze_agent/" . $row->fotografie . "' alt='Fotografie agent' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td>" . $row->email . "</td>";
            echo "<td>" . $row->telefon . "</td>";
            echo "<td>" . $row->detalii . "</td>";
            echo "<td><div class='buton'><button onclick=\"window.location.href='modificare.php?id=" . $row->id . "'\">Modificare</button></div></td>";
            echo "<td><div class='buton'><button onclick=\"window.location.href='stergere.php?id=" . $row->id . "'\">Stergere</button></div></td>";

            echo "</tr>";
        }
        echo "</table>";
    }
    else
    {
        echo "Nu exista agenti!";
    }
}
else
{ echo "Error: " . $mysqli->error(); }
$mysqli->close();
?>
<div class='buton'>
    <button><a href="adaugare.php">Adauga agent</a></button>
</div>
</body>
</html>

