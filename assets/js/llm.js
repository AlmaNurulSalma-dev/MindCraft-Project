$(document).ready(function () {
  $("#chat-form").on("submit", function (e) {
    e.preventDefault();

    const userInput = $("#user-input").val().trim();
    if (!userInput) return;

    $("#chat-box").append(`<div class="message user">${userInput}</div>`);
    $("#user-input").val("");
    $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);

    $.ajax({
      url: "/MindCraft-Project/api/llm_chat.php",
      method: "POST",
      data: { message: userInput },
      success: function (res) {
        try {
          const data = JSON.parse(res);
          $("#chat-box").append(`<div class="message bot">${data.reply}</div>`);
        } catch (error) {
          $("#chat-box").append(
            `<div class="message bot">Terjadi kesalahan dalam memproses jawaban.</div>`
          );
        }
        $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
      },
      error: function () {
        $("#chat-box").append(
          `<div class="message bot">Maaf, koneksi ke AI gagal.</div>`
        );
        $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
      },
    });
  });

  $(document).on("click", ".option-card", function () {
    const text = $(this).text();
    $("#user-input").val(text).focus();
  });

  $("#refresh-chat").click(function () {
    $("#chat-box").html(
      '<div class="message bot">Halo! Saya MindBot. Ingin belajar apa hari ini? 😊</div>'
    );
  });
});
