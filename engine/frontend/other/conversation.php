<div class="card">
    <div class="card-header">
        <h4 class="mb-0"><?= fiomess($recipientId) ?></h4>
    </div>
    <div class="card-body">
        <?php if (!isset($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <p><?= $message['author'] ?> (<?= $message['datetime'] ?>):</p>
                <p><?= $message['mes'] ?></p>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет сообщений</p>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">Новое сообщение</h4>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group">
                <label for="mes">Сообщение</label>
                <textarea class="form-control" id="mes" name="mes" rows="3" placeholder="Текст сообщения"
                          required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>

</div>




