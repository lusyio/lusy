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

    $('.photo-preview-background').on('click', function () {
        $this = $(this).siblings('.photo-preview-container');
        var name = $this.find('.photo-preview').text();
        var src = $this.find('.photo-preview').attr('href');
        var size = ($this.find('.photo-preview').attr('sizeFile')/1024/1024).toFixed(2);
        $('.image-modal').attr('src', src);
        $('.image-preview-open').attr('href', src);
        $('.photo-preview-name').text(name);
        $('.image-preview-file-size').text(size + 'мб');
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
            if (commentIdToScroll > 0) {
                $('#' + commentIdToScroll).addClass('bg-primary');
                location.hash = '';

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

    if ($('#control').has('.drag-n-drop')){

        var fileList2 = new Map();
        var names2;
        var n2 = 0;
        var size2;

    var dropZone2 = $('.drag-n-drop');

    dropZone2.on('drag dragstart dragend dragover dragenter dragleave drop', function () {
        return false;
    });

    dropZone2.on('dragover dragenter', function () {
        $('.drag-n-drop').addClass('dragover');
    });

    dropZone2.on('dragleave', function (e) {
        $('.drag-n-drop').removeClass('dragover');
    });

    dropZone2.on('drop', function (e) {
        $('.drag-n-drop').removeClass('dragover');
        var files = e.originalEvent.dataTransfer.files;
        sendFiles2(files);
    });

    function sendFiles2(files) {
        $(files).each(function () {
            names2 = this.name;
            size2 = this.size;
            var sizeLimit = $('.dropdown').attr('empty-space');
            if (size2 <= sizeLimit) {
                fileList2.set(n2, $(this)[0]);
                $(".file-name-review").show().append("<div val='" + n2 + "' class='filenames'>" +
                    "<i class='fas fa-paperclip mr-1'></i>" + names2 +
                    "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                    "</div>");
                n2++;
            } else {
                $("#fileSizeLimitModal").modal('show');
            }
        });
    }

    }

    var dropZone = $('.comment-container-task');

    dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function () {
        return false;
    });

    dropZone.on('dragover dragenter', function () {
        $('.comment-container-task').addClass('dragover');
    });

    dropZone.on('dragleave', function (e) {
        $('.comment-container-task').removeClass('dragover');
    });

    dropZone.on('drop', function (e) {
        $('.comment-container-task').removeClass('dragover');
        var files = e.originalEvent.dataTransfer.files;
        sendFiles(files);
    });

    function sendFiles(files) {
        $(files).each(function () {
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
                fileList2.set(n, $(this)[0]);
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

    $("#comin").tooltip('disable');

    $("#comin").keypress(function (e) {
        var str = $('#comin').val().trim();
        if (str !== '' && typeof str !== undefined) {
            if (e.which == 13 && e.which == 17) {
                $('#comin').val($('#comin').val() + "\n");
            } else if (e.which == 13) {
                e.preventDefault();
                $("#comment").click();
                $("#comin").val('');
                setTimeout(function () {
                    $("#comin").css('height', '38px');
                }, 300);
            }
        }
    });

    $("#comment").on('click', function () {
        var text = $("#comin").val();
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
        } else {
            $("#comin").tooltip('enable').tooltip('show');
            $('#comin').css({
                'border-color': '#dc3545',
                'transition': '1000ms'
            });
            setTimeout(function () {
                $('#comin').css('border-color', "#ced4da");
                $("#comin").tooltip('disable').tooltip('hide');
            }, 1500)
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
        fileList2.forEach(function (file, i) {
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
        var dateDone = $("#deadlineInput").attr('datedone');
        if (text !== '' && datepostpone >= checkDate && datepostpone != dateDone) {
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
            if (datepostpone <= checkDate || datepostpone == dateDone) {
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
        var dateDone = $("#deadlineInput").attr('datedone');
        if (sendDate >= checkDate && sendDate != dateDone) {
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
            if (sendDate <= checkDate || sendDate == dateDone) {
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
            $('#cancelRepeat').prop('disabled', true);
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
                        $('#cancelRepeat').prop('disabled', false);
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
        fileList2.forEach(function (file, i) {
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

    //Отмена таска
    $("#cancelTask").click(function () {
        if ($(this).hasClass('continue-none')) {
            highlightUnfinishedTasks();
        } else {
            $(this).prop('disabled', true);
            $('#cancelRepeat').prop('disabled', true);
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
                        $('#cancelRepeat').prop('disabled', false);
                        response.tasks.forEach(function (e) {
                            highlightUnfinishedTasksById(e.id);
                        });
                    }
                },
            });
        }
    });

    // Отмена повторения задачи

    $("#cancelRepeat").click(function () {
        $(this).prop('disabled', true);
        $("#workdone").prop('disabled', true);
        $("#cancelTask").prop('disabled', true);

        $.ajax({
            url: '/ajax.php',
            type: 'POST',
            dataType: 'json',
            data: {
                module: 'cancelRepeat',
                it: $it,
                ajax: 'task-control'
            },
            success: function (response) {
                if (response.status) {
                    controlUpdate()
                } else {
                    $(this).prop('disabled', false);
                    $('#workdone').prop('disabled', false);
                    $("#cancelTask").prop('disabled', false);
                }
            },
        });
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
                if (data == 1) {
                    $this.prop('checked', true);
                    var userName = $('#fullUserName').val();
                    $this.parents('.pure-material-checkbox').find('.small').text('(' + userName + ')');
                } else {
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

    $('#comments').on('click', '.system-text', function () {
        $(this).parent('.system').find('.system-date').css("transition-delay", "0s");
        $(this).find('.system-date').css("transform", "scaleX(1)");
    });
    $('#comments').on('mouseover', '.system-text', function () {
        $(this).parent('.system').find('.system-date').css("transform", "scaleX(1)");
    })
    $('#comments').on('mouseleave', '.system-text', function () {
        $(this).parent('.system').find('.system-date').css("transform", "scaleX(0)");
    })

});