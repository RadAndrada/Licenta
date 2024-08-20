<!DOCTYPE html>
<html lang="ro">
<head>
    <title>Proprietati</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
        }

        .antet {
            color: #e0e0e0;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .antet a {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #444;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
            position: relative;
            top: -20px;
        }

        .antet a:hover {
            background-color: #e5e5e5;
        }

        .container-utilitati {
            width: 800px;
            display: flex;
            flex-direction: column; 
            background-color: #fff;
            border: 2px solid #444;
            border-radius: 12px;
            padding: 20px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.6);
            margin-bottom: 20px;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .container-utilitati span {
            font-size: 18px; 
            display: block; 
            margin-bottom: 10px; 
            text-align: center; 
        }

        .container-proprietate {
            max-width: 1000px;
            width: 100%;
            margin: 80px auto;
            background-color: #fff;
            border: 2px solid #444;
            border-radius: 12px;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.6);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container-galerie-imagini {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container-imagine {
            max-width: 500px;
            max-height: 750px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .container-imagine img {
            width: 100%; 
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .container-miniaturi {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-miniaturi img {
            max-width: 150px;
            height: auto;
            margin: 0 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .container-miniaturi img:hover {
            transform: scale(1.2);
        }

        .titlu-ut {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .container-butoane {
            text-align: center;
            margin-top: 20px;
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

        .subsol {
            font-size: 20px;
            color: #e0e0e0;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .subsol .buton {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #444;
            font-size: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="antet">
    <h1>Oferte recomandate pentru tine</h1>
    <p>Portofoliul meu iti ofera cele mai moderne proprietati din Cluj-Napoca, renegociate periodic</p>
    <a href="index.php" style="float: right;">Echipa</a>
    <a href="portofoliu.php" style="float: right;">Portofoliu</a>
</div>
<?php
include("conectare.php");

if(isset($_GET['cod'])) {
    $cod_apartament = $_GET['cod'];

    $query = "SELECT * FROM apartamente WHERE cod = $cod_apartament";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo '<div class="container-proprietate">';
        echo '<div class="container-galerie-imagini">';
        echo '<div class="container-imagine">';
        echo "<img src='poze_apartamente/" . $row['fotografie0'] . "' alt='Poza apartament'>";
        echo '</div>';
        echo '<div class="container-miniaturi">';
        for ($i = 0; $i <= 4; $i++) {
            if (!empty($row['fotografie' . $i])) {
                echo "<img id='thumbnail$i' src='poze_apartamente/" . $row['fotografie' . $i] . "' alt='Thumbnail $i' onclick='changeMainImage(\"poze_apartamente/" . $row['fotografie' . $i] . "\")'>";
            }
        }
        echo '</div>';
        echo '</div>';
        echo "<h2>" . $row['titlu'] . "</h2>";
        echo "<p> " . $row['pret'] . "€</p>";
        echo "<p> " . $row['descriere'] . "</p>";
        echo "<p> " . $row['adresa'] . "</p>";
        echo "<p> " . $row['suprafata_totala'] . "</p>";
        echo '</div>';
        echo '<div class="container-utilitati">';
        echo '<p class="titlu-ut">Utilitati</p>';
        echo '<span  itemprop="name"> Suprafata utila: ' . $row['suprafata_utila'] . '</span>';
        echo '<span  itemprop="name"> Anul constructiei: ' . $row['anul_constructiei'] . '</span>';
        echo '<span  itemprop="name"> Etaj: ' . $row['etaj'] . '</span>';
        echo '<span> ' . $row['camere'] . ' | ' . $row['dormitoare'] . '</span>';
        echo '<span> Curte: ' . $row['curte'] . ' | ' . $row['parcare'] . ' | Garaj: ' . $row['garaj'] . '</span>';
        echo '<span  itemprop="name"> Sistem incalzire: ' . $row['tip_incalzire'] . '</span>';
        echo '<span  itemprop="name"> Alte detalii: ' . $row['dotari'] . '</span>';
        echo '</div>';

    } else {
        echo "Apartamentul nu a fost gasit.";
    }

    mysqli_free_result($result);
} else {
    echo "Nu a fost furnizat un cod de apartament.";
}

mysqli_close($mysqli);
?>
<div class="subsol">
    <p>Iti place un anume apartament?</p>
    <a href="Clienti/logclient.php" class="buton">Fa-ti o programare!</a>
</div>

<script>
    function changeMainImage(imageUrl) {
        document.querySelector('.container-imagine img').src = imageUrl;
    }
</script>

</body>
</html>
