<?php
include("conectare.php");

$error = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $uploaded_photos = array();

    $target_dir = "C:/wamp64/www/Licenta/poze_apartamente/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    for ($i = 0; $i < 5; $i++) {
        if ($_FILES['fotografie'.$i]['error'] == UPLOAD_ERR_OK) {
            $nume_fisier = $_FILES['fotografie'.$i]['name'];
            $uploaded_photos[] = $nume_fisier;
            $target_file = $target_dir . basename($_FILES['fotografie'.$i]['name']);
            move_uploaded_file($_FILES['fotografie'.$i]['tmp_name'], $target_file);
        } else {
            $uploaded_photos[] = NULL;
        }
    }

    $titlu = $_POST['titlu'];
    $descriere = $_POST['descriere'];
    $adresa = $_POST['adresa'];
    $suprafata_totala = $_POST['suprafata_totala'];
    $suprafata_utila = $_POST['suprafata_utila'];
    $etaj = $_POST['etaj'];
    $camere = $_POST['camere'];
    $dormitoare = $_POST['dormitoare'];
    $balcon = isset($_POST['balcon']) ? 1 : 0;
    $bai = $_POST['bai'];
    $garaj = isset($_POST['garaj']) ? 1 : 0;
    $curte = isset($_POST['curte']) ? 1 : 0;
    $parcare = isset($_POST['parcare']) ? 1 : 0;
    $anul_constructiei = $_POST['anul_constructiei'];
    $tip_incalzire = $_POST['tip_incalzire'];
    $dotari = $_POST['dotari'];
    $tip_apartament = $_POST['tip_apartament']; 
    $pret = $_POST['pret'];
    $comision = $_POST['comision'];
    $disponibilitate = $_POST['disponibilitate']; 
    $id_agent = $_POST['agent'];
    $nume_proprietar = $_POST['nume_proprietar'];
    $telefon_proprietar = $_POST['telefon_proprietar'];
    $cartier = $_POST['cartier'];

    $sql = "INSERT INTO apartamente (fotografie0, fotografie1, fotografie2, fotografie3, fotografie4, titlu, descriere, adresa, suprafata_totala, suprafata_utila, etaj, camere, dormitoare, balcon, bai, garaj, curte, parcare, anul_constructiei, tip_incalzire, dotari, tip_apartament, pret, comision, disponibilitate, id_agent, nume_proprietar, telefon_proprietar, cartier) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if (!($stmt = $mysqli->prepare($sql))) {
        die("Eroare la pregătirea interogării: " . $mysqli->error);
    }

    $stmt->bind_param("ssssssssiiiiiiiiiiisssiisssss", $uploaded_photos[0], $uploaded_photos[1], $uploaded_photos[2], $uploaded_photos[3], $uploaded_photos[4], $titlu, $descriere, $adresa, $suprafata_totala, $suprafata_utila, $etaj, $camere, $dormitoare, $balcon, $bai, $garaj, $curte, $parcare, $anul_constructiei, $tip_incalzire, $dotari, $tip_apartament, $pret, $comision, $disponibilitate, $id_agent, $nume_proprietar, $telefon_proprietar, $cartier);

    if ($stmt->execute()) {
        $success_message = "Apartament adăugat cu succes!";
    } else {
        $error = "Eroare la adăugarea apartamentului: " . $mysqli->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inserare proprietate nouă</title>
    <style>
        body {
            background-image: url('../index.jpg'); 
            background-size: cover; 
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 50px;
            padding: 30px;
        }
        h1 {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 24px;
            text-shadow: 2px 2px 4px #444;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            width: 50%; 
            margin: 0 auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px #444;  
            text-align: center;
        }
        form > * {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .buton {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        input[type="submit"] {
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
<div class="container">
    <h1>Inserare proprietate nouă</h1>
    <?php if (empty($success_message)): ?>
        <form action="adaugare.php" method="POST" enctype="multipart/form-data">
            <?php for ($i = 0; $i < 5; $i++) { ?>
                <label for="fotografie<?php echo $i; ?>">Fotografie <?php echo $i; ?>:</label>
                <input type="file" id="fotografie<?php echo $i; ?>" name="fotografie<?php echo $i; ?>" accept="image/*"><br>
            <?php } ?>
            <label for="titlu">Titlu:</label>
            <input type="text" id="titlu" name="titlu" required><br>
            
            <label for="descriere">Descriere:</label>
            <textarea id="descriere" name="descriere" rows="4" required></textarea><br>
            
            <label for="adresa">Adresa:</label>
            <input type="text" id="adresa" name="adresa" required><br>
            
            <label for="suprafata_totala">Suprafața Totală:</label>
            <input type="number" id="suprafata_totala" name="suprafata_totala" required>mp<br>
            
            <label for="suprafata_utila">Suprafața Utilă:</label>
            <input type="number" id="suprafata_utila" name="suprafata_utila" required>mp<br>
            
            <label for="etaj">Etaj:</label>
            <input type="number" id="etaj" name="etaj" required><br>

            <label for="camere">Camere:</label>
            <input type="number" id="camere" name="camere" required><br>

            <label for="dormitoare">Dormitoare:</label>
            <input type="number" id="dormitoare" name="dormitoare" required><br>

            <label for="balcon">Balcon:</label>
            <input type="checkbox" id="balcon" name="balcon"><br>

            <label for="bai">Băi:</label>
            <input type="number" id="bai" name="bai" required><br>

            <label for="garaj">Garaj:</label>
            <input type="checkbox" id="garaj" name="garaj"><br>

            <label for="curte">Curte:</label>
            <input type="checkbox" id="curte" name="curte"><br>

            <label for="parcare">Parcare:</label>
            <input type="checkbox" id="parcare" name="parcare"><br>

            <label for="anul_constructiei">Anul Construcției:</label>
            <input type="number" id="anul_constructiei" name="anul_constructiei" required><br>

            <label for="tip_incalzire">Tip Încălzire:</label>
            <input type="text" id="tip_incalzire" name="tip_incalzire" required><br>

            <label for="dotari">Dotări:</label>

            <textarea id="dotari" name="dotari" rows="4" required></textarea><br>
            <label for="tip_apartament">Tip Apartament:</label>
            <select id="tip_apartament" name="tip_apartament">
            <option value="inchirieri">Inchirieri</option>
            <option value="vanzari">Vanzari</option>
            </select><br>

            <label for="pret">Preț:</label>
            <input type="number" id="pret" name="pret" required>€<br>

            <label for="comision">Comision:</label>
            <input type="text" id="comision" name="comision" required>%<br>

            <label for="disponibilitate">Disponibilitate:</label>
            <select id="disponibilitate" name="disponibilitate">
            <option value="Disponibil">Disponibil</option>
            <option value="Indisponibil">Indisponibil</option>
            </select><br>

            <label for="agent">Agent imobiliar:</label>
            <select id="agent" name="agent" required>
            <?php
            $agent_query = "SELECT id, nume, prenume FROM agent";
            $agent_result = $mysqli->query($agent_query);
            if (!$agent_result) {
                die("Eroare în interogare: " . $mysqli->error);
            }
            if ($agent_result->num_rows > 0) {
                while ($agent_row = $agent_result->fetch_assoc()) {
                    echo "<option value='" . $agent_row['id'] . "'>" . $agent_row['nume'] . " " . $agent_row['prenume'] . "</option>";
                }
            } else {
                echo "<option value=''>Nu există agenți înregistrați</option>";
            }
            ?>
            </select><br>

            <label for="nume_proprietar">Proprietar:</label>
            <input type="text" id="nume_proprietar" name="nume_proprietar" required><br>

            <label for="telefon_proprietar">Telefon proprietar:</label>
            <input type="text" id="telefon_proprietar" name="telefon_proprietar" required><br>

            <label for="cartier">Cartier</label>
            <input type="text" id="cartier" name="cartier" required><br>

            <div class='buton'>
                <input type="submit" name="submit" value="Adăugare proprietate">
            </div>
            </form>
            <?php else: ?>
            <p><?php echo $success_message; ?></p>
            <a href="vizualizare.php">Vizualizare proprietăți</a>
            <?php endif; ?>
            <p style="color: red;"><?php echo $error; ?></p>
            </div>
</body>
</html>
