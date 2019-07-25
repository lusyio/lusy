$('img.svg-icon').each(function () {
    var $img = $(this);
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('src');
    $.get(imgURL, function (data) {
        var $svg = $(data).find('svg');
        if (typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass + ' replaced-svg');
        }
        $svg = $svg.removeAttr('xmlns:a');
        if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
            $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
        }
        $img.replaceWith($svg);
    }, 'xml');
});
$('#searchBtn').click(function () {
    hideSearchForm();
    showToolTip()
});

function showToolTip() {
    $(document).on('click', function (e) {
        if ($('#searchForm').is(':hidden')) {
            $('#searchBtn').find('.topsidebar-noty').tooltip('enable').removeClass('search-button-top');
            $('#search').val('');
            console.log('hidden');
        }
    });
}

function hideSearchForm() {
    $("#searchForm").show();
    var input = $('#search').val();
    console.log(input);
    if ($('#searchForm').is(':visible')) {
        $('#searchBtn').find('.topsidebar-noty').tooltip('disable').addClass('search-button-top');
        if (input !== '') {
            $('#searchForm').submit();
        }
        console.log('visible');
        $(document).on('click', function (e) { // событие клика по веб-документу
            var div = $("#searchForm"); // тут указываем ID элемента
            var dov = $("#searchBtn"); //
            if (!div.is(e.target) && !dov.is(e.target) && div.has(e.target).length === 0 && dov.has(e.target).length === 0) { // и не по его дочерним элементам
                div.hide(); // скрываем его
            }
        });
    }
}

function subscribeToMessagesNotification(userId) {
    console.log('подписыаемся на новые сообщения');
    cometApi.subscription("msg.new", function (e) {
        console.log('получено сообщение');
        console.log(e);
        getCounters(function (data) {
            updateCounters(data);
        });
        // if (!window.pageName || pageName !== 'conversation') {
        //     checkNotifications('newMessage', e.data.messageId);
        // }
    });
    console.log('подписыаемся на новые задачи');

    cometApi.subscription("msg.newTask", function (e) {
        console.log(e);
        getCounters(function (data) {
            updateCounters(data);
        });
    });
    console.log('подписыаемся на новые события в логе');

    cometApi.subscription("msg.newLog", function (e) {
        console.log('newlogmessage');
        console.log(e);
        console.log(window.pageName);
        var eventId = e.data.eventId;
        getCounters(function (data) {
            updateCounters(data);
        });
        if (window.pageName && (pageName === 'log' || pageName === 'dashboard')) {
            console.log('start event request');

            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                data: {
                    module: 'getEvent',
                    eventId: eventId,
                    ajax: 'log'
                },
                success: function (event) {
                    if (event) {
                        $('.timeline').prepend(event);
                    }
                }
            })


        }
    });
    console.log('подписки пройдены');

}

function increaseCommentCounter() {
    var commentCount = +$('#commentCount').text();
    commentCount++;
    $('#commentCount').text(commentCount);
    $('#commentIcon').addClass('text-warning');
}

function decreaseCommentCounter() {
    var commentCount = +$('#commentCount').text();
    commentCount--;
    if (commentCount < 1) {
        $('#commentCount').text('');
        $('#commentIcon').removeClass('text-warning');
    } else {
        $('#commentCount').text(commentCount);
        $('#commentIcon').addClass('text-warning');
    }
}

function decreaseTaskCounter() {
    var taskCount = +$('#notificationCount').text();
    console.log(taskCount);
    taskCount--;
    if (taskCount < 1) {
        $('#notificationCount').text('');
        $('#notificationIcon').removeClass('text-primary');
    } else {
        $('#notificationCount').text(taskCount);
        $('#notificationIcon').addClass('text-primary');
    }
}

function onlineStatusCheckIn(channelName) {
    cometApi.subscription(channelName, function (data) {
    })
}

function subscribeToChatNotification(channelName) {
    cometApi.subscription(channelName, function (e) {
        getCounters(function (data) {
            updateCounters(data);
        });
    });
}

function subscribeToOnlineStatusNotification(channelName) {
    var justLeftUsers = new Set();
    CometServer().subscription(channelName + ".subscription", function (msg) {
        var id = msg.data.user_id;
        justLeftUsers.add(id);
        console.log(msg);
        $('#dialog-' + id).removeClass('d-none');
        setTimeout(function () {
            justLeftUsers.delete(id)
        }, 10000);
        // Обработка события что кто то зашёл на сайт и подписался на канал track_online
    });
    CometServer().subscription(channelName + ".unsubscription", function (msg) {
        var id = msg.data.user_id;
        console.log(msg);
        setTimeout(function () {
            if (!justLeftUsers.has(id)) {
                $('#dialog-' + id).addClass('d-none');
            }
        }, 10000);

        // Обработка события что кто то покинул сайт и/или отписался от канала track_online
    });
}

