<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body pb-2">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-inline-block">
                        <div id="taskSearch" data-type="task" class="btn btn-secondary type-search words-search">
                            <span>Задачи</span>
                            <span class="count"></span>
                        </div>
                        <div id="commentSearch" data-type="comment" class="btn btn-secondary type-search words-search">
                            <span>Комментарии</span>
                            <span class="count"></span>
                        </div>
                        <div id="newSearch" class="btn btn-secondary view-status-search words-search">
                            <span>Показать новые</span>
                            <span class="count"></span>
                        </div>
                        <div id="allSearch" class="btn btn-secondary view-status-search words-search active">
                            <span>Показать все</span>
                            <span class="count"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body pb-0 pt-0">
            <div id="log">
                <ul class="timeline" id="eventBox" style="bottom: 0px;">
                    <?php foreach ($events as $event): ?>
                        <?php renderEvent($event); ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</div>
<style>
    .new-event {
        background-color: #f3f7ff;
    }

    .event {
        -webkit-transition: all 1s ease-out; /** Chrome & Safari **/
        -moz-transition: all 1s ease-out; /** Firefox **/
        -o-transition: all 1s ease-out; /** Opera **/
        transition: all 1s ease-out;
    }
</style>
<script>
    var pageName = 'log';
    $(document).ready(function () {
        var action = window.location.hash.substr(1);

        $('#commentIcon').closest('a').on('click', function () {
            if (!$('#commentSearch').hasClass('active')) {
                $('#commentSearch').trigger('click');
            }
        });
        $('#notificationIcon').closest('a').on('click', function () {
            if (!$('#taskSearch').hasClass('active')) {
                $('#taskSearch').trigger('click');
            }
        });

        $('.type-search').on('click', function () {
            var el = $(this);
            if (el.hasClass('active')) {
                el.removeClass('active');
            } else {
                $('.type-search').removeClass('active');
                el.addClass('active');
            }
            filterEvents();
        });
        $('.view-status-search').on('click', function () {
            var el = $(this);
            $('.view-status-search').removeClass('active');
            el.addClass('active');
            filterEvents();
        })
        if (action === 'new-comments') {
            $('#commentSearch').trigger('click');
            $('#newSearch').trigger('click');
        }
        if (action === 'new-tasks') {
            $('#taskSearch').trigger('click');
            $('#newSearch').trigger('click');
        }
        if (action === 'comments') {
            $('#commentSearch').trigger('click');
        }
        if (action === 'tasks') {
            $('#taskSearch').trigger('click');
        }

        $('#eventBox').on('mouseover', '.new-event', function () {
            $(this).removeClass('new-event');
            var eventId = $(this).data('event-id');
            if ($(this).hasClass('comment')) {
                decreaseCommentCounter();
            }
            if ($(this).hasClass('task')) {
                decreaseTaskCounter();
            }
            console.log(eventId);

            markAsRead(eventId);
        })
    });

    function filterEvents() {
        var filter = $('.type-search.active').data('type') || 'event';
        var newFilter = $('#newSearch').hasClass('active');

        $('.event').each(function () {
            var el = $(this);
            el.show();
            if (newFilter && !el.hasClass('new-event')) {
                el.hide();
            }
            if (!el.hasClass(filter)) {
                el.hide();
            }
        })
    }

    function markAsRead(eventId) {
        $.post("/ajax.php", {module: 'markAsRead', eventId: eventId, ajax: 'log'});
    }

</script>
