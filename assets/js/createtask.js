$(document).ready(function () {

    var fileList = [];
    // var files = [];
    function displayFile(){
        $("#sendFiles").bind('change', function () {
            var attachedFile = $('input[type=file]').prop('files')[0];
            console.log(attachedFile);
            for (var i = 0; i<this.files.length; i++) {
                var names = this.files[i].name;
                // files.push(this.files[i]);
                fileList.push(this.files[i]);
                // fileList.set('files', files);
                // fileList.set('names', names);
                $(".file-name").addClass('d-flex').append("<div class='filenames'>"
                    +names+
                    "<i class='fas fa-times custom-date cancel cancel-file ml-2 mr-3 cancelFile'></i>" +
                    "</div>");

                $(".cancelFile").on('click', function () {
                    $(this).closest(".filenames").remove();
                    removeFile();
                });
            }
        });
    }

    function removeFile(e) {
        // var file = $('this').data("file");
        // var attachedFile = $('input[type=file]').prop('files')[0];
        // for (var i = 0; i < fileList.length; i++) {
        //     if (fileList[i].name === file) {
        //         fileList.splice(i, 1);
        //         console.log(fileList);
        //         break;
        //     }
        // }
        // $(this).parent().remove();
        // $("#sendFiles").val("");
        console.log(fileList);
        // console.log(files);
    }

    $("#sendFiles").on('click', function () {
        displayFile();
        $("#sendFiles").off('click');

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
        fileList.forEach(function (file , i) {
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
                    // location.href = '/task/' + data + '/'
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