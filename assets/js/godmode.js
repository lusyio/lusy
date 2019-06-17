$(document).ready(function () {
    $('.show-preview-modal').on('click', function () {
        if ($(this).data('preview-type') == 'mail') {
            var modalContent = $('#mailHeader').val();
            var modalContent = modalContent + $('#mailBody').val();
            var modalContent = modalContent + $('#mailFooter').val();
            $('#previewModal').find('.modal-body').html(modalContent);
        } else if ($(this).data('preview-type') == 'article') {
            var modalContent = $('#articleText').val();
            $('#previewModal').find('.modal-body').html(modalContent);
        }
        $('#previewModal').modal('show');
    });

    $('.edit-mail-body').on('click', function () {
        $('#mailBody').val($(this).closest('.mail-template').find('.mail-body').html());
        $('.body-filename').val($(this).closest('.mail-template').find('.mail-template-name').text() );
        $('#bodyFileName').val($(this).closest('.mail-template').find('.mail-template-name').text() );
    });

    $('.edit-article').on('click', function (e) {
        e.preventDefault();
        var $parentDiv = $(this).closest('.article');
        $('#articleId').val($parentDiv.data('article-id'));
        $('#articleTitle').val($parentDiv.find('.article-name').text().trim());
        $('#articleUrl').val($parentDiv.find('.article-url').text().trim());
        $('#articleCategory').val($parentDiv.find('.article-category').text().trim());
        $('#articleDescription').val($parentDiv.find('.article-description').html().trim());
        $('#articleText').val($parentDiv.find('.article-text').html().trim());
        $('#articleDate').val($parentDiv.find('.article-date').text().trim());
    });

    $('.show-article-text').on('click', function (e) {
        e.preventDefault();
        var text = $(this).closest('.article').find('.article-text');
        if (text.hasClass('d-none')) {
            text.removeClass('d-none');
        } else {
            text.addClass('d-none')
        }
    });

    $('#sendArticle').on('click', function (e) {
        e.preventDefault();
        if ($('#articleId').val() == '0') {
            var module = 'addArticle'
        } else {
            var module = 'updateArticle'
        }
        $.ajax({
            url: '/ajax.php',
            type: 'POST',
            cache: false,
            data: {
                ajax: 'godmode',
                module: module,
                articleId: $('#articleId').val(),
                articleTitle: $('#articleTitle').val(),
                articleUrl: $('#articleUrl').val(),
                articleCategory: $('#articleCategory').val(),
                articleDescription: $('#articleDescription').val(),
                articleText: $('#articleText').val(),
                articleDate: $('#articleDate').val(),
            },
            success: function (response) {
                if (response === '1') {
                    $('#addArticle')[0].reset();
                } else {
                    console.log('Error');
                    console.log(response);
                }
            }
        });
    })
});