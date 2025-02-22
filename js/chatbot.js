$(document).ready(function () {
    $(".toggle-chatbot").click(function () {
        $(".chatbox").toggle();
    });

    $("#sendBtn").click(function () {
        let userMessage = $("#userInput").val().trim();
        if (userMessage === "") return;

        $("#chatArea").append(`<div class="user-inbox inbox">
            <div class="msg-header user-msg"><p>${userMessage}</p></div>
        </div>`);

        $.ajax({
            url: "../php/message.php",
            method: "POST",
            data: { message: userMessage },
            success: function (response) {
                setTimeout(() => {
                    $("#chatArea").append(`<div class="bot-inbox inbox">
                        <div class="msg-header bot-msg"><p>${response}</p></div>
                    </div>`);
                    $("#chatArea").scrollTop($("#chatArea")[0].scrollHeight);
                }, 500);
            }
        });

        $("#userInput").val("");
    });
});
