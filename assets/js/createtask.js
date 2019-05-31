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
            $(".file-name").show().append("<div val='" + n + "' class='filenames'>" +
                "<i class='fas fa-paperclip mr-1'></i>" + names +
                "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                "</div>");
            n++;
        });
    });

    function checkPlaceholderCoworkers() {
        var listArr = [];
        $(".add-worker:visible").each(function () {
            var list = $(this).attr('val');
            listArr.push(list);
        });
        console.log(listArr);
        if (listArr.length === 0){
            $('.placeholder-coworkers').show();
        }
    }

    function updateCoworkers() {
        $(".select-responsible:visible").each(function () {
            var list = $(this).attr('val');
            $('.coworker-card').find("[val = " + list + "]").removeClass('d-none');
        });
        $(".add-worker:visible").each(function () {
            var list = $(this).attr('val');
            $('.coworker-card').find("[val = " + list + "]").addClass('d-none');
        });
        checkPlaceholderCoworkers();
    }

    function updateResponsible() {
        $(".select-coworker:visible").each(function () {
            var list = $(this).attr('val');
            $('.responsible-card').find("[val = " + list + "]").removeClass('d-none');
        })
    }

//работа с ответственными
    $(".container-responsible").on('click', function () {
        $(".responsible").fadeToggle(200);
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest(".container-responsible").length) {
            $('.responsible').fadeOut(300);
        }
    });

    $(".select-responsible").on('click', function () {
        $('.placeholder-responsible').hide();
        var id = $(this).attr('val');
        var selected = $('.add-responsible:visible').attr('val');
        $('.responsible-card').find("[val = " + selected + "]").removeClass('d-none');
        $(this).addClass('d-none');
        $('.add-responsible').addClass('d-none');
        $('.coworker-card').find("[val = " + id + "]").addClass('d-none');
        $('.container-coworker').find("[val = " + id + "]").addClass('d-none');
        $('.container-responsible').find("[val = " + id + "]").removeClass('d-none');
        updateCoworkers();
    });

//работа с соисполнителями
    $(".icon-newtask-add-coworker").on('click', function () {
        $(".coworkers").fadeToggle(200);
    });

    $('.add-worker').on('click', function () {
        var id = $(this).attr('val');
        $(".coworkers").fadeIn(200);
        $(this).addClass('d-none');
        $('.coworker-card').find("[val = " + id + "]").removeClass('d-none');
        updateResponsible();
        checkPlaceholderCoworkers();
    });

    $(".select-coworker").on('click', function () {
        $('.placeholder-coworkers').hide();
        var id = $(this).attr('val');
        $(this).addClass('d-none');
        // $('.responsible-card').find("[val = " + id + "]").addClass('d-none');
        $('.container-coworker').find("[val = " + id + "]").removeClass('d-none');
        updateResponsible();
    });

//создание новой задачи
    $("#createTask").click(function () {
        var responsible = $('.add-responsible:visible').attr('val');
        var coworkers = [];
        $('.add-worker:visible').each(function () {
            coworkers.push($(this).attr('val'));
        });
        var name = $("#name").val();
        var delta = quill.root.innerHTML;
        var datedone = $("#datedone").val();
        var fd = new FormData();
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        fd.append('module', 'createTask');
        fd.append('name', name);
        fd.append('description', delta);
        fd.append('datedone', datedone);
        fd.append('worker', responsible);
        fd.append('coworkers', JSON.stringify(coworkers));
        fd.append('ajax', 'task-control');
        if (name != null && delta != null && datedone != null && responsible != null) {
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    location.href = '/task/' + data + '/';
                },
            });
        } else {
            console.log('asdasd');
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