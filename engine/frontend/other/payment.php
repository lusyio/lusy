<h5 class="font-weight-bold mb-4">Тарифный план</h5>
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold">Стартовый</h4>
                <p><span class="text-secondary">Периодичность оплаты<br>1 месяц</span> - 299 руб./мес.</p>
                <button class="btn btn-danger">Отменить подписку</button>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h3 class="font-weight-bold">Уверенный</h3>
                <p><span class="text-secondary">Периодичность оплаты<br>3 месяца</span> - 249 руб./мес.</p>
                <button class="btn btn-secondary" id="regPrice">Выбрать тариф</button>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h3 class="font-weight-bold">Босс</h3>
                <p><span class="text-secondary">Периодичность оплаты<br>12 месяцев</span> - 199 руб./мес.</p>
                <button class="btn btn-secondary" id="bossPrice">Выбрать тариф</button>
            </div>
        </div>
    </div>
</div>

<h5 class="font-weight-bold mb-3 mt-4">Операции</h5>

<div class="card mb-1">
    <div class="card-body d-flex" style="justify-content: space-between">
        <div style="width: 80px"><i class="fas fa-check text-success paymentIcon"></i></div>
        <div class="w-100">Списание средств по тарифному плану Стартовый</div>
        <div class="text-success" style="width: 150px">+ 299 руб.</div>
    </div>
</div>

<div class="card mb-1">
    <div class="card-body d-flex" style="justify-content: space-between">
        <div style="width: 80px"><i class="fas fa-times text-danger paymentIcon"></i></div>
        <div class="w-100">Неудачная попытка списать средства</div>
        <div class="text-danger" style="width: 150px">- 299 руб.</div>
    </div>
</div>

<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Тарифный план</h5>
            </div>
            <div class="modal-body text-center">
                Извините, но функция загрузки файлов из облачных хранилищ доступна только в Premium версии
            </div>
            <div class="modal-footer border-0">
                <?php if ($isCeo): ?>
                    <a href="/payment/" class="btn btn-primary">Перейти к тарифам</a>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#regPrice").on('click', function () {
            $('#payModal').modal('show');
        });
        $("#bossPrice").on('click', function () {
            $('#payModal').modal('show');
        })
    });
</script>