function updateMessagesCounter() {
    console.log('парсим текущее число непрочитанных');
    var messagesCount = +$('#messagesCount').text();
    console.log(messagesCount);
    console.log('инкремент');
    messagesCount++;
    console.log(messagesCount);

    $('#messagesCount').text(messagesCount);
    $('#messagesIcon').addClass('text-success');
}

function updateNotificationsCounter() {
    var notificationCount = +$('#notificationCount').text();
    console.log(notificationCount);
    notificationCount++;
    console.log(notificationCount);
    $('#notificationCount').text(notificationCount);
    $('#notificationIcon').addClass('text-primary');
}

function getCounters(callback) {
    $.ajax({
        url: '/ajax.php',
        type: 'POST',
        dataType: 'json',
        data: {
            ajax: 'top-sidebar',
            module: 'count',
        },
        success: callback,
    });
}

function setCounters(counts) {
    $('#searchBtn').removeClass('d-none');
    setTaskCounter(counts.task);
    setHotCounter(counts.hot);
    setCommentCounter(counts.comment);
    setMailCounter(counts.mail);
    console.log(counts);
    setMobileIndicator(counts);
}

function updateCounters(counts) {
    var oldCounts = {};
    oldCounts.task = parseInt($('#notificationCount').text() || 0);
    oldCounts.hot = parseInt($('#overdueCount').text() || 0);
    oldCounts.comment = parseInt($('#commentCount').text() || 0);
    oldCounts.mail = parseInt($('#messagesCount').text() || 0);
    console.log(oldCounts);
    if (counts.task > oldCounts.task) {
        console.log('new task');
    }
    if (counts.hot > oldCounts.hot) {
        console.log('new hot');
    }
    if (counts.comment > oldCounts.comment) {
        console.log('new comment');
    }
    if (counts.mail > oldCounts.mail) {
        console.log('new mail');
    }
    setCounters(counts);
}

function setTaskCounter(count) {
    var counter = $('#notificationCount');
    var icon = $('#notificationIcon');
    var link = counter.closest('a');
    var badge = $('#notificationBadge');
    if (count == '0') {
        badge.addClass('d-none');
        counter.text('');
        icon.removeClass('text-primary');
        link.attr('href', link.attr('href').replace('new-tasks', 'tasks'));
    } else {
        counter.text(count);
        icon.addClass('text-primary');
        if (link.attr('href').indexOf('new-tasks') < 0) {
            link.attr('href', link.attr('href').replace('tasks', 'new-tasks'));
        }
        badge.removeClass('d-none');
    }

}

function setHotCounter(count) {
    var counter = $('#overdueCount');
    var icon = $('#overdueIcon');
    var badge = $('#overdueBadge');

    if (count == '0') {
        badge.addClass('d-none');
        counter.text('');
        icon.removeClass('text-danger');
    } else {
        counter.text(count);
        icon.addClass('text-danger');
        badge.removeClass('d-none');
    }
}

function setCommentCounter(count) {
    var counter = $('#commentCount');
    var icon = $('#commentIcon');
    var link = counter.closest('a');
    var badge = $('#commentBadge');
    if (count == '0') {
        badge.addClass('d-none');
        counter.text('');
        icon.removeClass('text-warning');
        link.attr('href', link.attr('href').replace('new-comments', 'comments'));
    } else {
        counter.text(count);
        icon.addClass('text-warning');
        if (link.attr('href').indexOf('new-comments') < 0) {
            link.attr('href', link.attr('href').replace('comments', 'new-comments'));
        }
        badge.removeClass('d-none');
    }

}

function setMailCounter(count) {
    var counter = $('#messagesCount');
    var icon = $('#messagesIcon');
    var badge = $('#messagesBadge');
    if (count == '0') {
        badge.addClass('d-none');
        counter.text('');
        icon.removeClass('text-success');
    } else {
        counter.text(count);
        icon.addClass('text-success');
        badge.removeClass('d-none');
    }

}

function setMobileIndicator(counts) {
    var hasNewCounts = false;
    $.each(counts, function (k, v) {
        if (v > 0) {
            hasNewCounts = true;
            return false;
        }
    });
    if (hasNewCounts) {
        $('.top-sidebar-indicator').removeClass('d-none');
    } else {
        $('.top-sidebar-indicator').addClass('d-none');
    }
}

function markAsRead(eventId) {
    $.ajax({
        url: '/ajax.php',
        type: 'POST',
        dataType: 'json',
        data: {
            module: 'markAsRead',
            eventId: eventId,
            ajax: 'log'
        },
        success: updateCounters,
    })
}
