<script>
    $(document).ready(function () {
        $(".delc").click(function () {
            $idcom = $(this).val();
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                headers: {'Cookie' : document.cookie },
                data: {
                    ic: $idcom,
                    ajax: 'task-comments-del'},
                success: function () {
                    $($idcom).fadeOut();
                    setTimeout(function () {
                        $($idcom).remove();
                    }, 500);
                }
            });
        });
    });
</script>