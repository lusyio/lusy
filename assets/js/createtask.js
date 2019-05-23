$(document).ready(function () {

    $(".file-name").on('click', '.cancelFile', function () {
        $(this).closest(".filenames").remove();
        var num = parseInt($(this).closest(".filenames").attr('val'));
        fileList.delete(num);
    });

    var fileList = new Map();
    var names;
    var sizes;
    var n = 0;

    $("#sendFiles").bind('change', function () {
        $(this.files).each(function () {
            names = this.name;
            fileList.set(n, $(this)[0]);
            $(".file-name").show().addClass('d-flex').append("<div val='" + n + "' class='filenames'>"
                + names +
                "<i class='fas fa-times custom-date cancel cancel-file ml-2 mr-3 cancelFile'></i>" +
                "</div>");
            n++;
        });
    });


//создание новой задачи
    $("#createTask").click(function () {
        var coworkers = [];
        $('.coworker-select').each(function () {
            coworkers.push($(this).val())
        });
        console.log(coworkers);

        var name = $("#name").val();
        var delta = quill.root.innerHTML;
        // var description = $("#description").val();
        var datedone = $("#datedone").val();
        var worker = $("#worker").val();
        // var attachedFile = $('input[type=file]').prop('files')[0];
        // alert(attachedFile);
        var fd = new FormData();
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
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