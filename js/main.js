$("#submit").click(function(){
  if ($("input[type='text']").val() == '') {
    alert('必須項目が未記入です。');
  } else if ($("textarea[name='text']").val() == '') {
  　alert('本文を入力してください'); 
  } else if ($("#name").val().length > 255) {
  　alert('投稿者名は255文字以内で入力してください。');
  } else if ($("#title").val().length > 255) {
    alert('タイトルは255文字以内で入力してください。');
  } else {
    $("#submit").submit();
  }
});
