<?php
include("conectare.php");

$cartiereQuery = "SELECT DISTINCT cartier FROM apartamente WHERE disponibilitate = 'Disponibil' ORDER BY cartier";
$cartiereResult = mysqli_query($mysqli, $cartiereQuery);
if (!$cartiereResult) {
    die("Eroare la extragerea cartierelor: " . mysqli_error($mysqli));
}

$cartiere = [];
while ($cartier = mysqli_fetch_assoc($cartiereResult)) {
    $cartiere[] = $cartier['cartier'];
}

$agentiQuery = "SELECT id, nume, prenume FROM agent";
$agentiResult = mysqli_query($mysqli, $agentiQuery);
if (!$agentiResult) {
    die("Eroare la extragerea agenților: " . mysqli_error($mysqli));
}

$agenti = [];
while ($agent = mysqli_fetch_assoc($agentiResult)) {
    $agenti[] = $agent;
}

$query = "SELECT cod, titlu, pret, fotografie0 FROM apartamente WHERE disponibilitate = 'Disponibil'";

$filter_conditions = [];

if (isset($_GET['filter']) && is_array($_GET['filter'])) {
    foreach ($_GET['filter'] as $filter) {
        if ($filter === 'apartamente') {
            $filter_conditions[] = "titlu LIKE '%Apartament%'";
        } elseif ($filter === 'case') {
            $filter_conditions[] = "titlu LIKE '%Casa%'";
        } elseif ($filter === 'spatii') {
            $filter_conditions[] = "titlu LIKE '%Spatiu%'";
        } elseif ($filter === 'inchirieri') {
            $filter_conditions[] = "tip_apartament = 'inchirieri'";
        } elseif ($filter === 'vanzari') {
            $filter_conditions[] = "tip_apartament = 'vanzari'";
        } elseif (strpos($filter, 'agent_') === 0) {
            $agent_id = substr($filter, 6);
            $filter_conditions[] = "id_agent = " . intval($agent_id);
        }
    }
}

if (isset($_GET['min_price'], $_GET['max_price']) && $_GET['min_price'] !== '' && $_GET['max_price'] !== '') {
    $min_price = mysqli_real_escape_string($mysqli, $_GET['min_price']);
    $max_price = mysqli_real_escape_string($mysqli, $_GET['max_price']);
    $filter_conditions[] = "pret BETWEEN $min_price AND $max_price";
}

if (isset($_GET['cartier']) && $_GET['cartier'] !== '' && in_array($_GET['cartier'], $cartiere)) {
    $cartier = mysqli_real_escape_string($mysqli, $_GET['cartier']);
    $filter_conditions[] = "cartier = '$cartier'";
}

if (isset($_GET['camere']) && $_GET['camere'] !== '') {
    $camere = mysqli_real_escape_string($mysqli, $_GET['camere']);
    $filter_conditions[] = "camere = '$camere'";
}

if (!empty($filter_conditions)) {
    $query .= " AND (" . implode(' AND ', $filter_conditions) . ")";
}

