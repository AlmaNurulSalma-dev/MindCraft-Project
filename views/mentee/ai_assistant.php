<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>MindBot - AI Assistant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MindCraft-Project/assets/css/llm_chat.css">
</head>

<body>

    <div class="chatbot-container">
        <div class="chatbot-header">
            <h1>👋 Hi, MindBot here!</h1>
            <p>What would you like to learn today?</p>
            <div class="greeting-options">
                <div class="option-card">Bantu temukan jalur belajar terbaik!</div>
                <div class="option-card">Ingin belajar skill tradisional yang unik?</div>
                <div class="option-card">Rekomendasikan course berdasarkan minatmu</div>
            </div>
        </div>

        <div id="chat-box" class="chat-box">
            <div class="message bot">Halo! Saya MindBot. Ingin belajar apa hari ini? 😊</div>
        </div>

        <form id="chat-form" class="chat-form">
            <input type="text" id="user-input" class="chat-input" placeholder="Tulis pertanyaanmu di sini..." autocomplete="off">
            <button type="submit" class="chat-submit">Kirim</button>
            <button type="button" id="refresh-chat" class="refresh-button"> ⟳ </button>
        </form>
    </div>

    <!-- Bubble kembali -->
    <a href="/MindCraft-Project/views/mentee/dashboard.php" class="bubble-button" title="Kembali ke Dashboard"></a>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/MindCraft-Project/assets/js/llm.js"></script>

</body>

</html>