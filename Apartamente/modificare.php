<?php
include("conectare.php");
$error = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod']) && !empty($_POST['cod'])) {
    $cod = $_POST['cod'];
    $titlu = htmlentities($_POST['titlu'], ENT_QUOTES);
    $descriere = htmlentities($_POST['descriere'], ENT_QUOTES);
    $adresa = htmlentities($_POST['adresa'], ENT_QUOTES);
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
    $tip_incalzire = htmlentities($_POST['tip_incalzire'], ENT_QUOTES);
    $dotari = htmlentities($_POST['dotari'], ENT_QUOTES);
    $tip_apartament = $_POST['tip_apartament'];
    $pret = $_POST['pret'];
    $comision = htmlentities($_POST['comision'], ENT_QUOTES);
    $disponibilitate = $_POST['disponibilitate'];
    $nume_proprietar = htmlentities($_POST['nume_proprietar'], ENT_QUOTES);
    $telefon_proprietar = htmlentities($_POST['telefon_proprietar'], ENT_QUOTES);
    $cartier = htmlentities($_POST['cartier'], ENT_QUOTES);

    for ($i = 0; $i < 5; $i++) {
        $imageFieldName = 'fotografie' . $i;
        if ($_FILES[$imageFieldName]['error'] === UPLOAD_ERR_OK) {
            ${'fotografie' . $i} = $_FILES[$imageFieldName]['name'];
            move_uploaded_file($_FILES[$imageFieldName]['tmp_name'], "../poze_apartamente/{${'fotografie' . $i}}");
        } else {
            $result = $mysqli->query("SELECT $imageFieldName FROM apartamente WHERE cod=$cod");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ${'fotografie' . $i} = $row[$imageFieldName];
            }
        }
    }

    $stmt = $mysqli->prepare("UPDATE apartamente SET fotografie0=?, fotografie1=?, fotografie2=?, fotografie3=?, fotografie4=?, titlu=?, descriere=?, adresa=?, suprafata_totala=?, suprafata_utila=?, etaj=?, camere=?, dormitoare=?, balcon=?, bai=?, garaj=?, curte=?, parcare=?, anul_constructiei=?, tip_incalzire=?, dotari=?, tip_apartament=?, pret=?, comision=?, disponibilitate=?, nume_proprietar=?, telefon_proprietar=?, cartier=? WHERE cod=?");

    if ($stmt) {
        $stmt->bind_param("ssssssssiiiiiiiiiiisssiisssss", $fotografie0, $fotografie1, $fotografie2,  $fotografie3, $fotografie4, $titlu, $descriere, $adresa, $suprafata_totala, $suprafata_utila, $etaj, $camere, $dormitoare, $balcon, $bai, $garaj, $curte, $parcare, $anul_constructiei, $tip_incalzire, $dotari, $tip_apartament, $pret, $comision, $disponibilitate, $nume_proprietar, $telefon_proprietar, $cartier, $cod);

        if ($stmt->execute()) {
            $success_message = "Proprietate modificată cu succes!";
        } else {
            $error = "Eroare la modificarea proprietății: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Eroare la pregătirea declarației SQL: " . $mysqli->error;
    }
}

echo $error;

?>

<!DOCTYPE html>
<html>
<head>
<title>Modificare proprietăți</title>
<style>
    body {
        background-image: url('../index.jpg'); 
        background-size: cover;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 50px;
        padding: 30px;
    }
    h2 {
        text-align: center;
        font-family: Arial, sans-serif;
        font-size: 24px;
        text-shadow: 2px 2px 4px #444;
    }
    .container {
        width: 50%;
        margin: 0 auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 5px;
        box-shadow: 0 0 20px #444;  
        text-align: center;
        margin-bottom: 20px;
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
        transition: background-color 0.3s ease;
    }
    img {
        max-width: 40px;
        height: auto;
        display: block;
        margin: 10px auto;
    }
