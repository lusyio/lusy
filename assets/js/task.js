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
                console.log(true);
                $('html, body').animate({
                    scrollTop: $('#' + commentIdToScroll).offset().top - 20
                }, 1500);
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

    var fileList = new Map();
    var names;
    var sizes;
    var n = 0;

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
            fileList.set(n, $(this)[0]);
            $(".file-name-review").show().append("<div val='" + n + "' class='filenames mt-1'>" +
                "<i class='fas fa-paperclip mr-1'></i>" + names +
                "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                "</div>");
            n++;
        });
    });

    $("#sendFiles").bind('change', function () {
        $(this.files).each(function () {
            names = this.name;
            fileList.set(n, $(this)[0]);
            $(".file-name").show().append("<div val='" + n + "' class='filenames mt-1'>" +
                "<i class='fas fa-paperclip mr-1'></i>" + names +
                "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                "</div>");
            n++;
        });
    });

    $("#comment").on('click', function () {
        var text = $("#comin").val();
        console.log(attachedFile);
        var fd = new FormData();
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        fd.append('ajax', 'task-comments-new');
        fd.append('text', text);
        fd.append('it', $it);
        if (text) {
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
                        });
                        updateComments();
                    }, 200);
                    setTimeout(function () {
                        $('#comment').html('<i class="fas fa-paper-plane"></i>');
                        $("#comin").attr("disabled", false);
                    }, 500);
                },
            });
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
        fileList.forEach(function (file, i) {
            fd.append('file' + i, file);
        });
        fd.append('module', 'sendonreview');
        fd.append('ajax', 'task-control');
        fd.append('text', text);
        fd.append('it', $it);
        if (text) {
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
        var text = $("#reportarea1").val();
        if (text) {
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
            $("#reportarea1").addClass('border-danger');
        }
    });

    // Манагер ставит дату
    $("#sendDate").click(function () {
        var sendDate = $("#deadlineInput").val();
        if (sendDate) {
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                data: {
                    module: 'sendDate',
                    sendDate: sendDate,
                    it: $it,
                    ajax: 'task-control'},
                success: controlUpdate,
            });

        } else {
            $("#example-date-input").addClass('border-danger');
        }
    });

    // Манагер принимает дату
    $("#confirmDate").click(function () {
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
        // var report = $("#reportarea").val();
        // if (report) {
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                module: 'workdone',
                it: $it,
                ajax: 'task-control'
            },
            success: controlUpdate,
        });

        // } else {
        // 	$("#reportarea").addClass('border-danger');
        // }
    });

// Кнопка "принять" для worker'a (в статусе "на рассмотрении"")

    $("#workreturn").click(function () {
        var datepostpone = $("#returnDateInput").val();
        var text = $("#reportarea").val();
        if (text) {
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                data: {
                    module: 'workreturn',
                    text: text,
                    datepostpone: datepostpone,
                    it: $it,
                    ajax: 'task-control'
                },
                success: controlUpdate,
            });

        } else {
            $("#reportarea").addClass('border-danger');
        }
    });

// Кнопка "В работу" для worker'a (на странице "возвращен")

    $("#inwork").click(function () {
        // var report = $("#reportarea").val();
        // if (report) {
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
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                module: 'cancelTask',
                it: $it,
                ajax: 'task-control'
            },
            success: controlUpdate,
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

});