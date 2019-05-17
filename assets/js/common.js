function subscribeToMessagesNotification () {
    cometApi.subscription("msg.new", function (e) {
        console.log(e);
        updateMesagesCounter();
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