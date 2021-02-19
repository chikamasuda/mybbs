$("#submit").click(function(){
  if ($("input[type='text']").val() == '' || $("textarea[name='text']").val() == '') {
    alert('必須項目(投稿者名、タイトル、本文、削除キー）が未記入です。');
  } else if ($("#name").val().length > 255 || $("#title").val().length > 255) {
  　alert('投稿者名とタイトルは255文字以内で入力してください。');
  } else {
    $("#submit").submit();
  }
});
