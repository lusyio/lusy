<link href="/assets/css/quill.snow.css" rel="stylesheet">
<div>
    <p><a href="#" id="addNewArticle" class="btn btn-outline-primary">Добавить статью</a></p>
</div>
<?php foreach ($knowledgeArticles as $article): ?>
<div>
    <h2 class="article-title"><?=$article['article_name'] ?></h2>
    <div class="article-content d-none">
        <p><?=$article['article_text'] ?></p>
    </div>
    <div class="text-right">
        <a href="#" class="edit-article text-muted" data-article-id="<?=$article['article_id'] ?>">Редактировать</a>
    </div>
</div>
<hr>
<?php endforeach; ?>
<form class="pt-5">
    <input type="text" id="articleTitle" class="form-control mb-2" placeholder="Заголовок">
    <input type="hidden" id="articleId" class="form-control mb-2">
    <div class="border-0" id="articleText"></div>
    <button class="form-control btn btn-outline-success mb-2" id="saveArticle">Сохранить</button>
</form>
<script type="text/javascript" src="/assets/js/quill.js"></script>
<script>
    $(document).ready(function () {
        var quill = new Quill('#articleText', {
            theme: 'snow',
            placeholder: 'Текст статьи',
        });

        $('#addNewArticle').on('click', function (e) {
            e.preventDefault();
            window.scrollTo(0, $(document).height());
            $('#articleId').val('');
            $('#articleTitle').val('');
            $('.ql-editor').html('');
        });
        $('.article-title').on('click', function (e) {
            e.preventDefault();
            var el = $(this).siblings('.article-content');
            if ($(el).hasClass('d-none')) {
                $(el).removeClass('d-none');
            } else {
                $(el).addClass('d-none')
            }
        });

        $('.edit-article').on('click', function (e) {
            e.preventDefault();
            window.scrollTo(0, $(document).height());
            $('#articleId').val($(this).data('article-id'));
            $('#articleTitle').val($(this).closest('div').siblings('.article-title').text().trim());
            $('.ql-editor').html($(this).closest('div').siblings('.article-content').html().trim());
        });
        $('#saveArticle').on('click', function (e) {
            e.preventDefault();
            var title = $('#articleTitle').val();
            var text = $('.ql-editor').html();
            var articleId = $('#articleId').val();

            var fd = new FormData;
            fd.append('ajax','godmode');
            fd.append('articleTitle', title);
            fd.append('articleText', text);
            if (articleId === '') {
                fd.append('module', 'addKnowledgeArticle');
            } else {
                fd.append('articleId', articleId);
                fd.append('module', 'updateKnowledgeArticle');
            }
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    console.log(response);
                    if (response === '1') {
                        location.reload();
                    }
                }
            });
        })
    })
</script>