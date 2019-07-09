<h3 class="text-center pb-3">Тарифы</h3>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="text-center">1 месяц</h5>
            </div>
            <div class="card-body">
                <p>10 пробных дней бесплатно</p>
                <p>299 Р</p>
                <button data-tariff="1" class="buy-premium form-control btn btn-primary">Купить</button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="text-center">1 месяц</h5>
            </div>
            <div class="card-body">
                <p>249 Р / мес</p>
                <p>747 Р</p>
                <button  data-tariff="3"  class="buy-premium form-control btn btn-primary">Купить</button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="text-center">1 месяц</h5>
            </div>
            <div class="card-body">
                <p>199 Р / мес</p>
                <p>2388 Р</p>
                <button data-tariff="12"  class="buy-premium form-control btn btn-primary">Купить</button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="text-center">1 месяц</h5>
            </div>
            <div class="card-body">
                <p>Подписка</p>
                <p>299 Р</p>
                <button data-tariff="2" class="buy-premium form-control btn btn-primary">Купить</button>
            </div>
        </div>
    </div>
</div>

<button class="charge-subscribe form-control btn btn-primary">Продлить</button>

<script>
    $(document).ready(function () {
        $('.buy-premium').on('click', function () {
            var tariff = $(this).data('tariff');
            var subscribe = '0';
            if (tariff == 2) {
                tariff = 1;
                subscribe = '1';
            }
            var fd = new FormData();
            fd.append('tariff', tariff);
            fd.append('subscribe', subscribe);
            fd.append('module', 'getPaymentLink');
            fd.append('ajax', 'payments');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    if (response.error === ''){
                        if (response.url !== '') {
                            window.open(response.url);
                        } else {
                            console.log(response.status);
                        }
                    } else {
                        console.log(response.error);
                    }
                },
            });
        });

        $('.charge-subscribe').on('click', function () {
            var fd = new FormData();
            fd.append('module', 'chargeSubscribe');
            fd.append('ajax', 'payments');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    //window.open(response);
                },
            });
        })
    })
</script>
