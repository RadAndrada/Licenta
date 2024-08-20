<?php
include("conectare.php");
$error = '';
$success = false; 

if (isset($_POST['submit'])) {
    $nume = htmlentities($_POST['nume'], ENT_QUOTES);
    $prenume = htmlentities($_POST['prenume'], ENT_QUOTES);
    $email = htmlentities($_POST['email'], ENT_QUOTES);
    $telefon = htmlentities($_POST['telefon'], ENT_QUOTES);
    $detalii = htmlentities($_POST['detalii'], ENT_QUOTES);

    if ($nume == '' || $prenume == '' || $email == '' || $telefon == '' || $detalii == '') {
        $error = 'ERROR: Completați toate câmpurile obligatorii!';
    } else {
        if (isset($_FILES['fotografie']) && $_FILES['fotografie']['error'] === UPLOAD_ERR_OK) {
            $fotografie_name = $_FILES['fotografie']['name'];
            $fotografie_tmp_name = $_FILES['fotografie']['tmp_name'];
            $fotografie_type = $_FILES['fotografie']['type'];
            $fotografie_size = $_FILES['fotografie']['size'];

            $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
            if (in_array($fotografie_type, $allowed_types)) {
                $target_dir = __DIR__ . "/../poze_agent/";
                $target_file = $target_dir . basename($fotografie_name);
                if (move_uploaded_file($fotografie_tmp_name, $target_file)) {
                    if ($stmt = $mysqli->prepare("INSERT INTO agent (nume, prenume, fotografie, email, telefon, detalii) VALUES (?, ?, ?, ?, ?, ?)")) {
                        $stmt->bind_param("ssssss", $nume, $prenume, $fotografie_name, $email, $telefon, $detalii);
                        if ($stmt->execute()) {
                            $success = true; 
                        } else {
                            $error = "ERROR: Nu s-a putut adăuga agentul!";
                        }
                        $stmt->close();
                    } else {
                        $error = "ERROR: Nu s-a putut pregăti declarația SQL!";
                    }
                } else {
                    $error = "ERROR: Fișierul nu a putut fi mutat!";
                }
            } else {
                $error = "ERROR: Fișierul nu este o imagine!";
            }
        } else {
            $error = "ERROR: Nu s-a încărcat niciun fișier!";
        }
    }
}
$mysqli->close();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Inserare agent imobiliar nou</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
        .containar {
            background-color: rgba(255, 255, 255, 0.8);
            width: 50%;
            margin: 30px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px #444;  
            text-align: center;
            margin-bottom: 20px;
        }
        .buton-sumbit {
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
        input[type="text"],
        input[type="email"],
        textarea {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="containar">
    <h1>Inserare agent imobiliar</h1>
    <?php if ($error != '') {
        echo "<div>" . $error . "</div>";
    } elseif ($success) {
        echo "<div>Agent imobiliar adăugat cu succes!</div>";
    } ?>
    <?php if (!$success) { ?> 
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div>
                <strong>Nume: </strong> <input type="text" name="nume" value="<?php echo isset($nume) ? $nume : ''; ?>"/><br>

                <strong>Prenume: </strong> <input type="text" name="prenume" value="<?php echo isset($prenume) ? $prenume : ''; ?>"/><br>

                <strong>Fotografie: </strong> <input type="file" name="fotografie"/><br>

                <strong>Email: </strong> <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>"/><br>

                <strong>Telefon: </strong> <input type="text" name="telefon" value="<?php echo isset($telefon) ? $telefon : ''; ?>"/><br>

                <strong>Detalii: </strong> <textarea name="detalii"><?php echo isset($detalii) ? $detalii : ''; ?></textarea><br>
                <br>
                <div class='buton-submit'>
                    <input type="submit" name="submit" value="Submit"/><br>
                </div>
            </div>
        </form>
    <?php } ?>
    <?php if ($success) { ?>
        <a href="vizualizare.php">Vizualizare agenți imobiliari</a>
    <?php } ?>
</div>
</body>
</html>
