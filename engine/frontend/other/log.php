<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body pb-2">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-inline-block">
                        <div id="taskSearch" data-type="task" class="btn btn-secondary type-search words-search">
                            <span>Задачи</span>
                            <span class="count"></span>
                        </div>
                        <div id="commentSearch" data-type="comment" class="btn btn-secondary type-search words-search">
                            <span>Комментарии</span>
                            <span class="count"></span>
                        </div>
                        <div id="newSearch" class="btn btn-secondary view-status-search words-search">
                            <span>Показать новые</span>
                            <span class="count"></span>
                        </div>
                        <div id="allSearch" class="btn btn-secondary view-status-search words-search active">
                            <span>Показать все</span>
                            <span class="count"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="eventBox">
        <?php foreach ($events as $event): ?>
        <?php
            if ($event['action'] == 'comment') {
                include 'engine/frontend/event-messages/comment.php';
            } else if (in_array($event['action'], $systemEvents)) {
                include 'engine/frontend/event-messages/system.php';
            } else {
                include 'engine/frontend/event-messages/task.php';
            }
            ?>
        <?php endforeach; ?>
    </div>

	<div class="row justify-content-center">
        <div class="col-12 col-lg-10">
	      	<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link active" id="pills-new-tab" data-toggle="pill" href="#pills-new" role="tab" aria-controls="pills-new" aria-selected="true"><i class="fas fa-plus"></i> Задачи (<?=$newtask?>)</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="pills-comment-tab" data-toggle="pill" href="#pills-comment" role="tab" aria-controls="pills-comment" aria-selected="false"><i class="fas fa-comment"></i> Комментарии (<?=$comments?>)</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="pills-overdue-tab" data-toggle="pill" href="#pills-overdue" role="tab" aria-controls="pills-overdue" aria-selected="false"><i class="fas fa-exclamation"></i> Просрочки (<?=$overduetask?>)</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="pills-done-tab" data-toggle="pill" href="#pills-done" role="tab" aria-controls="pills-done" aria-selected="false"><i class="fas fa-check"></i> Выполнено (<?=$completetask?>)</a>
				  </li>
			</ul>
			<div class="tab-content" id="pills-tabContent">
			  <div class="tab-pane fade show active" id="pills-new" role="tabpanel" aria-labelledby="pills-new-tab">
				 
				<?php foreach ($newtask2 as $n) {
				  	echo '<div class="card mb-2"><div class="card-body"><a href="/task/'.$n['id'].'/">'.$n['name'].'</a></div><div class="card-footer text-muted small">28 февраля в 18:23 Вам поставлена новая задача. Необходимо с ней ознакомиться</div></div>';
				}
				?>
				 
			  </div>
			  <div class="tab-pane fade" id="pills-comment" role="tabpanel" aria-labelledby="pills-comment-tab">
				  <?php foreach ($comments2 as $n) {
					  	echo '<div class="card mb-2"><div class="card-body"><p>'.$n['comment'].'</p></div><div class="card-footer text-muted small">'.$n['datetime'].' Вам оставили комметарий в задаче <a href="/task/'.$n['idtask'].'/">'.DBOnce('name','tasks','id='.$n['idtask']).'</a></div></div>';
					}
					?>
			  </div>
			  <div class="tab-pane fade" id="pills-overdue" role="tabpanel" aria-labelledby="pills-overdue-tab">
				  
				  <?php foreach ($overduetask2 as $n) {
				echo '<div class="card mb-2"><div class="card-body"><a href="/task/'.$n['id'].'/">'.$n['name'].'</a></div></div>';
			}
			?>
				  
			  </div>
			  <div class="tab-pane fade" id="pills-done" role="tabpanel" aria-labelledby="pills-done-tab">
				  <?php foreach ($completetask2 as $n) {
				echo '<div class="card mb-2"><div class="card-body"><a href="/task/'.$n['id'].'/">'.$n['name'].'</a></div></div>';
			}
			?>
			  </div>
			</div>   
			
          </div>
		</div>
</div>
<style>
    .new-event {
        background-color: #f3f7ff;
    }
    .event{
        -webkit-transition: all 1s ease-out; /** Chrome & Safari **/
        -moz-transition: all 1s ease-out; /** Firefox **/
        -o-transition: all 1s ease-out; /** Opera **/
        transition: all 1s ease-out;
    }
</style>
<script>
    $(document).ready(function() {
        var action = window.location.hash.substr(1);

        $('#commentIcon').closest('a').on('click', function () {
            if (!$('#commentSearch').hasClass('active')) {
                $('#commentSearch').trigger('click');
            }
        });
        $('#notificationIcon').closest('a').on('click', function () {
            if (!$('#taskSearch').hasClass('active')) {
                $('#taskSearch').trigger('click');
            }
        });

        $('.type-search').on('click', function () {
            var el = $(this);
            if (el.hasClass('active')) {
                el.removeClass('active');
            } else {
                $('.type-search').removeClass('active');
                el.addClass('active');
            }
            filterEvents();
        });
        $('.view-status-search').on('click', function () {
            var el = $(this);
                $('.view-status-search').removeClass('active');
                el.addClass('active');
            filterEvents();
        })
        if (action === 'new-comments') {
            $('#commentSearch').trigger('click');
            $('#newSearch').trigger('click');
        }
        if (action === 'new-tasks') {
            $('#taskSearch').trigger('click');
            $('#newSearch').trigger('click');
        }
        if (action === 'comments') {
            $('#commentSearch').trigger('click');
        }
        if (action === 'tasks') {
            $('#taskSearch').trigger('click');
        }

        $('#eventBox').on('mouseover', '.new-event', function () {
            $(this).removeClass('new-event').fadeOut(1000);
            var eventId = $(this).data('event-id');
            if ($(this).hasClass('comment')) {
                decreaseCommentCounter();
            }
            if ($(this).hasClass('task')) {
                decreaseTaskCounter();
            }
            console.log(eventId);

            markAsRead(eventId);
        })
    });

    function filterEvents() {
        var filter = $('.type-search.active').data('type') || 'event';
        var newFilter = $('#newSearch').hasClass('active');

        $('.event').each(function () {
                var el = $(this);
                el.show();
                if (newFilter && !el.hasClass('new-event')) {
                    el.hide();
                }
                if (!el.hasClass(filter)) {
                    el.hide();
                }
            })
    }

    function markAsRead(eventId) {
        $.post("/ajax.php", {module: 'markAsRead', eventId: eventId, ajax: 'log'});
    }

</script>