</style>
</head>
<body>
<div class="container">
<h2>Modificare Proprietate</h2>
<?php
echo $success_message;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod']) && !empty($_POST['cod'])) {
} else {
    if (isset($_GET['cod']) && !empty($_GET['cod']) && is_numeric($_GET['cod'])) {
        $cod = $_GET['cod'];
        $result = $mysqli->query("SELECT a.*, b.nume, b.prenume FROM apartamente a INNER JOIN agent b ON a.id_agent = b.id WHERE a.cod=$cod");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_object();
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <strong>Cod: <?php echo $_GET['cod']; ?></strong><br>
    <input type="hidden" name="cod" value="<?php echo $row->cod; ?>" />     
    <strong>Agent imobiliar: </strong>
    <?php echo htmlspecialchars($row->nume . ' ' . $row->prenume); ?><br>
    
    <?php
    for ($i = 0; $i < 5; $i++) {
        $imageFieldName = 'fotografie' . $i;
        if (!empty($row->$imageFieldName)) {
            echo "<strong>Fotografie $i:</strong><br>";
            echo "<img src='../poze_apartamente/{$row->$imageFieldName}'><br>";
            echo "<input type='file' name='$imageFieldName' accept='image/*'><br>";
        } else {
            echo "<strong>Fotografie $i:</strong><br>";
            echo "<input type='file' name='$imageFieldName' accept='image/*'><br>";
        }
    }
    ?>
 <strong>Titlu: </strong> <input type="text" name="titlu" value="<?php echo $row->titlu; ?>"><br><br>
    <strong>Descriere: </strong> <textarea name="descriere"><?php echo $row->descriere; ?></textarea><br><br>
    <strong>Adresa: </strong> <input type="text" name="adresa" value="<?php echo htmlspecialchars($row->adresa); ?>"><br><br>
    <strong>Suprafața Totală: </strong> <input type="number" name="suprafata_totala" value="<?php echo $row->suprafata_totala; ?>">mp<br><br>
    <strong>Suprafața Utilă: </strong> <input type="number" name="suprafata_utila" value="<?php echo $row->suprafata_utila; ?>">mp<br><br>
    <strong>Etaj: </strong> <input type="number" name="etaj" value="<?php echo $row->etaj; ?>"><br><br>
    <strong>Camere: </strong> <input type="number" name="camere" value="<?php echo $row->camere; ?>"><br><br>
    <strong>Dormitoare: </strong> <input type="number" name="dormitoare" value="<?php echo $row->dormitoare; ?>"><br><br>
    <strong>Balcon: </strong> <input type="checkbox" name="balcon" <?php if($row->balcon == 1) echo 'checked'; ?>><br><br>
    <strong>Băi: </strong> <input type="number" name="bai" value="<?php echo $row->bai; ?>"><br><br>
    <strong>Garaj: </strong> <input type="checkbox" name="garaj" <?php if($row->garaj == 1) echo 'checked'; ?>><br><br>
    <strong>Curte: </strong> <input type="checkbox" name="curte" <?php if($row->curte == 1) echo 'checked'; ?>><br><br>
    <strong>Parcare: </strong> <input type="checkbox" name="parcare" <?php if($row->parcare == 1) echo 'checked'; ?>><br><br>
    <strong>Anul Construcției: </strong> <input type="number" name="anul_constructiei" value="<?php echo $row->anul_constructiei; ?>"><br><br>
    <strong>Tip Încălzire: </strong> <input type="text" name="tip_incalzire" value="<?php echo $row->tip_incalzire; ?>"><br><br>
    <strong>Dotări: </strong> <textarea name="dotari"><?php echo $row->dotari; ?></textarea><br><br>
    <strong>Tip Apartament: </strong>
    <select name="tip_apartament">
        <option value="inchirieri" <?php if($row->tip_apartament == 'inchirieri') echo 'selected'; ?>>Inchirieri</option>
        <option value="vanzari" <?php if($row->tip_apartament == 'vanzari') echo 'selected'; ?>>Vanzari</option>
    </select><br><br>
    <strong>Preț: </strong> <input type="number" name="pret" value="<?php echo $row->pret; ?>">€<br><br>
    <strong>Comision: </strong> <input type="text" name="comision" value="<?php echo $row->comision; ?>">%<br><br>
    <strong>Disponibilitate: </strong>
    <select name="disponibilitate">
        <option value="Disponibil" <?php if($row->disponibilitate == 'Disponibil') echo 'selected'; ?>>Disponibil</option>
        <option value="Indisponibil" <?php if($row->disponibilitate == 'Indisponibil') echo 'selected'; ?>>Indisponibil</option>
    </select><br><br>
    <strong>Nume proprietar: </strong> <input type="text" name="nume_proprietar" value="<?php echo $row->nume_proprietar; ?>"><br><br>
    <strong>Telefon proprietar: </strong> <input type="text" name="telefon_proprietar" value="<?php echo $row->telefon_proprietar; ?>"><br><br>
    <strong>Cartier: </strong> <input type="text" name="cartier" value="<?php echo $row->cartier; ?>"><br><br>    
    <div class='buton'>
        <input type="submit" name="submit" value="Modifică proprietate">
    </div>
</form>
<?php
        } else {
            $error = "Proprietatea nu există!";
        }
    }
}
?>

<br><a href="vizualizare.php">Vizualizare proprietăți</a>

</div>
</body>
</html>
