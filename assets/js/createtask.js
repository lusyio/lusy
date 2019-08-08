$(document).ready(function () {

    $('.attach-file').on('click', function (e) {
        e.preventDefault();
        $('#sendFiles').trigger('click');
    });

    var dropZone = $('.anim-show');

    dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function(){
        return false;
    });

    dropZone.on('dragover dragenter', function() {
        $('.dragover-box').addClass('dragover');
    });

    dropZone.on('dragleave', function(e) {
        $('.dragover-box').removeClass('dragover');
    });

    dropZone.on('drop', function(e) {
        $('.dragover-box').removeClass('dragover');
        var files = e.originalEvent.dataTransfer.files;
        sendFiles(files);
    });

    function sendFiles (files){
        $(files).each(function () {
            names = this.name;
            size = this.size;
            var sizeLimit = $('.dropdown').attr('empty-space');
            if (size <= sizeLimit) {
                fileList.set(n, $(this)[0]);
                $('#filenamesExample').clone().attr('val', n).removeClass('d-none').appendTo('.file-name');
                $('[val=' + n + ']').find('span').text(names);
                $('.file-name').show();
                n++;
            } else {
                $("#fileSizeLimitModal").modal('show');
            }
        });
    }

    $(".file-name").on('click', '.cancelFile', function () {
        $(this).closest(".filenames").remove();
        var num = parseInt($(this).closest(".filenames").attr('val'));
        fileList.delete(num);
        if ($('.filenames:visible').length === 0) {
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
                $('#filenamesExample').clone().attr('val', n).removeClass('d-none').appendTo('.file-name');
                $('[val=' + n + ']').find('span').text(names);
                $('.file-name').show();
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
            $('.empty-list').hide();
        }
    }

//подпункты

    var numberCheckList = 0;

    $('.check-list-container').on('click', '.delete-checklist-item', function () {
        $(this).closest('.check-list-new').remove();
        if ($('.check-list-new:visible').length < 1) {
            $('.check-list-container').hide();
            numberCheckList = 0;
        }
    });

    $("#checklistInput").keypress(function (e) {
            if (e.which == 13) {
                $('#addChecklistBtn').trigger('click')
            }
    });

    $('#addChecklistBtn').on('click', function () {
        var checkName = $('#checklistInput').val();
        if (checkName != ''){
            numberCheckList++;
            $('.check-list-container').show();
            $('#checkListExample').clone().attr('data-id', numberCheckList).removeClass('d-none').addClass('checklist-selected').appendTo('.check-list-container');
            $('[data-id=' + numberCheckList + ']').find('.ml-3').text(checkName);
            $('#checklistInput').val('');
        }
    });

//работа с надзадачами
    function subtaskListEmpty() {
        if ($(".select-subtask").is(':visible')) {
            $('.empty-list-subtask').hide();
        } else {
            $('.empty-list-subtask').show();
        }
    }

    $(".container-subtask").on('click', function () {
        $(".subtask").fadeToggle(200);
        subtaskListEmpty();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest(".container-subtask").length) {
            $('.subtask').fadeOut(300);
        }
    });

    var idSubtask;

    $(".select-subtask").on('click', function () {
        $('.placeholder-subtask').hide();
        idSubtask = $(this).attr('val');
        var selected = $('.add-subtask:visible').attr('val');
        $('.subtask-card').find("[val = " + selected + "]").removeClass('d-none');
        $(this).addClass('d-none');
        $('.add-subtask').addClass('d-none').removeClass('subtask-selected');
        $('.container-subtask').find("[val = " + idSubtask + "]").removeClass('d-none').addClass('subtask-selected');
        subtaskListEmpty();
    });

    $('.container-subtask').on('click', '.subtask-selected', function () {
        $(this).addClass('d-none').removeClass('subtask-selected');
        $('.placeholder-subtask').show();
        setTimeout(function () {
            $('.subtask').fadeOut();

        }, 100);
    });

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
        $('.add-responsible').addClass('d-none').removeClass('responsible-selected');
        $('.coworker-card').find("[val = " + id + "]").addClass('d-none');
        $('.container-coworker').find("[val = " + id + "]").addClass('d-none');
        $('.container-responsible').find("[val = " + id + "]").removeClass('d-none').addClass('responsible-selected');
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
        if (!div.is(e.target) && !dov.is(e.target) && dov.has(e.target).length === 0 // если клик был не по нашему блоку
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
        $this = $(this);
        var attachedGoogleFiles = {};
        $('.attached-google-drive-file').each(function (i, googleFileToSend) {
            attachedGoogleFiles[$(googleFileToSend).data('name')] = {
                link: $(googleFileToSend).data('link'),
                size: $(googleFileToSend).data('file-size'),
                id: $(googleFileToSend).data('file-id'),
            };
        });
        var attachedDropboxFiles = {};
        $('.attached-dropbox-file').each(function (i, dropboxFileToSend) {
            attachedDropboxFiles[$(dropboxFileToSend).data('name')] = {
                link: $(dropboxFileToSend).data('link'),
                size: $(dropboxFileToSend).data('file-size'),
                id: $(dropboxFileToSend).data('file-id'),
            };
        });
        var responsible = $('.add-responsible.responsible-selected').attr('val');
        var coworkers = [];
        $('.add-worker:visible').each(function () {
            coworkers.push($(this).attr('val'));
        });
        var checkList = [];
        $('.check-list-new.checklist-selected').each(function () {
            checkList.push($(this).text().trim());
        });
        var checkDate = $('#datedone').attr('min');
        var name = $("#name").val();
        var delta = quill.root.innerHTML;
        var datedone = $("#datedone").val();
        var startdate = $("#startDate").val();
        var parentTask = $('.add-subtask.subtask-selected').attr('val');
        if (typeof parentTask == "undefined") {
            parentTask = '0';
        }
        var fd = new FormData();
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        if (pageAction === 'create') {
            fd.append('module', 'createTask');
        } else {
            fd.append('module', 'editTask');
            fd.append('it', taskId);
            var oldDeviceUploads = [];
            $('.device-uploaded, .attached-source-file').each(function (i, deviceFile) {
                if ($(deviceFile).data('file-id') !== '') {
                    oldDeviceUploads.push($(deviceFile).data('file-id'))
                }
            });
            fd.append('oldUploads', JSON.stringify(oldDeviceUploads));
        }
        fd.append('ajax', 'task-control');
        fd.append('name', name);
        fd.append('description', delta);
        fd.append('datedone', datedone);
        fd.append('startdate', startdate);
        fd.append('worker', responsible);
        fd.append('coworkers', JSON.stringify(coworkers));
        fd.append('checklist', JSON.stringify(checkList));
        fd.append('googleAttach', JSON.stringify(attachedGoogleFiles));
        fd.append('dropboxAttach', JSON.stringify(attachedDropboxFiles));
        fd.append('parentTask', parentTask);
        if (name != '' && datedone != null && datedone >= checkDate && responsible != null) {
            $this.prop('disabled', true);
            $('#spinnerModal').modal('show');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                dataType: 'json',
                data: fd,

                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.onprogress = function (e) {
                        // $(window).bind('beforeunload', function () {
                        //     event.preventDefault();
                        //     event.returnValue = 'as';
                        // });
                        $('#sendMesName').hide();
                    };
                    return xhr;
                },

                success: function (data) {
                    if (data.error === 'taskLimit') {
                        $('#spinnerModal').modal('hide');
                        $("#taskLimitModal").modal('show');
                    } else if (data.taskId !== '') {
                        location.href = '/task/' + data.taskId + '/';
                    }
                },

                complete: function () {
                    $('#sendMesName').show();
                    // $(window).unbind('beforeunload');
                },
            });
        } else {
            if (responsible == null) {
                $('.container-responsible').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                $('html, body').animate({
                    scrollTop: $(".container-responsible").offset().top
                }, 500);
                setTimeout(function () {
                    $('.container-responsible').css('background-color', '#fff');
                }, 1000);
                $('html, body').animate({
                    scrollTop: $(".container-responsible").offset().top
                }, 500);
            }
            if (name === '') {
                $('#name').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                $('html, body').animate({
                    scrollTop: $("#name").offset().top
                }, 500);
                setTimeout(function () {
                    $('#name').css('background-color', '#fff');
                }, 1000);
                $('html, body').animate({
                    scrollTop: $("#name").offset().top
                }, 500);
            }
            if (datedone < checkDate) {
                $('#datedone').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                $('html, body').animate({
                    scrollTop: $("#datedone").offset().top
                }, 500);
                setTimeout(function () {
                    $('#datedone').css('background-color', '#fff');
                }, 1000);
                $('html, body').animate({
                    scrollTop: $("#datedone").offset().top
                }, 500);
            }
            if (datedone === '') {
                $('#datedone').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                $('html, body').animate({
                    scrollTop: $("#datedone").offset().top
                }, 500);
                setTimeout(function () {
                    $('#datedone').css('background-color', '#fff');
                }, 1000);
                $('html, body').animate({
                    scrollTop: $("#datedone").offset().top
                }, 500);
            }
        }
    });

    $('.coworkers-newtask').on('click', '.coworker-button', function () {
        if ($(this).attr('button-action') == 'add') {
            $(this).parents('.coworker-item').clone().appendTo('.coworkers-newtask');
            $(this).find('i').removeClass('fa-plus');
            $(this).find('i').addClass('fa-minus');
            $(this).attr('button-action', 'remove');
        } else if ($(this).attr('button-action') == 'remove') {
            $(this).parents('.coworker-item').remove();
        }
    })
});