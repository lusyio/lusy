<div class="card">
	<div class="card-header">
		<h4 class="mb-0">Новое сообщение</h4>
	</div>
	<div class="card-body">
		<form method="post">
			<div class="form-group">
			    <label for="mes">Сообщение</label>
			    <textarea class="form-control" id="mes" name="mes" rows="3" placeholder="Текст сообщения" required></textarea>
			  </div>
			  <div class="form-group">
			    <label for="recipient">Получатель</label>
			    <select class="form-control" id="recipient" name="recipient">
					<?php
					$users = DB('*','users','idcompany='.$GLOBALS["idc"].' and id !='.$GLOBALS["id"]);
					foreach ($users as $n) { ?>
					    <option value="<?php echo $n['id'] ?>"><?php echo $n['login'] ?></option>
					<?php } ?>
			    </select>
			  </div>
			  <button type="submit" class="btn btn-primary">Отправить</button>
		</form>
	</div>
</div>

<div class="card mt-3">
	<div class="card-header">
		<h4 class="mb-0">Диалоги</h4>
	</div>
	<div class="list-group">
	<?php foreach ($dialog as $n) { ?>
	
	<a href="./<?= $n ?>/" class="list-group-item list-group-item-action border-0">
		<p class="font-weight-bold"><?=fiomess($n)?></p>
		<p><?=lastmess($n)?></p>
	</a>
	
	<?php } ?>
	</div>
</div>
<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">Позьзователи</h4>
    </div>
    <div class="list-group">
        <?php foreach ($userList as $user): ?>
            <a href="./<?= $user['id'] ?>/" class="list-group-item list-group-item-action border-0">
                <p class="font-weight-bold"><?= $user['name'] . ' ' . $user['surname'] ?></p>
            </a>
        <?php endforeach; ?>
    </div>
	
</div>


