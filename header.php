<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEADER</title>
    <style>
        .buton-antet {
            margin-top: 10px;
            overflow: auto; 
        }
        .buton-antet a, .buton-antet button {
            display: block;
            margin-bottom: 10px; 
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            background-color: #f4f4f4;
            transition: background-color 0.3s; 
            max-width: 50px;
            width: 100%; 
        }
        .buton-antet a:hover, .buton-antet button:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<div class="buton-antet">
    <a href="../paginaprincipala.php">Meniu</a>
    <a href="../logout.php">LogOut</a>
</div>

</body>
</html>
