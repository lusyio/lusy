<script>
    $(document).ready(function () {
        $(".delc").click(function () {
            $idcom = $(this).val();
            $.post("/ajax.php", {ic: $idcom, ajax: 'task-comments-del'}).done(function () {
                $($idcom).fadeOut();
                setTimeout(function () {
                    $($idcom).remove();
                }, 500);
            });
        });
    });
</script>