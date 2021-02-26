$(function () {
    $("form").submit(function () {
        //バリデーション
        const stringMaxSize = 255;
        let error = false;

        if ($("#name").val() === "") {
            $("<p class='text-danger'>投稿者名が未記入です。</p>").insertAfter("#name");
            error = true;

        } else if ($("#name").val().length > stringMaxSize) {
            $("<p class='text-danger'>投稿者名は255文字以内で記入してください。</p>").insertAfter("#name");
            error = true;
        };

        if ($("#title").val() === "") {
            $("<p class='text-danger'>タイトルが未記入です。</p>").insertAfter("#title");
            error = true;

        } else if ($("#title").val().length > stringMaxSize) {
            $("<p class='text-danger'>タイトルは255文字以内で記入してください。</p>").insertAfter("#title");
            error = true;
        };

        if ($("#text").val() === "") {
            $("<p class='text-danger'>本文が未記入です。</p>").insertAfter("#text");
            error = true;
        };

        if ($("#delete_key").val() === "") {
            $("<p class='text-danger'>削除キーが未記入です。</p>").insertAfter("#delete_key");
            error = true;

        } else if ($("#delete_key").val().length > stringMaxSize) {
            $("<p class='text-danger'>削除キーは255文字以内で記入してください。</p>").insertAfter("#delete_key");
            error = true;
        };

        if (error) {
            return false;
        }
    });

});
