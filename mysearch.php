<?php
include "conectare.php";

if(isset($_POST['text'])){

    $msg = $mysqli->real_escape_string($_POST['text']);

    $query = $mysqli->query("SELECT * FROM intrebari WHERE intrebare LIKE '%$msg%'");

    if (!$query) {
        die('Eroare la interogare: ' . $mysqli->error);
    }

    $count = $query->num_rows;

    $server_time = date("Y-m-d H:i:s");

    if($count == 0){
        $data = "Nu sunt în totalitate clar cu ceea ce îmi soliciți. Poți reformula?";
    } else {
        $row = $query->fetch_assoc();
        $data = $row['raspuns'];
    }
    
    $query4 = $mysqli->query("INSERT INTO chats (user, chatbot, date) VALUES ('$msg', '$data', '$server_time')");
    if (!$query4) {
        die('Eroare la inserarea datelor: ' . $mysqli->error);
    }
    
    echo $data;
}
?>