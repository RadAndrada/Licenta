<?php
include("conectare.php");
$error = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $suma = htmlentities($_POST['suma'], ENT_QUOTES);
    $cod = htmlentities($_POST['cod'], ENT_QUOTES);
    $data_vanzare = htmlentities($_POST['data_vanzare'], ENT_QUOTES);
    $detalii = htmlentities($_POST['detalii'], ENT_QUOTES);
    $id_agent = $_POST['id_agent']; 

    if ($id == '' || $suma == '' || $cod == '' || $data_vanzare == '' || $detalii == '' || $id_agent == '') {
        $error = 'ERROR: Completați toate câmpurile obligatorii!';
    } else {
        $result = $mysqli->query("SELECT * FROM apartamente WHERE cod = '$cod'");
        if ($result && $result->num_rows > 0) {
            $row_apartament = $result->fetch_assoc();
            $procent_comision = $row_apartament['comision']; 

            $comision = ($suma * $procent_comision) / 100;

            $sql = "UPDATE vanzari SET suma='$suma', comision='$comision', cod='$cod', data_vanzare='$data_vanzare', detalii='$detalii',  id_agent='$id_agent' WHERE id='$id'";

            if ($mysqli->query($sql) === TRUE) {
                $success_message = "Tranzacție modificată cu succes!";
            } else {
                $error = "Eroare la modificarea tranzacției: " . $mysqli->error;
            }
        } else {
            $error = "Imi pare rau! Codul apartamentului $cod nu există!";
        }
    }
} elseif (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $result = $mysqli->query("SELECT vanzari.*, agent.nume, agent.prenume from vanzari LEFT JOIN agent ON vanzari.id_agent= agent.id WHERE vanzari.id=$id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_object();
    } else {
        echo "Vanzarea nu există.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificare Vânzare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../index.jpg'); 
            background-size: cover;
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
        .buton{
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
            margin: 10px;
        }
        .mesaj {
            padding: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Modificare vânzare</h2>

    <?php if ($error != '' && !isset($row)) : ?>
        <div class="mesaj error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (isset($row)) : ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
            <div>
                <strong>Agent imobiliar: </strong>
                <?php echo htmlspecialchars($row->nume . ' ' . $row->prenume); ?>
                <input type="hidden" name="id_agent" value="<?php echo $row->id_agent; ?>"><br>
                <br>
                <label for="suma">Sumă:</label><br>
                <input type="number" id="suma" name="suma" value="<?php echo $row->suma; ?>" required><br>   
                <label for="cod">Cod Apartament:</label><br>
                <input type="number" id="cod" name="cod" value="<?php echo $row->cod; ?>" required><br>
                <label for="data_vanzare">Data Vanzare:</label><br>
                <input type="date" id="data_vanzare" name="data_vanzare" value="<?php echo $row->data_vanzare; ?>" required><br>
                <label for="detalii">Detalii:</label><br>
                <textarea id="detalii" name="detalii" rows="4" required><?php echo $row->detalii; ?></textarea><br>
                <br>
                <div class="buton">
                    <input type="submit" name="submit" value="Modifică vânzare">
                </div>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($error == '' && $success_message != '') : ?>
        <div class="mesaj success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <a href="vizualizare.php">Vizualizare vânzări</a>
</div>
</body>
</html>
