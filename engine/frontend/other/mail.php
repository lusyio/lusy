<div class="card">
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
        <h4 class="mb-0">Пользователи</h4>
    </div>
    <div class="list-group">
        <?php foreach ($userList as $user): ?>
            <a href="./<?= $user['id'] ?>/" class="list-group-item list-group-item-action border-0">
                <p class="font-weight-bold"><?= $user['name'] . ' ' . $user['surname'] ?></p>
            </a>
        <?php endforeach; ?>
    </div>
	
</div>


