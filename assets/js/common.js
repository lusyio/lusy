function subscribeToMessagesNotification () {
    cometApi.subscription("msg.new", function (e) {
        console.log(e);
        updateMesagesCounter();
        if (!window.pageName || pageName !== 'conversation') {
            checkNotifications('newMessage', e.data.messageId);
        }
    });

    cometApi.subscription("msg.newTask", function (e) {
        console.log(e);
        updateNotificationsCounter();
        checkNotifications('newTask', e.data)
    });
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
function updateMesagesCounter() {
    var messagesCount = $('#messagesCount').text();
    messagesCount++;
    $('#messagesCount').text(messagesCount);
    $('#messagesIcon').addClass('text-warning');
}
function updateNotificationsCounter() {
    var notificationsCount = $('#notificationsCount').text();
    notificationsCount++;
    $('#notificationsCount').text(notificationsCount);
    $('#notificationIcon').addClass('text-warning');
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