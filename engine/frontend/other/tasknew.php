<link href="/assets/css/quill.snow.css" rel="stylesheet">
<script type="text/javascript" src="/assets/js/quill.js"></script>
<?php
$borderColor = [
    'new' => 'border-primary',
    'inwork' => 'border-primary',
    'overdue' => 'border-danger',
    'postpone' => 'border-warning',
    'pending' => 'border-warning',
    'returned' => 'border-primary',
    'done' => 'border-success',
    'canceled' => 'border-secondary',
    'planned' => 'border-info',
];
?>

<div class="dragover-box">

    <div class="row">
        <div class="col-12 col-lg-8 top-block-tasknew">
            <label class="label-tasknew">
                Имя задачи
            </label>
            <div class="mb-2 card card-tasknew">
                <input type="text" id="name" maxlength="100" class="form-control border-0 card-body-tasknew"
                       placeholder="<?= $GLOBALS['_namenewtask'] ?>"
                       autocomplete="off" value="<?= ($taskEdit) ? $taskName : '' ?>" autofocus required>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <label class="label-tasknew">
                Дата окончания
            </label>
            <div class="mb-2 card card-tasknew">
                <input type="date" class="form-control border-0 card-body-tasknew"
                       id="datedone"
                       min="<?= $GLOBALS["now"] ?>"
                       value="<?= ($taskEdit) ? date('Y-m-d', $taskDatedone) : $GLOBALS["now"] ?>" required>
            </div>
        </div>
    </div>

    <div class="row mt-25-tasknew">
        <div class="col-12 col-lg-8 top-block-tasknew">
            <label class="label-tasknew">
                Описание задачи
            </label>
            <div class="mb-2 card card-tasknew editor-card">
                <div id="editor" class="border-0">
                    <?= ($taskEdit) ? htmlspecialchars_decode($taskDescription) : '' ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <label class="label-tasknew label-help link-help-underline">
                Участники
                <span class="help-link"><i class="fas fa-info-circle"></i>
                     <div class="help-link-tooltip">
                        <div class="card">
                            <div class="help-link-tooltip-body">
                                <div>
                                    <p class="help-link-tooltip-body__content">
                                       Ответственный - пользователь, отвечающий за выполнение задачи
                                    </p>
                                    <a class="help-link-tooltip-body__link"
                                       href="https://lusy.io/ru/help/tasks/#members">Подробнее</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </span>
            </label>
            <?php include __ROOT__ . '/engine/frontend/members/responsible.php'; ?>
            <div class="mb-2 card card-tasknew card-tasknew-minheight">
                <label class="label-responsible">
                    <?= $GLOBALS['_responsiblenewtask'] ?>
                </label>
                <div class="container container-responsible border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew">
                    <div class="placeholder-responsible"
                         style="<?= ($taskEdit || count($users) == 1) ? 'display: none;' : '' ?>"><?= $GLOBALS['_placeholderresponsiblenewtask'] ?></div>
                    <?php foreach ($users

                    as $n): ?>
                    <?php if (!$taskEdit && count($users) == 1): ?>
                    <div val="<?php echo $n['id'] ?>"
                         class="add-responsible<?= ($n['id'] == $id) ? ' self-user' : '' ?> responsible-selected">
                        <?php else: ?>
                        <div val="<?php echo $n['id'] ?>"
                             class="add-responsible<?= ($n['id'] == $id) ? ' self-user' : '' ?> <?= ($taskEdit && $n['id'] == $worker) ? 'responsible-selected' : 'd-none' ?>">
                            <?php endif; ?>
                            <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                            <span class="card-coworker"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                        </div>

                        <?php endforeach; ?>
                        <?php if (count($users) > 1): ?>
                            <div class="position-absolute icon-newtask icon-newtask-change-responsible">
                                <i class="fas fa-caret-down"></i>
                            </div>
                        <?php endif; ?>
                    </div>


                    <label class="label-responsible">
                        <?= $GLOBALS['_coworkersnewtask'] ?>
                    </label>
                    <div class="coworkers-toggle container container-coworker border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew">
                        <?php foreach ($users as $n): ?>
                            <div val="<?php echo $n['id'] ?>"
                                 class="add-worker <?= ($taskEdit && in_array($n['id'], $taskCoworkers)) ? '' : 'd-none' ?>">
                                <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                                <span class="coworker-fio"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                                <i class="fas fa-times icon-newtask-delete-coworker"></i>
                            </div>
                        <?php endforeach; ?>
                        <div class="placeholder-coworkers position-relative">
                            <?php if (count($users) > 1): ?>
                                Добавить
                                <div class="position-absolute icon-newtask icon-newtask-add-coworker">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            <?php else: ?>
                                <span class="d-block w-100" data-toggle="tooltip" data-placement="bottom"
                                      title="Пока вы один в компании, эта функция недоступна">Добавить</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <?php include __ROOT__ . '/engine/frontend/members/coworkers.php'; ?>

            </div>
        </div>

        <div class="row mt-25-tasknew">
            <div class="col-12 text-center position-relative">
                <div class="other-func text-center position-relative">
                    <div class="additional-func">
                        <span>Дополнительные функции <i class="fas fa-caret-down"></i></span>
                    </div>
                </div>
                <?php if ($tariff == 1 || $tryPremiumLimits['task'] < 3 || $taskEdit): // БЛОК ДЛЯ ПРЕМИУМ ТАРИФА?>
                    <div class="collapse mt-25-tasknew" id="collapseFunctions">
                        <div class="row top-block-tasknew">
                            <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                                <div class="label-tasknew text-left">
                                    <span class="link-help-underline">Надзадача
                                         <span class="help-link"><i class="fas fa-info-circle"></i>
                                             <div class="help-link-tooltip">
                                                <div class="card">
                                                    <div class="help-link-tooltip-body">
                                                        <div>
                                                            <p class="help-link-tooltip-body__content">
                                                               Это создаст вложенную задачу, т.е. текущая задача станет подзадачей для выбранной
                                                            </p>
                                                            <a class="help-link-tooltip-body__link"
                                                               href="https://lusy.io/ru/help/tasks/#parentTask">Подробнее</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </span>
                                </div>
                                <div class="card card-tasknew">
                                    <?php if ($taskEdit && $hasSubTasks): ?>
                                        <div class="container container-subtask border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew disabled">
                                            <div class="placeholder-subtask"
                                                 style="<?= ($taskEdit && !is_null($parentTask)) ? 'display: none;' : '' ?>">
                                                Недоступно - у этой задачи есть подзадачи
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php include __ROOT__ . '/engine/frontend/members/parent-task.php'; ?>
                                        <div class="container container-subtask border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew">
                                            <div class="placeholder-subtask"
                                                 style="<?= ($taskEdit && !is_null($parentTask)) ? 'display: none;' : '' ?>">
                                                Не выбрана
                                            </div>
                                            <div class="search-subtask">
                                                <input id="searchSubtask" class="form-control" placeholder="Поиск..."
                                                       autocomplete="off" type="text">
                                            </div>
                                            <?php foreach ($parentTasks as $parentTaskItem): ?>
                                                <div val="<?php echo $parentTaskItem['id']; ?>"
                                                     class="add-subtask text-area-message <?= ($taskEdit && $parentTaskItem['id'] == $parentTask) ? 'subtask-selected' : 'd-none' ?> border-left-tasks <?= $borderColor[$parentTaskItem['status']] ?>">
                                                    <span class="card-coworker"><?= $parentTaskItem['name']; ?></span><i
                                                            class="fas fa-times remove-parenttask"></i>
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="position-absolute icon-newtask icon-newtask-change-subtask">
                                                <i class="fas fa-caret-down"></i>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="label-tasknew text-left">
                                    <span class="link-help-underline">Отложенный старт
                                        <span class="help-link"><i class="fas fa-info-circle"></i>
                                             <div class="help-link-tooltip">
                                                <div class="card">
                                                    <div class="help-link-tooltip-body">
                                                        <div>
                                                            <p class="help-link-tooltip-body__content">
                                                               Дата, в которую задача будет опубликована
                                                            </p>
                                                            <a class="help-link-tooltip-body__link"
                                                               href="https://lusy.io/ru/help/tasks/#plannedTask">Подробнее</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </span>
                                </div>
                                <div class="card card-tasknew">
                                    <?php if ($taskEdit && $taskStatus != 'planned'): ?>
                                        <input type="text" class="form-control border-0 card-body-tasknew"
                                               id="startDate"
                                               value="Задача уже в работе" disabled>
                                    <?php elseif ($taskEdit && $taskStatus == 'planned'): ?>
                                        <input type="date" class="form-control border-0 card-body-tasknew"
                                               id="startDate"
                                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                               value="<?= date('Y-m-d', $startDate) ?>"
                                            <?= ($taskEdit && $taskStatus != 'planned') ? 'disabled' : 'required' ?>>
                                    <?php else: ?>
                                        <input type="date" class="form-control border-0 card-body-tasknew"
                                               id="startDate"
                                               min="<?= $GLOBALS["now"] ?>" value="<?= $GLOBALS["now"] ?>" required>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-13px">
                            <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                                <div class="label-tasknew text-left">
                                    <span class="link-help-underline">Подпункты
                                      <span class="help-link"><i class="fas fa-info-circle"></i>
                                         <div class="help-link-tooltip">
                                            <div class="card">
                                                <div class="help-link-tooltip-body">
                                                    <div>
                                                        <p class="help-link-tooltip-body__content">
                                                           По-другому - чеклист - список того, что нужно не забыть выполнить
                                                        </p>
                                                        <a class="help-link-tooltip-body__link"
                                                           href="https://lusy.io/ru/help/tasks/#checkList">Подробнее</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </span>
                                    </span>
                                </div>
                                <div class="mb-2 card card-tasknew">
                                    <input type="text" id="checklistInput"
                                           class="form-control border-0 card-body-tasknew"
                                           placeholder="Наименование подпункта"
                                           autocomplete="off">
                                    <div id="addChecklistBtn" class="position-absolute icon-newtask">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <div class="check-list-container card-body-tasknew text-left"
                                         style="<?= ($taskEdit && count($checklist) > 0) ? 'display: block;' : '' ?>">
                                        <div id="checkListExample" class="position-relative check-list-new d-none mb-2">
                                            <i class="far fa-square text-muted-new"></i>
                                            <span class="ml-3" style="color: #28416b;">  checkName  </span>
                                            <i class="fas fa-times delete-checklist-item"></i>
                                        </div>
                                        <?php if ($taskEdit): ?>
                                            <?php foreach ($checklist as $key => $item): ?>
                                                <div class="position-relative check-list-new mb-2 checklist-selected"
                                                     data-row-id="<?= isset($item['rowId']) ? $item['rowId'] : 0 ?>"
                                                     data-id="<?= ++$key ?>">
                                                    <i class="<?= ($item['status']) ? 'far fa-check-square' : 'far fa-square' ?> text-muted-new"></i>
                                                    <span class="ml-3"
                                                          style="color: #28416b;"><?= $item['text'] ?></span>
                                                    <i class="fas fa-times delete-checklist-item"></i>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 top-block-tasknew top-block-tasknew">
                                <div class="label-tasknew text-left">
                                <span class="link-help-underline">Повторение задачи
                                     <span class="help-link"><i class="fas fa-info-circle"></i>
                                         <div class="help-link-tooltip">
                                            <div class="card">
                                                <div class="help-link-tooltip-body">
                                                    <div>
                                                        <p class="help-link-tooltip-body__content">
                                                           Доступно только для задач, назначенных самому себе
                                                        </p>
                                                        <a class="help-link-tooltip-body__link"
                                                           href="https://lusy.io/ru/help/tasks/#repeat">Подробнее</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </span>
                                </div>
                                <div class="card card-tasknew">
                                    <?php
                                    $repeatOptions = [
                                        '1' => 'Каждый день',
                                        '2' => 'Через день',
                                        '3' => 'Раз в 3 дня',
                                        '4' => 'Раз в 4 дня',
                                        '5' => 'Раз в 5 дней',
                                        '6' => 'Раз в 6 дней',
                                        '7' => 'Раз в неделю',
                                    ];
                                    include __ROOT__ . '/engine/frontend/members/repeat.php'; ?>
                                    <div class="container container-repeat border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew<?= ($taskEdit && $repeatType != 0) ? '' : ' disabled' ?>">
                                        <div class="placeholder-repeat"
                                             style="<?= ($taskEdit && $repeatType != 0) ? 'display:none;' : '' ?>">
                                            Не повторять
                                        </div>
                                        <?php foreach ($repeatOptions as $key => $name): ?>
                                            <div val="<?= $key ?>"
                                                 class="add-repeat text-area-message <?= ($taskEdit && $repeatType == $key) ? 'repeat-selected' : 'd-none' ?>">
                                                <span class="card-coworker"><?= $name ?></span><i
                                                        class="fas fa-times remove-parenttask"></i>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="position-absolute icon-newtask icon-newtask-change-repeat">
                                            <i class="fas fa-caret-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="collapse mt-25-tasknew" id="collapseFunctions">
                        <div class="row">
                            <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                                <div class="label-tasknew text-left">
                                    <span class="link-help-underline">Надзадача
                                         <span class="help-link"><i class="fas fa-info-circle"></i>
                                             <div class="help-link-tooltip">
                                                <div class="card">
                                                    <div class="help-link-tooltip-body">
                                                        <div>
                                                            <p class="help-link-tooltip-body__content">
                                                               Это создаст вложенную задачу, т.е. текущая задача станет подзадачей для выбранной
                                                            </p>
                                                            <a class="help-link-tooltip-body__link"
                                                               href="https://lusy.io/ru/help/tasks/#parentTask">Подробнее</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </span>
                                </div>
                                <div class="card card-tasknew">
                            <span class="position-absolute disabledBtnOptions">
                            </span>
                                    <div class="container container-subtask border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew disabled">
                                        <div class="placeholder-subtask">Не выбрана</div>
                                        <div class="position-absolute icon-newtask icon-newtask-change-subtask">
                                            <i class="fas fa-caret-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="label-tasknew text-left">
                                    <span class="link-help-underline">Отложенный старт
                                        <span class="help-link"><i class="fas fa-info-circle"></i>
                                             <div class="help-link-tooltip">
                                                <div class="card">
                                                    <div class="help-link-tooltip-body">
                                                        <div>
                                                            <p class="help-link-tooltip-body__content">
                                                               Дата, в которую задача будет опубликована
                                                            </p>
                                                            <a class="help-link-tooltip-body__link"
                                                               href="https://lusy.io/ru/help/tasks/#plannedTask">Подробнее</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </span>
                                        </div>
                                        <div class="card card-tasknew">
                                    <span class="position-absolute disabledBtnOptions">
                                    </span>
                                    <input type="date" class="form-control border-0 card-body-tasknew" id="startDate"
                                           min="<?= $GLOBALS["now"] ?>"
                                           value="<?= $GLOBALS["now"] ?>" required disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-13px">
                            <div class="col-12 col-lg-8 top-block-tasknew">
                                <div class="label-tasknew text-left">
                                    <span class="link-help-underline">Подпункты
                                      <span class="help-link"><i class="fas fa-info-circle"></i>
                                         <div class="help-link-tooltip">
                                            <div class="card">
                                                <div class="help-link-tooltip-body">
                                                    <div>
                                                        <p class="help-link-tooltip-body__content">
                                                           По-другому - чеклист - список того, что нужно не забыть выполнить
                                                        </p>
                                                        <a class="help-link-tooltip-body__link"
                                                           href="https://lusy.io/ru/help/tasks/#checkList">Подробнее</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </span>
                                    </span>
                                </div>
                                <div class="mb-2 card card-tasknew">
                                <span class="position-absolute disabledBtnOptions">
                                </span>
                                    <input type="text" id="checklistInput"
                                           class="form-control border-0 card-body-tasknew disabled"
                                           placeholder="Наименование подпункта"
                                           autocomplete="off">
                                    <div id="addChecklistBtn" class="position-absolute icon-newtask">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <div class="check-list-container card-body-tasknew text-left">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 top-block-tasknew top-block-tasknew">
                                <div class="label-tasknew text-left">
                                <span class="link-help-underline">Повторение задачи
                                     <span class="help-link"><i class="fas fa-info-circle"></i>
                                         <div class="help-link-tooltip">
                                            <div class="card">
                                                <div class="help-link-tooltip-body">
                                                    <div>
                                                        <p class="help-link-tooltip-body__content">
                                                           Доступно только для задач, назначенных самому себе
                                                        </p>
                                                        <a class="help-link-tooltip-body__link"
                                                           href="https://lusy.io/ru/help/tasks/#repeat">Подробнее</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </span>
                                </div>
                                <div class="card card-tasknew">
                                    <div class="container container-repeat border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew<?= ($taskEdit && $repeatType != 0) ? '' : ' disabled' ?>">
                                        <div class="placeholder-repeat"
                                             style="<?= ($taskEdit && $repeatType != 0) ? 'display:none;' : '' ?>">
                                            Не повторять
                                        </div>
                                        <span class="position-absolute disabledBtnOptions">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mt-25-tasknew">
            <div class="col-12 col-lg-8 top-block-tasknew">
                <label class="label-tasknew">
                    Прикрепленные файлы
                </label>
                <?php if ($taskEdit && !is_null($repeatTask)): ?>
                    <div>
                        <p class="text-muted-new small">Файлы будут прикреплены к <a href="/task/<?= $repeatTask ?>/">оригиналу
                                задачи</a></p>
                    </div>
                <?php endif; ?>
                <div class="file-name container-files <?= ($taskEdit && count($taskUploads) > 0) ? '' : 'display-none' ?>">
                    <div id="filenamesExampleCloud" class='filenames attached-source-file d-none' data-name='name'
                         data-link='link'
                         data-file-size='size' data-file-id="">
                        <i class='fas fa-paperclip mr-1'></i> <i class='icon mr-1'></i>
                        <span>name</span>
                        <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
                    </div>
                    <div id="filenamesExample" val='n' class='filenames d-none'>
                        <i class='fas fa-paperclip mr-1'></i>
                        <span>filenames</span>
                        <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
                    </div>
                    <?php if ($taskEdit): ?>
                        <?php foreach ($taskUploads as $file): ?>
                            <?php if ($file['is_deleted']): ?>
                                <div class='filenames attached-source-file'>
                                    <i class='fas fa-paperclip mr-1'></i><i class='icon mr-1'></i>
                                    <span><s><?= $file['file_name'] ?></s> (удален)</span>
                                </div>
                            <?php elseif ($file['cloud']): ?>
                                <div class='filenames attached-source-file' data-name='<?= $file['file_name'] ?>'
                                     data-link='<?= $file['file_path'] ?>'
                                     data-file-size='<?= $file['file_size'] ?>' data-file-id="<?= $file['file_id'] ?>">
                                    <i class='fas fa-paperclip mr-1'></i> <i class='icon mr-1'></i>
                                    <span><?= $file['file_name'] ?></span>
                                    <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
                                </div>
                            <?php else: ?>
                                <div val='n' data-file-id="<?= $file['file_id'] ?>" class='filenames device-uploaded'>
                                    <i class='fas fa-paperclip mr-1'></i>
                                    <span><?= $file['file_name'] ?></span>
                                    <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="pl-20-tasknew">
                    <?php $uploadModule = 'tasknew'; // Указываем тип дропдауна прикрепления файлов?>
                    <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; // Подключаем дропдаун прикрепления файлов?>
                </div>
            </div>
            <div class="col-lg-4 col-12">
            </div>
        </div>


        <div class="row createTask-row">
            <div class="col-12 col-lg-4 create-task">
                <button id="createTask"
                        class="btn btn-block btn-outline-primary h-100"><?= ($taskEdit) ? 'Сохранить' : $GLOBALS['_createnewtask'] ?></button>
            </div>
        </div>

    </div>

    <div class="modal fade limit-modal" id="freeOptionsModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 text-left d-block">
                    <h5 class="modal-title" id="exampleModalLabel">Похоже, вы исчерпали лимит использования расширенного
                        функционала задач в бесплатном тарифе</h5>
                </div>
                <div class="modal-body text-center position-relative">
                    <div class="text-left text-block">
                        <p class="text-muted-new">Так круто планировать задачи на будущее и тем самым разгружать себе
                            голову
                            для будущих идей</p>
                        <p class="text-muted-new">Переходи на Premium тариф и тогда у тебя появится шанс обогнать Илона
                            Маска по капитализации</p>
                    </div>
                    <span class="position-absolute">
                <i class="fas fa-cogs icon-limit-modal"></i>
            </span>
                </div>
                <div class="modal-footer border-0">
                    <?php if ($isCeo): ?>
                        <a href="/payment/" id="goToPay" class="btn text-white border-0">
                            Перейти к тарифам
                        </a>
                    <?php endif; ?>
                </div>
                <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
            </div>
        </div>
    </div>

    <div class="modal fade limit-modal" id="premModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 text-left d-block">
                    <h5 class="modal-title" id="exampleModalLabel">Похоже, вы исчерпали лимит загрузкок файлов через
                        облачные хранилища</h5>
                </div>
                <div class="modal-body text-center position-relative">
                    <div class="text-left text-block">
                        <p class="text-muted-new">Рады, что вы оценили бесшовную итеграцию с Google Drive и DropBox.</p>
                        <p class="text-muted-new">Переходи на Premium тариф и использую облака на полную мощность</p>
                    </div>
                    <span class="position-absolute">
                <i class="fas fa-cloud icon-limit-modal"></i>
            </span>
                </div>
                <div class="modal-footer border-0">
                    <?php if ($isCeo): ?>
                        <a href="/payment/" id="goToPay" class="btn text-white border-0">
                            Перейти к тарифам
                        </a>
                    <?php endif; ?>
                </div>
                <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="taskLimitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 text-center d-block">
                    <h5 class="modal-title" id="exampleModalLabel">Лимит задач</h5>
                </div>
                <div class="modal-body text-center">
                    Извините, у вас ичерпан лимит задач в этом месяце. Безлимитное число задач доступно в Premium
                    версии.
                </div>
                <div class="modal-footer border-0">
                    <?php if ($isCeo): ?>
                        <a href="/payment/" class="btn btn-primary">Перейти к тарифам</a>
                    <?php endif; ?>
                </div>
                <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="spinnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/createtask.js?n=8"></script>
    <?php if (($tariff == 1 || $tryPremiumLimits['cloud'] < 3) || ($taskEdit && $hasCloudUploads)): ?>
        <script type="text/javascript">
            //=======================Google Drive==========================
            //=Create object of FilePicker Constructor function function & set Properties===
            function SetPicker() {
                var picker = new FilePicker(
                    {
                        apiKey: 'AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc',
                        clientId: '34979060720-4dmsjervh14tqqgqs81pd6f14ed04n3d.apps.googleusercontent.com',
                        buttonEl: document.getElementById("openGoogleDrive"),
                        onClick: function (file) {
                        }
                    });
            }

            //====================Create POPUP function==============
            function PopupCenter(url, title, w, h) {
                var left = (screen.width / 2) - (w / 2);
                var top = (screen.height / 2) - (h / 2);
                return window.open(url, title, 'width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
            }

            //===============Create Constructor function==============
            function FilePicker(User) {
                //Configuration
                this.apiKey = User.apiKey;
                this.clientId = User.clientId;
                //Button
                this.buttonEl = User.buttonEl;
                //Click Events
                this.onClick = User.onClick;
                this.buttonEl.addEventListener('click', this.open.bind(this));
                //Disable the button until the API loads, as it won't work properly until then.
                this.buttonEl.disabled = true;
                //Load the drive API
                gapi.client.setApiKey(this.apiKey);
                gapi.client.load('drive', 'v2', this.DriveApiLoaded.bind(this));
                gapi.load('picker', '1', {callback: this.PickerApiLoaded.bind(this)});
            }

            FilePicker.prototype = {
                //==========Check Authentication & Call ShowPicker() function=======
                open: function () {
                    var token = gapi.auth.getToken();
                    if (token) {
                        this.ShowPicker();
                    } else {
                        this.DoAuth(false, function () {
                            this.ShowPicker();
                        }.bind(this));
                    }
                },
                //========Show the file picker once authentication has been done.=========
                ShowPicker: function () {
                    var accessToken = gapi.auth.getToken().access_token;
                    var DisplayView = new google.picker.DocsView().setIncludeFolders(true);
                    this.picker = new google.picker.PickerBuilder().addView(DisplayView).enableFeature(google.picker.Feature.MULTISELECT_ENABLED).setAppId(this.clientId).setOAuthToken(accessToken).setCallback(this.PickerResponse.bind(this)).setTitle('Google Drive').setLocale('ru').build().setVisible(true);
                },
                //====Called when a file has been selected in the Google Picker Dialog Box======
                PickerResponse: function (data) {
                    if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                        var gFiles = data[google.picker.Response.DOCUMENTS];
                        gFiles.forEach(function (file) {
                            addFileToList(file.name, file.url, file.sizeBytes, 'google-drive', 'fab fa-google-drive');
                        });
                    }
                },
                //====Called when file details have been retrieved from Google Drive========
                GetFileDetails: function (file) {
                    if (this.onClick) {
                    }
                },
                //====Called when the Google Drive file picker API has finished loading.=======
                PickerApiLoaded: function () {
                    this.buttonEl.disabled = false;
                },
                //========Called when the Google Drive API has finished loading.==========
                DriveApiLoaded: function () {
                    this.DoAuth(true);
                },
                //========Authenticate with Google Drive via the Google Picker API.=====
                DoAuth: function (immediate, callback) {
                    gapi.auth.authorize({
                        client_id: this.clientId,
                        scope: 'https://www.googleapis.com/auth/drive',
                        immediate: immediate
                    }, callback);
                }
            };

            //=======================Dropbox==========================
            options = {
                success: function (files) {
                    files.forEach(function (file) {
                        addFileToList(file.name, file.link, file.bytes, 'dropbox', 'fab fa-dropbox');
                    })
                },
                linkType: "direct", // or "preview"
                multiselect: true, // or false
                folderselect: false, // or true
            };
            $('#openDropbox').on('click', function () {
                Dropbox.choose(options);
            });

            //===================End of Dropbox=======================
            var n = 0;

            function addFileToList(name, link, size, source, icon) {
                n++;
                $('#filenamesExampleCloud').clone().addClass('attached-' + source + '-file').attr('data-name', name).attr('data-link', link).attr('data-file-size', size).attr('data-id', n).removeClass('d-none').appendTo('.file-name');
                $('[data-id=' + n + ']').find('span').text(name).find('mr-1').addClass(icon);
                $('.file-name').show();
                // $(".file-name").show().append("<div class='filenames attached-" + source + "-file' data-name='" + name + "' data-link='" + link + "' data-file-size='" + size + "'>" +
                //     "<i class='fas fa-paperclip mr-1'></i> <i class='" + icon + " mr-1'></i>" + name +
                //     "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                //     "</div>");
            }
        </script>
        <script src="https://www.google.com/jsapi?key=AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc"></script>
        <script src="https://apis.google.com/js/client.js?onload=SetPicker"></script>
        <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs"
                data-app-key="pjjm32k7twiooo2"></script>
    <?php endif; ?>
    <script>
        $(document).ready(function () {
            function checkId() {
                var selfId = $('.responsible').attr('self-id');
                var id = $('.responsible-selected').val();
                if (selfId == id) {
                    $('.container-repeat').removeClass('disabled');
                }
            }

            checkId();

            $('.other-func').on('click', function () {
                $('#collapseFunctions').collapse('toggle');
            });
            $('.disabledBtnOptions').on('click', function () {
                $('#freeOptionsModal').modal('toggle');
            });

            $("#datedone").on('change', function () {
                $(this).css('color', '#353b41');
                var minVal = $(this).attr('min');
                setTimeout(function () {
                    if ($('#datedone').val() < minVal) {
                        $('#datedone').val(minVal);
                    }
                }, 500);
            });
            $("#startDate").on('change', function () {
                $(this).css('color', '#353b41');
                var minVal = $(this).attr('min');
                setTimeout(function () {
                    if ($('#startDate').val() < minVal) {
                        $('#startDate').val(minVal);
                    }
                }, 500);
            });
            $("#startDate").on('change', function () {
                var val = $(this).val();
                var minVal = $(this).attr('min');
                if (val >= minVal) {
                    $('#datedone').attr('min', val);
                }
                if (val > $('#datedone').val()) {
                    $('#datedone').val(val);
                }
            });

            <?php if ($tariff == 0 && ($tryPremiumLimits['cloud'] >= 3 && !($taskEdit && !$hasCloudUploads))):?>
            $('#openGoogleDrive, #openDropbox').on('click', function () {
                $('#premModal').modal('show');
            });
            <?php endif; ?>

            $("#name").on('input', function () {
                var nameText = $('#name').val();
                var header = $("#headerName");
                if (nameText) {
                    header.html(nameText);
                } else {
                    nameText = 'Введите название задачи';
                    header.html(nameText);
                }
            });
        });

        var Delta = Quill.import('delta');
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Опишите суть задания...',
            modules: {
                toolbar: [
                    [{header: [1, 2, 3, 4, 5, 6, false]}],
                    ['bold', 'italic', 'underline', 'link'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    ['code'],
                    ['clean'],
                ]
            },
        });
        quill.clipboard.addMatcher(Node.ELEMENT_NODE, function (node, delta) {
            var plaintext = $(node).text();
            return new Delta().insert(plaintext);
        });

        <?php if ($taskEdit): ?>
        var taskId = <?= $taskId; ?>;
        var pageAction = 'edit';
        <?php else: ?>
        var taskId = 0;
        var pageAction = 'create';
        <?php endif; ?>
    </script>
