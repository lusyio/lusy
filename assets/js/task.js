$(document).ready(function () {
    function controlUpdate(data) {
        location.reload();
    }

    // при загрузке обновляем комменты

    updateComments();

    $('#workzone').on('mouseover', '.comment', function () {
        var el = $(this);
        setTimeout(function () {
            $(el).removeClass('bg-success')
        }, 1000);
    });

    // функция загрузки комментариев
    function updateComments() {
        var lastVisit = getCookie($it);
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                it: $it,
                lastVisit: lastVisit,
                ajax: 'task-comments'
            },
            success: onCommentSuccess,
        });
        var currentTime = parseInt(new Date().getTime() / 1000);


        setCookie($it, currentTime, {
            expires: 60 * 60 * 24 * 30,
            path: '/',
        });

        function onCommentSuccess(data) {
            $('#comments').html(data).fadeIn();
            countComments();
            var commentIdToScroll = window.location.hash.substr(1);
            console.log(commentIdToScroll);
            if (commentIdToScroll > 0) {
                $('#' + commentIdToScroll).addClass('bg-primary');
                location.hash = '';

                console.log(true);
                $('html, body').animate({
                    scrollTop: $('#' + commentIdToScroll).offset().top - 20
                }, 500);
                setTimeout(function () {
                    $('#' + commentIdToScroll).removeClass('bg-primary');
                }, 5000)
            }
        }
    }

    // возвращает cookie с именем name, если есть, если нет, то undefined
    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    //обновляет куки
    function setCookie(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    }

    // добавление файлов к комментам

    var marker = true;

    function count() {
        marker = false;
    }

    $(".file-name").on('click', '.cancelFile', function () {
        $(this).closest(".filenames").remove();
        var num = parseInt($(this).closest(".filenames").attr('val'));
        fileList.delete(num);
        if (fileList.size === 0) {
            $('.file-name').hide();
        }
    });

    $('.attach-file').on('click', function (e) {
        e.preventDefault();
        $('#sendFiles').trigger('click');
    });

    $('.attach-file-review').on('click', function (e) {
        e.preventDefault();
        $('#sendFilesReview').trigger('click');
    });

    var fileList = new Map();
    var names;
    var n = 0;
    var size;

    $(".file-name-review").on('click', '.cancelFile', function () {
        $(this).closest(".filenames").remove();
        var num = parseInt($(this).closest(".filenames").attr('val'));
        fileList.delete(num);
        if (fileList.size === 0) {
            $('.file-name-review').hide();
        }
    });

    $('#sendFilesReview').bind('change', function () {
        $(this.files).each(function () {
            names = this.name;
            size = this.size;
            var sizeLimit = $('.dropdown').attr('empty-space');
            if (size <= sizeLimit) {
                fileList.set(n, $(this)[0]);
                $(".file-name-review").show().append("<div val='" + n + "' class='filenames mt-1'>" +
                    "<i class='fas fa-paperclip mr-1'></i>" + names +
                    "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                    "</div>");
                n++;
            } else {
                $("#fileSizeLimitModal").modal('show');
            }
        });
    });

    $("#sendFiles").bind('change', function () {
        $(this.files).each(function () {
            names = this.name;
            var size = this.size;
            var sizeLimit = $('.dropdown').attr('empty-space');
            if (size <= sizeLimit) {
                fileList.set(n, $(this)[0]);
                $(".file-name").show().append("<div val='" + n + "' class='filenames mt-1'>" +
                    "<i class='fas fa-paperclip mr-1'></i>" + names +
                    "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                    "</div>");
                n++;
            } else {
                $("#fileSizeLimitModal").modal('show');
            }
        });
    });

    $("#comment").on('click', function () {
        var text = $("#comin").val();
        console.log(attachedFile);
        var fd = new FormData();
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

        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        fd.append('ajax', 'task-comments-new');
        fd.append('text', text);
        fd.append('it', $it);
        fd.append('googleAttach', JSON.stringify(attachedGoogleFiles));
        fd.append('dropboxAttach', JSON.stringify(attachedDropboxFiles));
        if (text) {
            $(this).prop('disabled', true);
            console.log(fd.get('googleAttach'));
            $("#comin").attr("disabled", true);
            $('#comment').html('<i class="fas fa-spinner fa-spin"></i>');
            $('#comments').fadeOut();
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    setTimeout(function () {
                        var currentTime = parseInt(new Date().getTime() / 1000);
                        setCookie($it, currentTime, {
                            expires: 60 * 60 * 24 * 30,
                            path: '/',
                        });
                        updateComments();
                    }, 200);
                    setTimeout(function () {
                        $('#comment').html('<i class="fas fa-paper-plane"></i>');
                        $("#comin").attr("disabled", false);
                    }, 500);
                    $('.file-name').hide();
                    fileList = new Map();
                },
            });
            $('#comment').prop('disabled', false);
            $("#comin").val("");
            $(".file-name").empty();
        }
    });

    // перенос срока задачи
    $("#postpone").click(function () {
        $('#status-block').addClass('d-none');
        $('#postpone-block').removeClass('d-none');

    });

    // // отчет о завершении
    $("#done").click(function () {
        $('#status-block').addClass('d-none');
        // 	$('#report-block').removeClass('d-none');
    });

    var attachedFile = [];


    // на рассмотрение
    $("#sendonreview").click(function () {
        var text = $("#reportarea").val();
        var fd = new FormData();
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
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        fd.append('module', 'sendonreview');
        fd.append('ajax', 'task-control');
        fd.append('text', text);
        fd.append('it', $it);
        fd.append('googleAttach', JSON.stringify(attachedGoogleFiles));
        fd.append('dropboxAttach', JSON.stringify(attachedDropboxFiles));
        if (text) {
            $(this).prop('disabled', true);
            $('#spinnerModal').modal('show');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: controlUpdate,
            });

        } else {
            $("#reportarea").addClass('border-danger');
        }
    });

    // Перенос срока
    $("#sendpostpone").click(function () {
        var datepostpone = $("#deadlineInput").val();
        var checkDate = $("#deadlineInput").attr('min');
        var text = $("#reportarea1").val();
        if (text !== '' && datepostpone >= checkDate) {
            $(this).prop('disabled', true);
            $('#spinnerModal').modal('show');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                data: {
                    module: 'sendpostpone',
                    text: text,
                    datepostpone: datepostpone,
                    it: $it,
                    ajax: 'task-control'
                },
                success: controlUpdate
            });

        } else {
            if (text === '') {
                $('#reportarea1').css({
                    'border-color': '#dc3545',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#reportarea1').css('border-color', "#ced4da");
                }, 1000)
            }
            if (datepostpone <= checkDate) {
                $('#deadlineInput').css({
                    'border-color': '#dc3545',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#deadlineInput').css('border-color', "#ced4da");
                }, 1000)
            }
        }
    });

    $("#deadlineInput").on('change', function () {
        var datedone = $(this).val();
        var $this = $(this);
        var minVal = $(this).attr('min');
        setTimeout(function () {
            if (datedone < minVal) {
                $this.val(minVal);
            }
        }, 500);
    });

    // Манагер ставит дату
    $("#sendDate").click(function () {
        var sendDate = $("#deadlineInput").val();
        var checkDate = $("#deadlineInput").attr('min');
        if (sendDate >= checkDate) {
            $(this).prop('disabled', true);
            $('#spinnerModal').modal('show');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                data: {
                    module: 'sendDate',
                    sendDate: sendDate,
                    it: $it,
                    ajax: 'task-control'
                },
                success: controlUpdate,
            });

        } else {
            if (sendDate <= checkDate) {
                $('#deadlineInput').css({
                    'border-color': '#dc3545',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#deadlineInput').css('border-color', "#ced4da");
                }, 1000)
            }
        }
    });

    // Манагер принимает дату
    $("#confirmDate").click(function () {
        $(this).prop('disabled', true);
        $('#spinnerModal').modal('show');
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                module: 'confirmDate',
                it: $it,
                ajax: 'task-control',
            },
            success: controlUpdate,
        });
    });

    // Манагер отменят дату
    $("#cancelDate").click(function () {
        $(this).prop('disabled', true);
        $('#spinnerModal').modal('show');
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                module: 'cancelDate',
                it: $it,
                ajax: 'task-control'
            },
            success: controlUpdate,
        });
    });