$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Eroare la interogare: " . mysqli_error($mysqli));
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofoliul meu de proprietăți</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg'); 
            background-size: cover;
            background-position: center;
            padding: 20px;
            margin: 0; 
        }

        .antet {
            color: #e0e0e0;
            padding: 20px; 
            background-color: rgba(0, 0, 0, 0.5); 
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            margin-bottom: 20px;
        }

        .antet a {
            display: inline-block;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #444;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 10px 5px auto; 
            height: 30px; 
        }

        .antet a:hover {
            background-color: #e5e5e5;
        }

        .container-filtre {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .container-filtre > div {
            margin-right: 20px;
        }

        .container-filtre label {
            margin-right: 15px; 
        }

        .container-filtre select {
            margin-right: 15px;
        }

        .proprietati {
            margin: 0 30px; 
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .partepropretate {
            width: 400px; 
            margin: 0 10px 20px;
            background-color: #fff;
            border: 2px solid #444;
            border-radius: 12px;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.8); 
        }

        .partepropretate img {
            max-width: 100%;
            max-height: 200px; 
            margin-bottom: 20px; 
        }

        .partepropretate-titlu {
            font-size: 20px; 
            font-weight: bold; 
            color: #444;
            margin-bottom: 10px;
        }

        .pret {
            font-size: 18px;
            font-weight: bold;
            color: #008000;
            margin-bottom: 20px; 
        }

        .detalii {
            display: block;
            background-color: rgba(255, 255, 255, 0.8);
            color: #444;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .detalii:hover {
            background-color: #CCCCCC;
        }

        .subsol {
            font-size: 20px;
            color: #e0e0e0;
            padding: 20px; 
            background-color: rgba(0, 0, 0, 0.5); 
            text-align: center; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            margin-top: 20px;
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

        .filtre-buton {
            display: inline-block;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #444;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 10px 5px auto; 
            height: 50px;
            font-size: 15px;
        }

        .filtre-buton:hover {
            background-color: #e5e5e5;
        }
        .EstateCluj{
            font-size: 48px;
            font-weight: bold;
            color: #ffffff; 
            text-shadow: 0 0 10px #ffffff; 
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="antet">
    <h3 class="EstateCluj">Estate Cluj</h3><br>
    <h1>Oferte recomandate pentru tine</h1>
    <h2>Portofoliul meu îți oferă cele mai moderne proprietăți din Cluj-Napoca, renegociate periodic</h2>
    <a href="index.php" style="float: right;">Echipa</a>
    <form action="portofoliu.php" method="get" class="container-filtre">
        <div>
            <label for="tip_proprietate">Tipul proprietății:</label>
            <select id="tip_proprietate" name="filter[]">
                <option value="">Alege tipul de proprietate</option>
                <option value="apartamente">Apartamente</option>
                <option value="case">Case</option>
                <option value="spatii">Spații comerciale</option>
            </select>
        </div>

        <div>
            <label for="tip_tranzactie">Tipul tranzacției:</label>
            <select id="tip_tranzactie" name="filter[]">
                <option value="">Alege tranzacția dorita</option>
                <option value="inchirieri">Închiriere</option>
                <option value="vanzari">Vânzare</option>
            </select>
        </div>

        <div>
            <label for="cartier">Cartier:</label>
            <select id="cartier" name="cartier">
                <option value="">Toate cartierele disponibile</option>
                <?php foreach ($cartiere as $cartier): ?>
                    <option value="<?php echo htmlspecialchars($cartier); ?>">
                        <?php echo htmlspecialchars($cartier); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="camere">Număr de camere:</label>
            <select id="camere" name="camere">
                <option value="">Toate</option>
                <option value="1">1 cameră</option>
                <option value="2">2 camere</option>
                <option value="3">3 camere</option>
                <option value="4">4 camere</option>
                <option value="5">5 camere</option>
            </select>
        </div>

        <div>
            <label for="agent">Agent:</label>
            <select id="agent" name="filter[]">
                <option value="">Alege agentul imobiliar</option>
                <?php foreach ($agenti as $agent): ?>
                    <option value="agent_<?php echo $agent['id']; ?>">
                        <?php echo htmlspecialchars($agent['nume'] . ' ' . $agent['prenume']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="min_price">Preț minim:</label>
            <input type="number" id="min_price" name="min_price" min="0" placeholder="Min"><br>
            <label for="max_price">Preț maxim:</label>
            <input type="number" id="max_price" name="max_price" min="0" placeholder="Max">
        </div>

        <button type="submit" class="filtre-buton">Caută</button>
        <a href="portofoliu.php" class="filtre-buton">Elimină filtrele</a>
    </form>
</div>

<div class="proprietati">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="partepropretate">
            <img src="poze_apartamente/<?php echo htmlspecialchars($row['fotografie0']); ?>" alt="Imagine proprietate">
            <h3 class="partepropretate-titlu"><?php echo htmlspecialchars($row['titlu']); ?></h3>
            <p class="pret"><?php echo htmlspecialchars($row['pret']); ?> €</p>
            <a href="detalii_apartament.php?cod=<?php echo $row['cod']; ?>" class="detalii">Mai multe detalii</a>
        </div>
    <?php endwhile; ?>
</div>

<div class="subsol">
    <p>Dacă dorești să porți o conversație cu agentul nostru virtual, accesează:</p>
    <a href="Clienti/logclient.php" class="buton">Agent virtual</a><br>
    <p>Îți place un anume apartament?</p>
    <a href="Clienti/logclient.php" class="buton">Fă-ți o programare!</a>
</div>
</body>
</html>

