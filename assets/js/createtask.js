$(document).ready(function () {

    $('.attach-file').on('click', function (e) {
        e.preventDefault();
        $('#sendFiles').trigger('click');
    });

    $(".file-name").on('click', '.cancelFile', function () {
        $(this).closest(".filenames").remove();
        var num = parseInt($(this).closest(".filenames").attr('val'));
        fileList.delete(num);
        if ($('.filenames').length === 0) {
            $('.file-name').hide();
        }
    });

    var fileList = new Map();
    var names;
    var size;
    var n = 0;
    // window.addEventListener("beforeunload", function (event) {
    //     event.preventDefault();
    //     event.returnValue = 'фыв';
    // });


    $("#sendFiles").bind('change', function () {
        $(this.files).each(function () {
            names = this.name;
            size = this.size;

            var sizeLimit = $('.dropdown').attr('empty-space');
            if (size <= sizeLimit) {
                fileList.set(n, $(this)[0]);
                $(".file-name").show().append("<div val='" + n + "' class='filenames'>" +
                    "<i class='fas fa-paperclip mr-1'></i>" + names +
                    "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                    "</div>");
                n++;
            } else {
                $("#fileSizeLimitModal").modal('show');
            }
        });
    });

    function checkPlaceholderCoworkers() {
        var listArr = [];
        $(".add-worker:visible").each(function () {
            var list = $(this).attr('val');
            listArr.push(list);
        });
        console.log(listArr);
        if (listArr.length === 0) {
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

    function coworkersListEmpty() {
        if ($(".select-coworker").is(':visible')) {
            $('.empty-list').hide();
        } else {
            $('.empty-list').show();
        }
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
    $(".container-coworker ").on('click', function () {
        $(".coworkers").fadeToggle(200);
        coworkersListEmpty();
    });

    $(document).on('click', function (e) { // событие клика по веб-документу
        var div = $(".coworkers-toggle"); // тут указываем ID элемента
        var dov = $('.coworkers');
        if (!div.is(e.target) // если клик был не по нашему блоку
            && div.has(e.target).length === 0) { // и не по его дочерним элементам
            dov.fadeOut(200); // скрываем его
        }
    });

    $('.add-worker').on('click', function () {
        var id = $(this).attr('val');
        $(".coworkers").fadeIn(200);
        $(this).addClass('d-none');
        $('.coworker-card').find("[val = " + id + "]").removeClass('d-none');
        updateResponsible();
        checkPlaceholderCoworkers();
        coworkersListEmpty();
    });

    $(".select-coworker").on('click', function () {
        $('.placeholder-coworkers').hide();
        var id = $(this).attr('val');
        $(this).addClass('d-none');
        // $('.responsible-card').find("[val = " + id + "]").addClass('d-none');
        $('.container-coworker').find("[val = " + id + "]").removeClass('d-none');
        updateResponsible();
        coworkersListEmpty();
    });

//создание новой задачи
    $("#createTask").click(function () {
        var attachedGoogleFiles = {};
        $('.attached-google-drive-file').each(function (i, googleFileToSend) {
            attachedGoogleFiles[$(googleFileToSend).data('name')] = {
                link: $(googleFileToSend).data('link'),
                size: $(googleFileToSend).data('file-size'),
            };
        });
        var attachedDropboxFiles = {};
        $('.attached-dropbox-file').each(function (i, dropboxFileToSend) {
            attachedDropboxFiles[$(dropboxFileToSend).data('name')] = {
                link: $(dropboxFileToSend).data('link'),
                size: $(dropboxFileToSend).data('file-size')
            };
        });
        var responsible = $('.add-responsible:visible').attr('val');
        var coworkers = [];
        $('.add-worker:visible').each(function () {
            coworkers.push($(this).attr('val'));
        });
        var checkDate = $('#datedone').attr('min');
        var name = $("#name").val();
        var delta = quill.root.innerHTML;
        var datedone = $("#datedone").val();
        var startdate = $("#startDate").val();
        var fd = new FormData();
        console.log(datedone);
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        fd.append('module', 'createTask');
        fd.append('name', name);
        fd.append('description', delta);
        fd.append('datedone', datedone);
        fd.append('startdate', startdate);
        fd.append('worker', responsible);
        fd.append('coworkers', JSON.stringify(coworkers));
        fd.append('googleAttach', JSON.stringify(attachedGoogleFiles));
        fd.append('dropboxAttach', JSON.stringify(attachedDropboxFiles));
        fd.append('ajax', 'task-control');
        if (name != null && delta != null && datedone != null && datedone >= checkDate && responsible != null) {
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                dataType: 'json',
                data: fd,
                success: function (data) {
                    console.log(data);
                    if (data.error === 'taskLimit') {
                        $("#taskLimitModal").modal('show');
                    } else if (data.taskId !== '') {
                        location.href = '/task/' + data.taskId + '/';
                    }
                },
            });
        } else {
            if (responsible == null) {
                $('.container-responsible').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('.container-responsible').css('background-color', '#fff');
                }, 1000)
            }
            if (name === '') {
                $('#name').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#name').css('background-color', '#fff');
                }, 1000)
            }
            if (delta === '<p><br></p>') {
                $('#editor, .editor-card').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#editor, .editor-card').css('background-color', '#fff');
                }, 1000)
            }
            if (datedone <= checkDate) {
                $('#datedone').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#datedone').css('background-color', '#fff');
                }, 1000)
            }
            if (datedone === '') {
                $('#datedone').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#datedone').css('background-color', '#fff');
                }, 1000)
            }
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