// Кнопка принять для worker'a (в статусе "на рассмотрении""), переводит в статус done - завершен

    $("#workdone").click(function () {
        if ($(this).hasClass('continue-none')) {
            highlightUnfinishedTasks();
        } else {
            $(this).prop('disabled', true);
            $("#cancelTask").prop('disabled', true);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    module: 'workdone',
                    it: $it,
                    ajax: 'task-control'
                },
                success: function (response) {
                    if (response.status) {
                        controlUpdate()
                    } else {
                        $('#workdone').prop('disabled', false);
                        $("#cancelTask").prop('disabled', false);
                        response.tasks.forEach(function (e) {
                            highlightUnfinishedTasksById(e.id)
                        });
                    }
                },
            });
        }
    });

    $("#changePlanDate").on('click', function () {
        $(this).prop('disabled', true);
        var datePlan = $("#inputChangePlanDate").val();
        $('#spinnerModal').modal('show');
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                module: 'changeStartDate',
                startDate: datePlan,
                it: $it,
                ajax: 'task-control'
            },
            success: controlUpdate,
        })
    });

// Возврат

    $("#workreturn").click(function () {
        var datepostpone = $("#returnDateInput").val();
        var text = $("#reportarea").val();
        var fd = new FormData();

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
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        fd.append('module', 'workreturn');
        fd.append('ajax', 'task-control');
        fd.append('text', text);
        fd.append('datepostpone', datepostpone);
        fd.append('it', $it);
        fd.append('googleAttach', JSON.stringify(attachedGoogleFiles));
        fd.append('dropboxAttach', JSON.stringify(attachedDropboxFiles));
        if (text) {
            $('#spinnerModal').modal('show');
            $(this).prop('disabled', true);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: controlUpdate,
            });

        } else {
            $("#reportarea").addClass('border-danger');
        }
    });

