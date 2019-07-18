<?php
$badges = [
    'meeting' => 'fas fa-handshake',
    'invitor' => 'fas fa-user-plus',
    'bugReport_1' => 'fab fa-accessible-icon',
    'message_1' => 'fas fa-broadcast-tower',
    'taskOverdue_1' => 'fas fa-meh',
    'taskPostpone_1' => 'fas fa-crown',
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
    'taskDonePerMonth_leader' => 'fas fa-greater-than',
    'taskInwork_20' => 'fas fa-brain',
    'taskCreatePerDay_30' => 'fas fa-bolt',
];
$percentAchieve = $countUserAchievements / count($badges) * 100;
?>
<script src="/assets/js/circle-progress.min.js"></script>
<div class="row">
    <div class="col">
        <div class="progress progress-awards col mt-3 mb-2 p-0 w">
            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentAchieve ?>%"
                 aria-valuenow="<?= $percentAchieve ?>"
                 aria-valuemin="0"
                 aria-valuemax="100" title="Достижения">
                <div class="text-white ml-3"><?= $countUserAchievements ?>/<?= count($badges) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex text-center flex-wrap mb-5 mt-3">
    <?php
    if ($countUserAchievements == 0):
        ?>
        <div class="search-container search-container-awards" style="width: 86%;">
            <div class="card">
                <div class="card-body search-empty">
                    <p>Вы ещё не получили достижения</p>
                </div>
            </div>
        </div>
    <?php
    else:
        ?>
        <?php foreach ($nonMultipleAchievements as $name => $values) {
        include __ROOT__ . '/engine/frontend/other/award-card.php';
    } ?>
    <?php endif; ?>
</div>

<?php if (!key_exists('taskDone_500', $nonMultipleAchievements)): ?>
    <div class="row mb-1">
        <div class="col">
            <h3 style=" color: #27406a; font-size: 26px; font-weight: 700; ">Путь Ответственного</h3>
            <p class="text-secondary mb-0">Уверены, вы - мастер своего дела! На этом пути вас ждут следующие достижения:</p>
        </div>
    </div>

<?php endif; ?>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($workerPath as $name => $values) {
        if ($values['got']) {
            continue;
        } else {
            include __ROOT__ . '/engine/frontend/other/award-card.php';
        }
    } ?>
</div>

<?php if (!key_exists('taskCreate_500', $nonMultipleAchievements)): ?>
    <div class="row mt-5 mb-1">
        <div class="col">
            <h3 style=" color: #27406a; font-size: 26px; font-weight: 700; ">Путь Руководителя</h3>
            <p class="text-secondary mb-0">На пути великого руководителя вас ждут следующие достижения:</p>
        </div>
    </div>
<?php endif; ?>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($managerPath as $name => $values) {
        if ($values['got']) {
            continue;
        } else {
            include __ROOT__ . '/engine/frontend/other/award-card.php';
        }
    } ?>
</div>

<div class="row mt-5 mb-1">
    <div class="col">
        <h3 style=" color: #27406a; font-size: 26px; font-weight: 700; ">Разовые достижения</h3>
        <p class="text-secondary mb-0">Еще недостигнутые разовые достижения:</p>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($otherAchievements as $name => $values) {
        if ($values['got'] || $values['hidden']) {
            continue;
        } else {
            include __ROOT__ . '/engine/frontend/other/award-card.php';
        }
    } ?>
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
        thickness: 2
    });
</script>


