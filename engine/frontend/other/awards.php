<?php
$badges = [
    'meeting' => 'fas fa-handshake',
    'invitor' => 'fas fa-user',
    'bugReport_1' => 'fab fa-accessible-icon',
    'message_1' => 'fas fa-broadcast-tower',
    'taskOverdue_1' => 'fas fa-meh',
    'taskPostpone_1' => 'fas fa-user-tie',
    'taskDoneWithCoworker_1' => 'fas fa-user-friends',
    'selfTask_1' => 'fas fa-user-tie',
    'taskDone_1' => 'fas fa-thumbs-up',
    'taskDone_10' => 'fas fa-star',
    'taskDone_50' => 'fas fa-star',
    'taskDone_100' => 'fas fa-star',
    'taskDone_200' => 'fas fa-star',
    'taskDone_500' => 'fas fa-star',
    'taskDonePerMonth_500' => 'fas fa-user-graduate',
    'taskCreate_10' => 'fas fa-atom',
    'taskCreate_50' => 'fas fa-atom',
    'taskCreate_100' => 'fas fa-atom',
    'taskCreate_200' => 'fas fa-atom',
    'taskCreate_500' => 'fas fa-atom',
    'comment_1000' => 'fas fa-comment',
    'taskOverduePerMonth_0' => 'fas fa-medal',
    'taskDonePerMonth_leader' => 'fas fa-user-graduate',
    'taskInwork_20' => 'fas fa-user-graduate',
    'taskCreatePerDay_30' => 'fas fa-user-graduate',
];
?>
<script src="/assets/js/circle-progress.min.js"></script>
<div class="row mb-4">
    <div class="col">
        <span class="h3">Прогресс достижений</span>
        <div class="progress progress-awards col mt-3 mb-2 p-0 w">
            <div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="1"
                 aria-valuemin="0"
                 aria-valuemax="100" title="Достижения"><span class="text-white ml-3">2/123</span></div>
        </div>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($nonMultipleAchievements as $name => $values) {
        include 'engine/frontend/other/award-card.php';
    } ?>
</div>

<div class="row mt-5 mb-1">
    <div class="col">
        <h3>Путь Ответственного</h3>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($workerPath as $name => $values){
        if ($values['got']) {
            continue;
        } else {
        include 'engine/frontend/other/award-card.php';
    }
} ?>

</div>

<div class="row mt-5 mb-1">
    <div class="col">
        <h3>Путь Руководителя</h3>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($managerPath as $name => $values){
        if ($values['got']) {
            continue;
        } else {
            include 'engine/frontend/other/award-card.php';
        }
    } ?>
</div>

<div class="row mt-5 mb-1">
    <div class="col">
        <h3>Разовые достижения</h3>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($otherAchievements as $name => $values){
        if ($values['got'] || $values['hidden']) {
            continue;
        } else {
            include 'engine/frontend/other/award-card.php';
        }
    } ?>
</div>

</div>

<script>
    jQuery(function ($) {
        $.fn.hScroll = function (amount) {
            amount = amount || 120;
            $(this).bind("DOMMouseScroll mousewheel", function (event) {
                var oEvent = event.originalEvent,
                    direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta,
                    position = $(this).scrollLeft();
                position += direction > 0 ? -amount : amount;
                $(this).scrollLeft(position);
                event.preventDefault();
            })
        };
    });

    $(document).ready(function () {
        $('.award-container').hScroll(30);
    });

    $('.circle').circleProgress({
        size: 75,
    });
</script>


