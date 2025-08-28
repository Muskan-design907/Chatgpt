<?php
session_start();
require_once 'db.php';
 
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "Not logged in.";
    exit;
}
 
$message = trim($_POST['message'] ?? '');
if ($message === '') {
    echo "Empty message.";
    exit;
}
 
// Store user message
$stmt = $pdo->prepare("INSERT INTO chat_messages (user_id, role, content) VALUES (?, 'user', ?)");
$stmt->execute([$user_id, $message]);
 
// Send to OpenAI API
$apiKey = "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";
$url = "https://api.openai.com/v1/chat/completions";
 
$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [["role" => "user", "content" => $message]],
    "max_tokens" => 100
];
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
 
$response = curl_exec($ch);
if (curl_errno($ch) || !$response) {
    // Fallback fake response
    $aiReply = "I'm your AI assistant. (Placeholder response)";
} else {
    $resData = json_decode($response, true);
    $aiReply = $resData['choices'][0]['message']['content'] ?? "I'm your AI assistant. (Fallback)";
}
curl_close($ch);
 
// Store AI reply
$stmt = $pdo->prepare("INSERT INTO chat_messages (user_id, role, content) VALUES (?, 'assistant', ?)");
$stmt->execute([$user_id, $aiReply]);
 
echo nl2br(htmlspecialchars($aiReply));
?>
 
