<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            padding: 20px;  
            justify-content: center;
            align-items: center;
        }
        .containar {
            background-color: #fff;
            border: 2px solid #444;
            border-radius: 12px;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.8); 
            margin-bottom: 20px;
            display: flex;
            flex-direction: column; 
            align-items: center;
            margin-top: 50px;
        }
        .form-group {
            margin-bottom: 20px; 
            font-size: 20px;
            font-weight: bold;
        }
        .buton {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.6);
            color: #444;
            text-decoration: none;
            border-radius: 5px;
        }
        .buton:hover {
            background-color: #e5e5e5;
        }
        .succesmsj {
            color: green;
            font-weight: bold;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.6);
            text-align: center;
        }
        .errormsj {
            color: red;
            font-weight: bold;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.6);
            text-align: center;
        }
        .buton-container {
            text-align: center; 
            margin-top: 20px;
        }
        .EstateCluj{
            font-size: 48px;
            font-weight: bold;
            color: #ffffff; 
            text-shadow: 0 0 10px black; 
            font-style: italic;
            text-align: center;
        }
    </style>
</head>
<body>
<h3 class="EstateCluj">Estate Cluj</h3><br>

<div class="buton-container">
    <a href="panouclient.php" class="buton">Înapoi</a>
    <a href="index.php" class="buton">Despre</a>
    <a href="portofoliu.php" class="buton">Portofoliu</a>
    <a href="../Licenta/Clienti/logoutclient.php" class="buton">LogOut</a>
</div>
<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: logclient.php');
    exit;
}

require 'conectare.php';

$succes_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titlu = $_POST['apartament'];
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $comentarii = $_POST['comentarii'];

    $id_client = $_SESSION['id'];

    $id_agent = $_POST['agent_imobiliar'];

    if ($mysqli->connect_error) {
        die('Conexiunea la baza de date a eșuat: ' . $mysqli->connect_error);
    }

    try {
        $query = "SELECT COUNT(*) FROM apartamente WHERE titlu = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception('Eroare pregătire interogare: ' . $mysqli->error);
        }
        $stmt->bind_param('s', $titlu);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $error_message = "Apartamentul selectat nu există sau nu este disponibil. Vă rugăm să alegeți altul.";
        } else {
            $query_check = "SELECT COUNT(*) FROM programari WHERE data = ? AND ora = ? AND id_agent = ?";
            $stmt_check = $mysqli->prepare($query_check);
            if (!$stmt_check) {
                throw new Exception('Eroare pregătire interogare: ' . $mysqli->error);
            }
            $stmt_check->bind_param('ssi', $data, $ora, $id_agent);
            $stmt_check->execute();
            $stmt_check->bind_result($existing_count);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($existing_count > 0) {
                $error_message = "Ora selectată este deja ocupată pentru acest agent imobiliar în această dată. Vă rugăm să alegeți o altă oră sau alt agent imobiliar.";
            } else {
                $query_insert = "INSERT INTO programari (id_client, id_agent, titlu, data, ora, comentarii) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert = $mysqli->prepare($query_insert);
                if (!$stmt_insert) {
                    throw new Exception('Eroare pregătire interogare: ' . $mysqli->error);
                }
                $stmt_insert->bind_param('iissss', $id_client, $id_agent, $titlu, $data, $ora, $comentarii);
                if ($stmt_insert->execute()) {
                    $succes_message = "Programarea a fost înregistrată cu succes! În cel mai scurt timp vei fi sunat de către agentul imobiliar selectat!";
                } else {
                    $error_message = "Eroare la înregistrarea programării: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            }
        }
    } catch (Exception $e) {
        $error_message = "Eroare: " . $e->getMessage();
    }
    $mysqli->close();
}
?>
<?php
if (!empty($succes_message)) {
    echo "<div class='succesmsj'>$succes_message</div>";
}
?>
<?php
if (!empty($error_message)) {
    echo "<div class='errormsj'>$error_message</div>";
}
?>
<?php
if (empty($succes_message) && empty($error_message)) {
?>
<div class="containar">
    <p>Următorul pas este acela de a-ți alege data și ora potrivită pentru a viziona proprietatea visată!</p> 
    <p>Este simplu, completează formularul de mai jos și alege proprietatea pe care vrei să o vizionezi și agentul imobiliar care vrei să îți fie alături.</p>
    <h1>Programare</h1>
    <div class="containar">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="apartament">Apartament:</label>
                <select id="apartament" name="apartament" required>
                    <option value="">Alege apartamentul</option>
                    <?php
                    $query_apartamente = "SELECT titlu FROM apartamente WHERE disponibilitate = 'Disponibil'";
                    $result_apartamente = mysqli_query($mysqli, $query_apartamente);

                    if ($result_apartamente && mysqli_num_rows($result_apartamente) > 0) {
                        while ($row_apartament = mysqli_fetch_assoc($result_apartamente)) {
                            echo '<option value="' . $row_apartament['titlu'] . '">' . $row_apartament['titlu'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>Niciun apartament disponibil</option>';
                    }
                    ?>
                </select><br>
                <label for="agent_imobiliar">Agent imobiliar:</label>
                <select id="agent_imobiliar" name="agent_imobiliar" required>
                    <option value="">Alege agentul imobiliar</option>
                    <?php
                    $query_agent = "SELECT id, nume, prenume FROM agent";
                    $result_agent = mysqli_query($mysqli, $query_agent);

                    if ($result_agent && mysqli_num_rows($result_agent) > 0) {
                        while ($row_agent = mysqli_fetch_assoc($result_agent)) {
                            echo '<option value="' . $row_agent['id'] . '">' . $row_agent['nume'] . ' ' . $row_agent['prenume'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>Niciun agent imobiliar disponibil</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label for="ora">Ora:</label>
                <input type="time" id="ora" name="ora" required min="09:00" max="19:00">
            </div>
            <div class="form-group">
                <label for="comentarii">Comentarii:</label>
                <textarea id="comentarii" name="comentarii"></textarea>
            </div>
            <button type="submit" name="submit" class="buton">Trimite</button>
        </form>
    </div>
</div>
<?php
}
?>
</body>
</html>
