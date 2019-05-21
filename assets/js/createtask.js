$(document).ready(function () {

//создание новой задачи
    $("#createTask").click(function () {
        var coworkers = [];
        $('.coworker-select').each(function () {
            coworkers.push($(this).val())
        });
        console.log(coworkers);

        var name = $("#name").val();
        var delta = quill.root.innerHTML;
        var description = $("#description").val();
        var datedone = $("#datedone").val();
        var worker = $("#worker").val();
        var attachedFile = $('input[type=file]').prop('files')[0];
        var fd = new FormData();
        fd.append('file', attachedFile);
        fd.append('module', 'createTask');
        fd.append('name', name);
        fd.append('description', delta);
        fd.append('datedone', datedone);
        fd.append('worker', worker);
        fd.append('coworkers', JSON.stringify(coworkers));
        fd.append('ajax', 'task-control');
        if (name != null && delta != null && datedone != null && worker != null) {
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    location.href = '/task/' + data + '/'
                },
            });
        } else {
            $("#reportarea").addClass('border-danger');
        }
    });

    $('.coworkers-newtask').on('click', '.coworker-button', function () {
        if ($(this).attr('button-action') == 'add') {
            $(this).parents('.coworker-item').clone().appendTo('.coworkers-newtask');
            $(this).find('i').removeClass('fa-plus');
            $(this).find('i').addClass('fa-minus');
            $(this).attr('button-action', 'remove');
            console.log($(this).attr('button-action'));
        } else if ($(this).attr('button-action') == 'remove') {
            $(this).parents('.coworker-item').remove();
        }
    })
});