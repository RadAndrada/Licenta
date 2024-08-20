<?php
include "conectare.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        body{
            font-family: Arial, sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            margin: 0 auto;
            max-width: 800px;
            padding: 0 20px;
        }
        .container1{
            border: 2px solid #dedede;
            background-color: #f1f1f1;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
        }
        .darker{
            border-color: #ccc;
            background-color: #ddd;
        }
        .container1::after{
            content: "";
            clear: both;
            display: table;
        }
        .container1 img{
            float: left;
            max-width: 50px;
            width: 100%;
            margin-right: 20px;
            border-radius: 50%;
        }
        .container1 img.right{
            float: right;
            margin-left: 20px;
            margin-right:0;
        }
        .time-right{
            float: right;
            color:#aaa;
        }
        .time-left{
            float: left;
            color:#999;
        }
        .div.sticky{
            position: sticky;
            bottom:0;
            margin-top: 10px; 
            background-color: #fff;
            padding: 10px 0 0 10px;
            font-size: 20px;
        }
        .square{
            margin-top: 80px;
            height: auto;
            width: 810px;
            padding: 8px;
            background-color: rgba(255, 255, 255, 0.5); 
            border: 2px solid #dedede;
        }
        .message-group {
            margin-bottom: 10px;
        }
        .question {
            width: 70%;
            float: left;
        }
        .answer {
            width: 55%;
            float: right;
        }
        .btn-container {
            text-align: center; 
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.6);
            color: #444;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #e5e5e5;
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
    <br>
<h3 class="EstateCluj">Estate Cluj</h3><br>
    
<div class="btn-container">
    <a href="panouclient.php" class="btn">Înapoi</a>
    <a href="index.php" class="btn">Despre</a>
    <a href="portofoliu.php" class="btn">Portofoliu</a>
    <a href="../Licenta/Clienti/logoutclient.php" class="btn">LogOut</a>
    
</div>
<div class="square">
    <h3 style="text-align: center;">Dialog Interactiv</h3>
    <br/>
    <div id="ref">
    </div>

    <div class="sticky">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="msg">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="send()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        getChatMessages();
    });

    function getChatMessages(){
        $.ajax({
            type: "post",
            url: "get_chat_messages.php",
            success: function(data){
                $("#ref").html(data);
                scrollToBottom(); 
            }
        });
    }

    function send() {
        var text = $('#msg').val().toLowerCase();
        
        $.ajax({
            type: "post",
            url: "mysearch.php",
            data: { text: text },
            success: function (response) {
                appendMessage(text); 
                appendResponse(response);
                scrollToBottom(); 
                $('#msg').val(''); 
            },
            error: function () {
                alert('A apărut o eroare la trimiterea mesajului.');
            }
        });
    }

    function appendMessage(text) {
        if (text.trim() !== '') {
            var message = '<div class="container1">' +
                            '<div class="question">' +
                                '<p>' + text + '</p>' +
                                '<span class="time-left">' + getCurrentTime() + '</span>' +
                            '</div>' +
                        '</div>';
            $('#ref').append(message);
        }
    }

    function appendResponse(response) {
        var message = '<div class="container1">' +
                        '<div class="answer">' +
                            '<p><strong>ChatBot:</strong> ' + response + '</p>' +
                            '<span class="time-right">' + getCurrentTime() + '</span>' +
                        '</div>' +
                    '</div>';
        $('#ref').append(message);
    }

    function scrollToBottom() {
        var chatPanel = document.getElementById("ref");
        chatPanel.scrollTop = chatPanel.scrollHeight;
    }

    function getCurrentTime() {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        return hours + ':' + minutes + ':' + seconds;
    }
</script>

</body>
</html>
