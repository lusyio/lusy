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

<?php foreach ($dialog as $n) { ?>
	<p><?=$n?></p>
<?php } ?>