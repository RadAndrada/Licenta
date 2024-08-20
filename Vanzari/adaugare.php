<!DOCTYPE html>
<html>
<head>
    <title>Inserare vânzare nouă</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            background-image: url('../index.jpg'); 
            background-size: cover;
            font-family: Arial, sans-serif;
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
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            box-shadow: 0 0 20px #444;  
            text-align: center;
        }
        form > * {
            margin-bottom: 30px;
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
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Inserare vânzare nouă</h1>
    <?php
    include("conectare.php");
    $error = '';
    $success_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'], $_POST['suma'], $_POST['cod'], $_POST['data_vanzare'], $_POST['detalii'], $_POST['agent'], $_FILES['contract_agent_vanzator']['name'], $_FILES['contract_vanzator_cumparator']['name'])) {
            $suma = $_POST['suma'];
            $cod = $_POST['cod'];
            $data_vanzare = $_POST['data_vanzare'];
            $detalii = $_POST['detalii'];
            $id_agent = $_POST['agent'];

            if (empty($suma) || empty($cod) || empty($data_vanzare) || empty($detalii) || empty($id_agent)) {
                $error = 'ERROR: Completați toate câmpurile obligatorii!';
            } else {
                $result = $mysqli->query("SELECT * FROM apartamente WHERE cod = '$cod'");
                if ($result && $result->num_rows > 0) {
                    $contract_agent_vanzator_name = $_FILES['contract_agent_vanzator']['name'];
                    $contract_vanzator_cumparator_name = $_FILES['contract_vanzator_cumparator']['name'];
                    $contract_dir = "../contracts/";
                    move_uploaded_file($_FILES['contract_agent_vanzator']['tmp_name'], $contract_dir . $contract_agent_vanzator_name);

                    move_uploaded_file($_FILES['contract_vanzator_cumparator']['tmp_name'], $contract_dir . $contract_vanzator_cumparator_name);

                    $apartament_row = $result->fetch_assoc();
                    $procent_comision = $apartament_row['comision'];

                    $comision = ($suma * $procent_comision) / 100;

                    $sql = "INSERT INTO vanzari (suma, comision, cod, id_agent, data_vanzare, detalii, contract_agent_vanzator, contract_vanzator_cumparator) VALUES ('$suma', '$comision', '$cod', '$id_agent', '$data_vanzare', '$detalii', '$contract_agent_vanzator_name', '$contract_vanzator_cumparator_name')";

                    if ($mysqli->query($sql) === TRUE) {
                        $success_message = "Vânzare adăugată cu succes!";
                    } else {
                        $error = "Eroare la adăugarea vânzării: " . $mysqli->error;
                    }
                } else {
                    $error = "Imi pare rau! Codul apartamentului $cod nu există!";
                }
            }
        } else {
            $error = 'Eroare la procesarea formularului!';
        }
    }

    if ($error != '') {
        echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
    }
    if ($success_message != '') {
        echo "<p>$success_message<p>";
        echo "<a href='vizualizare.php'>Vizualizare vânzări</a>";
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" <?php if ($success_message != '') echo 'class="hidden"'; ?>>
    <label for="suma">Sumă:</label><br>
    <input type="number" id="suma" name="suma" required><br>
    <label for="cod">Cod Apartament:</label><br>
    <select id="cod" name="cod" required>
        <?php
        $apartamente_query = "SELECT cod FROM apartamente";
        $apartamente_result = $mysqli->query($apartamente_query);
        if ($apartamente_result->num_rows > 0) {
            while ($apartamente_row = $apartamente_result->fetch_assoc()) {
                echo "<option value='" . $apartamente_row['cod'] . "'>" . $apartamente_row['cod'] . "</option>";
            }
        } else {
            echo "<option value=''>Nu există apartamente înregistrate</option>";
        }
        ?>
    </select><br>
    <label for="data_vanzare">Data Vanzare:</label><br>
    <input type="date" id="data_vanzare" name="data_vanzare" required><br>
    <label for="contract_agent_vanzator">Contract Agent-Vânzător:</label><br>
    <input type="file" id="contract_agent_vanzator" name="contract_agent_vanzator" required><br>
    <label for="contract_vanzator_cumparator">Contract Vânzător-Cumpărător:</label><br>
    <input type="file" id="contract_vanzator_cumparator" name="contract_vanzator_cumparator" required><br>
    <label for="detalii">Detalii:</label><br>
    <textarea id="detalii" name="detalii" rows="4" required></textarea><br>
    <label for="agent">Agent imobiliar:</label><br>
    <select id="agent" name="agent" required>
        <?php
        $agent_query = "SELECT id, nume, prenume FROM agent";
        $agent_result = $mysqli->query($agent_query);
        if ($agent_result->num_rows > 0) {
            while ($agent_row = $agent_result->fetch_assoc()) {
                echo "<option value='" . $agent_row['id'] . "'>" . $agent_row['nume'] . " " . $agent_row['prenume'] . "</option>";
            }
        } else {
            echo "<option value=''>Nu există agenți înregistrați</option>";
        }
        ?>
    </select><br>
    <input type="hidden" id="procent_comision" name="procent_comision" value="">
    <div class="buton">
        <input type="submit" name="submit" value="Adăugare vânzare"/>
    </div>
</form>

    
</div>
<script>
    document.getElementById('suma').addEventListener('input', function() {
        var suma = parseFloat(this.value);
        var procent_comision = parseFloat(document.getElementById('procent_comision').value);
        if (!isNaN(suma) && !isNaN(procent_comision)) {
            var comision = (suma * procent_comision) / 100;
            document.getElementById('comision').value = comision.toFixed(2);
        }
    });
</script>
</body>
</html>
