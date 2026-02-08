<?php
// Konfigurasi
$TOKEN = "8361148157:AAHmhu2nckYnbw2QWM2TsB2hOKLR4LknJMo";
$CHAT_ID = "8241472181";
$API_URL = "https://api.telegram.org/bot{$TOKEN}/";

// Ambil data dari Telegram
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// Pastikan ada pesan masuk
if (!isset($update["message"])) {
    exit;
}

$message = $update["message"];
$chat_id = $message["chat"]["id"];
$text = isset($message["text"]) ? $message["text"] : "";

// Fungsi untuk mengirim pesan
function send_message($chat_id, $text) {
    global $API_URL;
    $url = $API_URL . "sendMessage";
    
    $data = array(
        "chat_id" => $chat_id,
        "text" => $text
    );
    
    $options = array(
        "http" => array(
            "method" => "POST",
            "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
            "content" => http_build_query($data)
        )
    );
    
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    return $result;
}

// Proses pesan
if ($text == "/on") {
    send_message($chat_id, "✅ Bot sudah ON!");
} elseif ($text == "/off") {
    send_message($chat_id, "❌ Bot sudah OFF!");
} elseif ($text == "/start") {
    send_message($chat_id, "👋 Halo! Saya adalah bot Telegram Anda.\n\nPerintah tersedia:\n/on - Hidupkan bot\n/off - Matikan bot\n/help - Bantuan");
} elseif ($text == "/help") {
    send_message($chat_id, "📚 Perintah yang tersedia:\n\n/on - Hidupkan bot\n/off - Matikan bot\n/start - Mulai ulang\n/help - Bantuan");
} else {
    send_message($chat_id, "Anda kirim: " . $text);
}

http_response_code(200);
?>