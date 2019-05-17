<div class="container-fluid">
		<div class="row justify-content-center">
          <div class="col-12 col-lg-10 col-xl-8">
            
            <!-- Header -->
            <div class="header mt-md-5">
              <div class="header-body">
                <div class="row align-items-center">
                  <div class="col">
                    
                    <!-- Pretitle -->
                    <h6 class="header-pretitle">
                      Создайте новую задачу
                    </h6>

                    <!-- Title -->
                    <h1 class="header-title">
                      Новая задача
                    </h1>

                  </div>
                </div> <!-- / .row -->
              </div>
            </div>

            <!-- Form -->
        

              <!-- Project id -->
              <div class="form-group">
                <label>
                  Имя задачи
                </label>
                <input type="text" id="name" class="form-control" placeholder="Наименование задачи"  required>
              </div>

              <!-- Project id -->
              <div class="form-group">
                <label>
                  Описание задачи
                </label>
                <textarea class="form-control" id="description" id="description" s="3" placeholder="Опишите суть задания"  required></textarea>
              </div>

              <div class="row">
	              <div class="col-12 col-md-6">
		              <!-- Project id -->
		              <div class="form-group">
		                <label>
		                  Дата окончания
		                </label>
		                <input type="date" class="form-control" id="datedone" min="<?=$GLOBALS["now"]?>" value="<?=$GLOBALS["now"]?>" required>
		              </div>
	              </div>
                  <div class="col-12 col-md-6 coworkers">
                      <div class="row">
                          <label>Ответственные</label>
                      </div>
                      <div class="row coworker-item">
                          <div class="form-group col-10 p-0">
                              <select class="form-control coworker-select" id="worker" required>
                                  <?php
                                  $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                                  foreach ($users as $n) { ?>
                                      <option value="<?php echo $n['id'] ?>"><?php echo $n['name'] . ' ' . $n['surname'] ?></option>
                                  <?php } ?>
                              </select>

                          </div>
                          <div class="form-group col-2 p-0">
                          <button class="btn btn-primary h-100 coworker-button" button-action="add">
                              <i class="fas fa-plus"></i>
                          </button>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Divider -->
              <hr class="mt-4 mb-5">

              
<!-- не начислять баллы -->
              <!-- Buttons -->
              <div class="row">
                  <div class="col-sm-10 p-0">
                      <button id="createTask" class="btn btn-block btn-primary h-100">Создать задачу</button>
                  </div>
                  <div class="col-sm-2 p-0 text-center">
                        <span class="btn btn-light btn-file">
                            <i class="fas fa-file-upload custom-date"></i><input type="file">
                        </span>
                  </div>
              </div>
<!--              <button id="createTask" class="btn btn-block btn-primary w-85 d-inline">Создать задачу</button>-->
<!--              <span class="btn btn-light btn-file">-->
<!--                    <i class="fas fa-file-upload custom-date"></i><input type="file">-->
<!--                </span>-->
              <div class="row">
                  <div class="col-sm-10 p-0">
                      <a href="/" class="btn btn-block btn-link text-muted">
                          Отменить создание задачи
                      </a>
                  </div>
              </div>



          </div>
        </div>
		</div>
    <script>
    var $usp = <?php echo $id + 345;  // айдишник юзера ?>; 
    </script>
<script src="/assets/js/CometServerApi.js"></script>
<script>
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id:<?= $id ?>, user_key: '<?= $cometHash ?>', node: "app.comet-server.ru"});
        subscribeToMessagesNotification();
        subscribeToOnlineStatusNotification('<?=$cometTrackChannelName?>');
    });
</script>
    <script src="/assets/js/createtask.js"></script>