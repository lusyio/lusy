function subscribeToMessagesNotification (userId) {
    console.log('подписыаемся на новые сообщения');

    cometApi.subscription("msg.new", function (e) {
        console.log('получено сообщение');
        console.log(e);
        console.log('запускаем обновление счетчика');
        if (e.data.senderId != userId) {
            updateMessagesCounter();
        }
        // if (!window.pageName || pageName !== 'conversation') {
        //     checkNotifications('newMessage', e.data.messageId);
        // }
    });
    console.log('подписыаемся на новые задачи');

    cometApi.subscription("msg.newTask", function (e) {
        console.log(e);
        updateNotificationsCounter();
        //checkNotifications('newTask', e.data)
    });
    console.log('подписыаемся на новые события в логе');

    cometApi.subscription("msg.newLog", function (e) {
        console.log('newlogmessage');
        console.log(e);
        console.log(window.pageName);
        var eventId = e.data.eventId;
        if (e.data.type === 'comment') {
            increaseCommentCounter()
        }
        if (e.data.type === 'task') {
            console.log(e);
            updateNotificationsCounter();
        }
        if (window.pageName && pageName === 'log') {
            console.log('start event request');
            $.post("/ajax.php", {module: 'getEvent', eventId: eventId, ajax: 'log'}, function (event) {
                if (event) {
                    $('#eventBox').prepend(event);
                }
            });
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
    cometApi.subscription(channelName, function(data){} )
}

function subscribeToOnlineStatusNotification (channelName) {
    var justLeftUsers = new Set();
    CometServer().subscription(channelName + ".subscription", function(msg)
    {
        var id = msg.data.user_id;
        justLeftUsers.add(id);
        console.log(msg);
        $('#dialog-'+id).removeClass('d-none');
        setTimeout(function () {
            justLeftUsers.delete(id)
        }, 10000);
        // Обработка события что кто то зашёл на сайт и подписался на канал track_online
    });
    CometServer().subscription(channelName + ".unsubscription", function(msg)
    {
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
    $('#messagesIcon').addClass('text-successful');
}
function updateNotificationsCounter() {
    var notificationsCount = +$('#notificationsCount').text();
    notificationsCount++;
    $('#notificationCount').text(notificationsCount);
    $('#notificationIcon').addClass('text-primary');
}

function checkNotifications(event, id) {
    var isFdFilled = false;
    var fd = new FormData();
    if (event === 'onLoad') {
        fd.append('module', 'checkNew');
        fd.append('ajax', 'notification-control');
        isFdFilled = true;
    }
    if (event === 'newMessage') {
        fd.append('module', 'newMessage');
        fd.append('ajax', 'notification-control');
        fd.append('messageId', id);
        isFdFilled = true;
    }
    if (event === 'newTask') {
        fd.append('module', 'newTask');
        fd.append('ajax', 'notification-control');
        fd.append('taskId', id);
        isFdFilled = true;
    }
    if (isFdFilled) {
        $.ajax({
            url: '/ajax.php',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: fd,
            success: function (data) {
                var messages = JSON.parse(data);
                if (data) {
                    while (messages.length) {
                        $('.push-messages-area').append(messages.shift());
                    }
                }
            },
        });
    }
}