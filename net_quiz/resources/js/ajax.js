
export const ajax = function () {
  $('#favorite_button').on('click', function () {
    let a = $(this);
      $.ajax({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: a.attr('title'), 
        //formのaction要素を参照
        type: "get",  //formのmethod要素を参照
        // data: form.serialize(),     //formで送信している内容を送る
      })
      
        //通信が成功した時
        .done((res) => {
          console.log("成功");
          if (res == 1) {
            // 黄色にする
            $(this).css("color", "yellow");
            console.log("お気に入りに追加されました");
        } else if (res == 0) {
            // 色をなくす（背景色をリセットし、文字色を赤にする）
            $(this).css("color", ""); // 色をリセット
            console.log("お気に入りから外れました");
        }
          // if (res[0] == 400) {
          //   // 変化なし
          // } else if (res[0] == 1) {
          //   a.find(".check_on").removeClass("hidden");
          //   a.find(".check_out").addClass("hidden");
          // } else if (res[0] == 0) {
          //   a.find(".check_on").addClass("hidden");
          //   a.find(".check_out").removeClass("hidden");
          // }
  
  
          // $(".x-message").addClass("hidden");
          // $(".x-input-error").addClass("hidden");
          // $("#message_ajax").parent().parent().removeClass("hidden");
          // $("#message_ajax").removeClass("hidden");
          // $("#message_ajax").html(res[1]);
  
        })
        //通信が失敗したとき
        .fail((error) => {
          console.log("失敗");
  
        })
    });


  }