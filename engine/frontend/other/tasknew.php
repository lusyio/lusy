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
            <form class="mb-4" method="post">

              <!-- Project name -->
              <div class="form-group">
                <label>
                  Имя задачи
                </label>
                <input type="text" name="name" class="form-control" placeholder="Наименование задачи" value="Ntest" required>
              </div>

              <!-- Project name -->
              <div class="form-group">
                <label>
                  Описание задачи
                </label>
                <textarea class="form-control" id="opis" name="description" s="3" placeholder="Опишите суть задания" value="hf" required></textarea>
              </div>

              <div class="row">
	              <div class="col-12 col-md-6">
		              <!-- Project name -->
		              <div class="form-group">
		                <label>
		                  Дата окончания
		                </label>
		                <input type="date" class="form-control" name="datedone" value="" required>
		              </div>
	              </div>
				  <div class="col-12 col-md-6">
		              <!-- Project name -->
		              <div class="form-group">
		                <label>
		                  Ответственный
		                </label>
		                <select class="form-control" name="worker" required>
			                 <?php
					$users = DB('*','users','idcompany='.$GLOBALS["idc"]);
					foreach ($users as $n) { ?>
					    <option value="<?php echo $n['id'] ?>"><?php echo $n['login'] ?></option>
					<?php } ?>
		                </select>
		              </div>
	              </div>
              </div>

              <!-- Divider -->
              <hr class="mt-4 mb-5">

              
не начислять баллы
              <!-- Buttons -->
              <button type="submit" name="Submit" class="btn btn-block btn-primary">Создать задачу</button>
              <a href="/" class="btn btn-block btn-link text-muted">
                Отменить создание задачи
              </a>

            </form>

          </div>
        </div>
		</div>