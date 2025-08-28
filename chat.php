<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>ChatGPT Clone</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f7f7f8;
    margin: 0;
}
.header {
    background: #10a37f;
    color: white;
    padding: 10px;
    text-align: center;
}
.chat-container {
    max-width: 800px;
    margin: auto;
    padding: 10px;
}
.messages {
    background: white;
    padding: 10px;
    height: 500px;
    overflow-y: auto;
    border-radius: 8px;
}
.message {
    margin: 5px 0;
    padding: 8px;
    border-radius: 5px;
}
.user {
    background: #e3f2fd;
    align-self: flex-end;
}
.bot {
    background: #f1f1f1;
}
.input-container {
    display: flex;
    margin-top: 10px;
}
.input-container input {
    flex: 1;
    padding: 10px;
}
.input-container button {
    background: #10a37f;
    color: white;
    padding: 10px;
    border: none;
}
.typing {
    font-style: italic;
    color: gray;
}
</style>
</head>
<body>
<div class="header">
    ChatGPT Clone - Logged in as <?php echo $_SESSION['username']; ?> | <a href="logout.php" style="color:white;">Logout</a>
</div>
<div class="chat-container">
    <div class="messages" id="messages"></div>
    <div class="input-container">
        <input type="text" id="userInput" placeholder="Type your message...">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>
 
<script>
function sendMessage(){
    let input = document.getElementById("userInput");
    let msg = input.value.trim();
    if(msg === "") return;
 
    let messagesDiv = document.getElementById("messages");
    messagesDiv.innerHTML += `<div class='message user'>${msg}</div>`;
    input.value = "";
 
    let typingDiv = document.createElement("div");
    typingDiv.classList.add("message", "bot", "typing");
    typingDiv.innerText = "AI is typing...";
    messagesDiv.appendChild(typingDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
 
    fetch("send.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "message=" + encodeURIComponent(msg)
    })
    .then(res => res.text())
    .then(data => {
        typingDiv.remove();
        messagesDiv.innerHTML += `<div class='message bot'>${data}</div>`;
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    })
}
</script>
</body>
</html>
 
