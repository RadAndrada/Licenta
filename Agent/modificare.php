<?php
include("conectare.php");
$error = '';
$success_message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit'])) {
   
    $nume = htmlentities($_POST['nume'], ENT_QUOTES);
    $prenume = htmlentities($_POST['prenume'], ENT_QUOTES);
    $email = htmlentities($_POST['email'], ENT_QUOTES);
    $telefon = htmlentities($_POST['telefon'], ENT_QUOTES);
    $detalii = htmlentities($_POST['detalii'], ENT_QUOTES);
    $id = $_POST['id']; 

    if ($nume == '' || $prenume == '' || $email == '' || $telefon == '' || $detalii == '') {
        $error = 'ERROR: Completați toate câmpurile obligatorii!';
    } else {
        if ($_FILES['fotografie']['size'] > 0) {
            $fotografie = $_FILES['fotografie']['name']; 
            $target_dir = "C:/wamp64/www/Licenta/poze_agent/"; 
            $target_file = $target_dir . basename($_FILES["fotografie"]["name"]);
            move_uploaded_file($_FILES["fotografie"]["tmp_name"], $target_file); 
        } else {
            $fotografie = $_POST['fotografie_curenta'];
        }
        $stmt = $mysqli->prepare("UPDATE agent SET nume=?, prenume=?, fotografie=?, email=?, telefon=?, detalii=? WHERE id=?");
        $stmt->bind_param("ssssssd", $nume, $prenume, $fotografie, $email, $telefon, $detalii, $id); 
        if ($stmt->execute()) {
            $success_message = "Agent imobiliar modificat cu succes!"; 
        } else {
            $error = "Eroare la modificarea agentului: " . $stmt->error;
        }
        $stmt->close();
    }
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM agent WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_object();
    } else {
        $error = "ID-ul agentului nu există!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificare Agent Imobiliar</title>
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
        .containar {
            width: 50%; 
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            box-shadow: 0 0 20px #444;  
            text-align: center;
        }
        .button-vizualizare {
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
    </style>

</head>
<body>
<div class="containar">
<h2>Modificare agent imobiliar</h2>
<?php if ($error != '' && !isset($row)) { echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>"; } ?>

<?php if ($success_message != '') { ?>
    <div><?php echo $success_message; ?></div>
<?php } ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <?php if (isset($row)) { ?>
        <p>ID: <?php echo $_GET['id'];
            if ($result = $mysqli->query("SELECT * FROM agent where id='".$_GET['id']."'"))
            {
            if ($result->num_rows > 0)
            { $row = $result->fetch_object();?></p>
        <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
        <input type="hidden" name="fotografie_curenta" value="<?php echo $row->fotografie; ?>" />
        <strong>Nume: </strong> <input type="text" name="nume" value="<?php echo $row->nume; ?>"><br><br>
        <strong>Prenume: </strong> <input type="text" name="prenume" value="<?php echo $row->prenume; ?>"><br><br>
        <strong>Fotografie: </strong> <input type="file" name="fotografie"><br><br>
        <strong>Email: </strong> <input type="text" name="email" value="<?php echo $row->email; ?>"><br><br>
        <strong>Telefon: </strong> <input type="text" name="telefon" value="<?php echo $row->telefon; ?>"><br><br>
        <strong>Detalii: </strong> <textarea name="detalii"><?php echo $row->detalii; }} ?></textarea><br><br>
        <input type="submit" name="submit" value="Modifică Agent">
    <?php } ?>
</form>

<div class='buton-vizualizare'>
    <a href="vizualizare.php">Vizualizare agenți imobiliari</a>
</div>

</div>
</body>
</html>
