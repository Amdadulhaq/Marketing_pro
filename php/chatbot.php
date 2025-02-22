<?php
include __DIR__ . '/../database/db_connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize session for chat history
if (!isset($_SESSION['chat'])) {
    $_SESSION['chat'] = [];
}

$botResponse = "";

// Process only if there is a new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_input'])) {
    $userMessage = strtolower(trim($_POST['user_input']));

    // Prevent re-processing the same message on refresh
    if (!isset($_SESSION['last_input']) || $_SESSION['last_input'] !== $userMessage) {
        $_SESSION['last_input'] = $userMessage; // Store last input to prevent duplicate submissions

        // Check for "schedule" or "appointment" keywords
        if (strpos($userMessage, 'schedule') !== false || strpos($userMessage, 'appointment') !== false) {
            $botResponse = '<p>Click the link below to schedule an appointment:</p>
            <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfr1Nyq4Opes3q94Ti_1TMuRZFAbMvo2xZAKCYn52_ge02tgg/viewform?embedded=true" width="100%" height="500px" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>';
        
        } else {
            // Fetch bot response from the database
            $stmt = $conn->prepare("SELECT bot_response FROM chatbot WHERE user_input = ?");
            $stmt->bind_param("s", $userMessage);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($botResponse);
            $stmt->fetch();

            if ($stmt->num_rows == 0) {
                $botResponse = "I'm sorry, I don't understand that. Can you try something else?";
            }

            $stmt->close();
        }

        // Store chat history in session
        $_SESSION['chat'][] = ['user', $userMessage];
        $_SESSION['chat'][] = ['bot', $botResponse];

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
/* Chatbot Container */
.chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 370px;
    z-index: 1000;
}

/* Toggle Chatbot Button */
.toggle-chatbot {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    display: block;
    width: 100%;
    text-align: center;
}

/* Chatbox Styling */
.chatbox {
    display: none;
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    width: 100%;
    height: 450px;
    overflow-y: auto;
    padding: 10px;
}

/* Chat Message Container */
.form {
    height: 350px;
    overflow-y: auto;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

/* Input Field */
.typing-field {
    padding: 10px;
    display: flex;
    justify-content: space-between;
}

.input-data input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.input-data button {
    padding: 8px 12px;
    margin-left: 5px;
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

/* Chat Messages Styling */
.message {
    display: block;
    margin: 5px 0;
    padding: 10px;
    border-radius: 8px;
    max-width: 80%;
    word-wrap: break-word;
}

.user-message {
    background-color: #007bff;
    color: white;
    text-align: right;
    margin-left: auto;
}

.bot-message {
    background-color: #f1f1f1;
    color: black;
    text-align: left;
    margin-right: auto;
}
    </style>
</head>
<body>

    <!-- Chatbot Container -->
    <div class="chatbot-container">
        <button class="toggle-chatbot">Chat with Us</button>
        <div class="chatbox wrapper">
            <div class="title">Chatbot</div>
            <div class="form" id="chatArea">
                <div class="bot-inbox inbox">
                    <div class="msg-header"><p>Hello! How can I assist you today?</p></div>
                </div>

                <!-- Display Previous Chat History -->
                <?php if (!empty($_SESSION['chat'])): ?>
                    <?php foreach ($_SESSION['chat'] as $chat): ?>
                        <div class="<?php echo $chat[0] === 'user' ? 'user-message' : 'bot-message'; ?>">
                            <?php echo $chat[1]; // Remove htmlspecialchars() to render HTML properly ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
            <form method="POST" class="typing-field">
                <div class="input-data">
                    <input type="text" name="user_input" id="userInput" placeholder="Type something..." required>
                    <button type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    var chatbox = document.querySelector(".chatbox");
    var chatArea = document.getElementById("chatArea");
    var toggleButton = document.querySelector(".toggle-chatbot");

    // Keep chat open after submission
    if (sessionStorage.getItem("chatbox_open") === "true") {
        chatbox.style.display = "block";
    }

    toggleButton.addEventListener("click", function () {
        if (chatbox.style.display === "none" || chatbox.style.display === "") {
            chatbox.style.display = "block";
            sessionStorage.setItem("chatbox_open", "true");
        } else {
            chatbox.style.display = "none";
            sessionStorage.setItem("chatbox_open", "false");
        }
    });

    // Auto-scroll to latest message
    chatArea.scrollTop = chatArea.scrollHeight;
});
</script>

</body>
</html>
