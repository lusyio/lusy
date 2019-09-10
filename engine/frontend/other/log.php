<div class="mb-3">
    <div class="pb-2">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-inline-block sort-log">
                    <div id="allSearch" class="btn btn-light view-status-search active mb-2">
                        <span><i class="fas fa-align-justify mr-2"></i> <?= $_buttonLogShowAll ?></span>
                        <span class="count"></span>
                    </div>
                    <div id="newSearch" class="btn btn-light view-status-search mb-2">
                        <span><i class="fas fa-plus mr-2"></i> <?= $_buttonLogShowNew ?></span>
                        <span class="count"></span>
                    </div>
                    <div id="taskSearch" data-type="task" class="btn btn-light type-search mb-2">
                        <span><i class="far fa-clipboard mr-2"></i> <?= $_tasks ?></span>
                        <span class="count"></span>
                    </div>
                    <div id="commentSearch" data-type="comment" class="btn btn-light type-search mb-2">
                        <span><i class="far fa-comment mr-2"></i> <?= $_comments ?></span>
                        <span class="count"></span>
                    </div>
                    <div id="systemSearch" data-type="system-event" class="btn btn-light type-search mb-2">
                        <span><i class="fas fa-info mr-2"></i>Системные</span>
                        <span class="count"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-5">
    <div class="pb-0 pt-0">
        <div class="search-container" id="logPlug">
            <div class="search-empty">
                <p>Тут пока что пусто</p>
            </div>
        </div>
        <div id="log">
            <ul class="timeline" id="eventBox">
                <?php foreach ($events as $event): ?>
                    <?php renderEvent($event); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<div class="load-log position-absolute">
    <div id="loadLog" class="rounded-circle btn btn-light">
        <i class="fas fa-chevron-down"></i>
    </div>
</div>


<script>
    function insertPlug() {
        if ($('.event:visible').length === 0) {
            $('#logPlug').show();
            $('#log').addClass('delete-before')
        } else {
            $('#logPlug').hide();
            $('#log').removeClass('delete-before')
        }
    }

    var pageName = 'log';
    var num = 0;

    function loadLog() {
        num += 10;
        var log = $(".event:visible").slice(0, num);
        $('.event').hide();
        log.show();
    }

    function loadLogPage() {
        filterEvents();
        num += 10;
        var log = $(".event:visible").slice(0, num);
        $('.event').hide();
        log.show();
        var type = $('[data-type].active').attr('id');
        if (type === 'systemSearch') {
            type = 'system-event';
        }
        if (type === 'commentSearch') {
            type = 'comment';
        }
        if (type === 'taskSearch') {
            type = 'task';
        }
        if (type === undefined) {
            type = 'event';
        }
        if ($('.' + type + '').length - $('.' + type + ':visible').length <= 10) {
            $("#loadLog").hide();
        }
    }

    function hideLoadLog() {
        if ($(".event:visible").length < 10) {
            $("#loadLog").hide();
        } else {
            $("#loadLog").show();
        }
    }

    $(document).ready(function () {
        loadLog();
        $('#loadLog').on('click', function () {
            loadLogPage();
        });

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
            num = 0;
            loadLog();
            hideLoadLog();
            insertPlug();
        });
        $('.view-status-search').on('click', function () {
            var el = $(this);
            $('.view-status-search').removeClass('active');
            el.addClass('active');
            filterEvents();
            num = 0;
            loadLog();
            hideLoadLog();
            insertPlug();
        });

        $('[href="/log/#tasks"]').on('click', function () {
            var val = $('.topsidebar-noty-content').attr('href');
            if (val == '/log/#new-tasks'){
                $('#newSearch').trigger('click');
                $('#taskSearch').trigger('click');
                $('#taskSearch').addClass('active');
                $('#systemSearch').addClass('active');
                $('.event').hide();
                $('.system-event.new-event').show();
                $('.task.new-event').show();
                loadLog();
                hideLoadLog();
                insertPlug();
            } else {
                $('#taskSearch').trigger('click');
                $('#taskSearch').addClass('active');
                $('#systemSearch').addClass('active');
                $('.event').hide();
                $('.system-event').show();
                $('.task').show();
                loadLog();
                hideLoadLog();
                insertPlug();
            }
        });

        $('[href="/log/#comments"]').on('click', function () {
            $('#commentSearch').trigger('click');
            $('#allSearch').trigger('click');
        });

        $('[href="/log/#new-comments"]').on('click', function () {
            $('#commentSearch').trigger('click');
            $('#newSearch').trigger('click');
        });

        var action = window.location.hash.substr(1);

        if (action === 'new-comments') {
            $('#commentSearch').trigger('click');
            $('#newSearch').trigger('click');
        }
        if (action === 'new-tasks') {
            $('#newSearch').trigger('click');
            $('#taskSearch').trigger('click');
            $('#taskSearch').addClass('active');
            $('#systemSearch').addClass('active');
            $('.event').hide();
            $('.system-event.new-event').show();
            $('.task.new-event').show();
            loadLog();
            hideLoadLog();
            insertPlug();
        }
        if (action === 'comments') {
            $('#commentSearch').trigger('click');
        }
        if (action === 'tasks') {
            $('#taskSearch').trigger('click');
            $('#systemSearch').addClass('active');
            $('.event').hide();
            $('.system-event').show();
            $('.task').show();
            loadLog();
            hideLoadLog();
            insertPlug();
        }


        $('#eventBox').on('mouseover', '.new-event', function () {
            if ($(this).hasClass('task')) {
                var $taskLink = $(this).find('.task-link');
                $taskLink.addClass('text-primary');
                setTimeout(function () {
                    $taskLink.removeClass('text-primary');
                }, 1000);
            }
            $(this).removeClass('new-event');
            var eventId = $(this).data('event-id');
            markAsRead(eventId);
        });
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
        });
        hideLoadLog();
    }

</script>
