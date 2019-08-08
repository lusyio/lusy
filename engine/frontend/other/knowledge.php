<link href="/assets/css/quill.snow.css" rel="stylesheet">
<div>
    <p><a href="#" id="addNewArticle" class="btn btn-outline-primary">Добавить статью</a></p>
</div>

<div class="accordion" id="accordionKnowledge">
    <?php foreach ($knowledgeArticles as $article): ?>
    <div class="card">
        <div class="card-header" id="heading<?=$article['article_id'] ?>">
            <h2 class="mb-0">
                <button class="btn btn-link article-title" type="button" data-toggle="collapse" data-target="#collapse<?=$article['article_id'] ?>" aria-expanded="true" aria-controls="collapseOne">
                    <?=$article['article_name'] ?>
                </button>
            </h2>
        </div>
        <div id="collapse<?=$article['article_id'] ?>" class="collapse" aria-labelledby="heading<?=$article['article_id'] ?>" data-parent="#accordionKnowledge">
            <div class="card-body article-content">
                <?=$article['article_text'] ?>
            </div>
            <div class="text-right">
                <a href="#" class="edit-article text-muted" data-article-id="<?=$article['article_id'] ?>">Редактировать</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
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
            $('.collapse').collapse('hide');
            $('#articleId').val('');
            $('#articleTitle').val('');
            $('.ql-editor').html('');
        });

        $('.edit-article').on('click', function (e) {
            e.preventDefault();
            $('.collapse').collapse('hide');
            $('#articleId').val($(this).data('article-id'));
            $('#articleTitle').val($(this).closest('.card').find('.article-title').text().trim());
            $('.ql-editor').html($(this).closest('.card').find('.article-content').html().trim());
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