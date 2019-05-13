function subscribeToMessagesNotification () {
    cometApi.subscription("msg.new", function (e) {
        console.log(e);
        updateMesagesCounter();
    });
}
function updateMesagesCounter() {
    var messagesCount = $('#messagesCount').text();
    messagesCount++;
    $('#messagesCount').text(messagesCount);
    $('#messagesIcon').addClass('text-warning');
}