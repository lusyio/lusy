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
    $('#messagesIcon').removeClass('text-white').addClass('text-warning');
}