// Кнопка "В работу" для worker'a (на странице "возвращен")

    $("#inwork").click(function () {
        $(this).prop('disabled', true);
        // var report = $("#reportarea").val();
        // if (report) {
        $('#spinnerModal').modal('show');
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                module: 'inwork',
                it: $it,
                ajax: 'task-control',
            },
            success: controlUpdate,
        });

        // } else {
        // 	$("#reportarea").addClass('border-danger');
    });

    //Отмена таска
    $("#cancelTask").click(function () {
        if ($(this).hasClass('continue-none')) {
            highlightUnfinishedTasks();
        } else {
            $(this).prop('disabled', true);
            $("#workdone").prop('disabled', true);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    module: 'cancelTask',
                    it: $it,
                    ajax: 'task-control'
                },
                success: function (response) {
                    if (response.status) {
                        controlUpdate()
                    } else {
                        $('#workdone').prop('disabled', false);
                        $("#cancelTask").prop('disabled', false);
                        response.tasks.forEach(function (e) {
                            highlightUnfinishedTasksById(e.id);
                        });
                    }
                },
            });
        }
    });


    $("#backbutton").click(function () {
        $("#status-block").removeClass('d-none');
    });

    $("#backbutton1").click(function () {
        $("#status-block").removeClass('d-none');
    });

    $("#return-manager").click(function () {
        $('#status-block').addClass('d-none');
    });

    $("#changeDate").click(function () {
        $('#status-block').addClass('d-none');
    });


    var dateControl = document.querySelector('input[type="date"]');
    var d1 = new Date();
    var curr_date = d1.getDate();
    var curr_month = d1.getMonth() + 1;
    var curr_year = d1.getFullYear();
    if (curr_month < 10) {
        curr_month = '0' + curr_month
    }
    if (curr_date < 10) {
        curr_date = '0' + curr_date
    }
    dated = curr_year + "-" + curr_month + "-" + curr_date;
    var element = document.getElementById("example-date-input");
    var elementt = document.getElementById("deadlineInput");
    if (!elementt) {
    } else {
        dateControl.value = dated;
        dateControl.min = dated;
    }
    if (!element) {
    } else {
        dateControl.value = dated;
        dateControl.min = dated;
    }

    $('.comment-filter').on('click', function () {
        var filter;
        if ($(this).hasClass('active')) {
            filter = 'all';
        } else {
            filter = $(this).attr('data-filter-type');
        }
        $('#comments').children().hide();
        $('.comment-filter').removeClass('active');
        switch (filter) {
            case 'comments':
                $(this).addClass('active');
                $('#comments').children('.comment').fadeIn();
                $('#comments').children('.comment').find('.comment-text').fadeIn();
                break;
            case 'files':
                $(this).addClass('active');
                $('#comments').children('').has('.attached-files').fadeIn();
                $('#comments').children('').has('.attached-files').find('.comment-text').fadeIn();
                break;
            case 'systems':
                $(this).addClass('active');
                $('#comments').children('.system').fadeIn();
                break;
            case 'reports':
                $(this).addClass('active');
                $('#comments').children('.report').fadeIn();
                $('#comments').children('.report').find('.comment-text').fadeIn();
                break;
            case 'all':
            default:
                $('#comments').children().fadeIn();
                $('#comments').children().find('.comment-text').fadeIn();
                break;
        }
    });

    function countComments() {
        var commentsCount = $('#comments').children('.comment').length;
        var filesCount = $('#comments').find('.file').length;
        var systemsCount = $('#comments').children('.system').length;
        var reportsCount = $('#comments').children('.report').length;
        $('.comments-count').text(commentsCount);
        $('.files-count').text(filesCount);
        $('.systems-count').text(systemsCount);
        $('.reports-count').text(reportsCount);
    }

    $("#comments").on('click', '.delc', (function () {
        $idcom = $(this).val();
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                ic: $idcom,
                ajax: 'task-comments-del'
            },
            success: function () {
                $($idcom).fadeOut();
                setTimeout(function () {
                    $($idcom).remove();
                }, 500);
            }
        });
    }));
    $("#comments").on('mouseover', '.new-event', (function () {
            $(this).removeClass('new-event');
        })
    );

    $('#nameTask').on('click', function () {
        $('.collapse-checklist').collapse('toggle');
    });

    $('.checkbox-checklist').on('change', function () {
        // var isChecked = $(this).prop('checked');
        // console.log(isChecked);
        var $this = $(this);
        var idCheckList = $(this).attr('idChecklist');
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                ajax: 'task-control',
                module: 'checklist',
                it: $it,
                checklistRow: idCheckList
            },
            success: function (data) {
                console.log(data);
                if (data == 1){
                    $this.prop('checked', true);
                    var userName = $('#fullUserName').val();
                    $this.parents('.pure-material-checkbox').find('.small').text('(' + userName + ')');
                } else{
                    $this.prop('checked', false);
                    $this.parents('.pure-material-checkbox').find('.small').text('');
                }
            }
        })
    });

    $('.continue-none').on('mouseleave', function () {
        $(this).tooltip('hide');
    });

    function highlightUnfinishedTasks() {
        $('.not-finished').each(function () {
            $(this).children('.card-footer').css({
                'background-color': '#ff000030',
                'transition': '1000ms'
            });
        });
        setTimeout(function () {
            $('.not-finished').each(function () {
                $(this).children('.card-footer').css({
                    'background-color': '#00000008',
                });
            });
        }, 3000)
    }

    function highlightUnfinishedTasksById(id) {
        $('.subTaskInList').find('[idtask= ' + id + ']').children('.card-footer').css({
            'background-color': '#ff000030',
            'transition': '1000ms'
        });
        setTimeout(function () {
            $('.subTaskInList').find('[idtask= ' + id + ']').children('.card-footer').css('background-color', '#00000008');
        }, 3000)
    }

});