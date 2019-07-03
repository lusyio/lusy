<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-10">
        <div class="card">
            <div class="card-header bg-mail">
                <div class="position-relative">
                    <div class="text-reg" style="font-weight: 300;">
                        Тариф
                    </div>
                </div>
            </div>
            <?php if ($tariff == 1):?>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 price-card" style="opacity: 0.5">
                        <div class="card-header bg-secondary">
                        </div>
                        <div class="card-body border text-center">
                            <h4 class="price-header">
                                Бесплатный
                            </h4>
                            <span class="badge badge-secondary">Free</span>
                            <hr>
                            <div class="price-content text-left">
                                <li>
                                    100 мб хранилища
                                </li>
                                <li>
                                    200 задач
                                </li>
                            </div>
                            <button class="btn btn-secondary mt-3">
                                Перейти
                            </button>
                        </div>
                    </div>
                    <div class="col-6 price-card">
                        <div class="card-header bg-primary">
                        </div>
                        <div class="card-body border text-center">
                            <h4 class="price-header">
                                Всё включено
                            </h4>
                            <span class="badge badge-primary">до 10.07</span>
                            <hr>
                            <div class="price-content text-left">
                                <li>
                                    1000 мб хранилища
                                </li>
                                <li>
                                    Безлимитные задачи
                                </li>
                            </div>
                            <div class="mt-3">
                                <i class="icon-price-prem fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else:?>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 price-card">
                        <div class="card-header bg-secondary">
                        </div>
                        <div class="card-body border text-center">
                            <h4 class="price-header">
                                Бесплатный
                            </h4>
                            <span class="badge badge-secondary">Free</span>
                            <hr>
                            <div class="price-content text-left">
                                <li>
                                    100 мб хранилища
                                </li>
                                <li>
                                    200 задач
                                </li>
                            </div>
                            <div class="mt-3">
                                <i class="icon-price-free fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 price-card">
                        <div class="card-header bg-primary">
                        </div>
                        <div class="card-body border text-center">
                            <h4 class="price-header">
                                Всё включено
                            </h4>
                            <span class="badge badge-primary">Premium</span>
                            <hr>
                            <div class="price-content text-left">
                                <li>
                                    1000 мб хранилища
                                </li>
                                <li>
                                    Безлимитные задачи
                                </li>
                            </div>
                            <button class="btn btn-primary mt-3">
                                Перейти
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="card">
            <div class="card-header border-top bg-mail">
                <div class="position-relative">
                    <div class="text-reg" style="font-weight: 300;">
                        <?= $GLOBALS['_companysettings'] ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input id="companyName" name="companyName" type="text"
                           class="form-control input-settings company-name"
                           value="<?= $companyData['idcompany'] ?>">
                </div>
                <div>
                    <small class="text-muted text-muted-reg">
                        <?= $GLOBALS['_namecompanysettings'] ?>
                    </small>
                </div>
                <div class="input-group mt-3">
                    <input id="companyFullName" name="companyFullName" type="text"
                           class="form-control input-settings company-full-name"
                           value="<?= $companyData['full_company_name'] ?>">
                </div>
                <div>
                    <small class="text-muted text-muted-reg">
                        <?= $GLOBALS['_fullnamecompanysettings'] ?>
                    </small>
                </div>
                <div class="input-group mt-3">
                            <textarea id="companyDescription" name="companyDescription" type="text"
                                      class="form-control input-settings  company-description"><?= $companyData['description'] ?></textarea>
                </div>
                <div>
                    <small class="text-muted text-muted-reg">
                        <?= $GLOBALS['_aboutcompanysettings'] ?>
                    </small>
                </div>
                <div class="input-group mt-3">
                    <input id="companySite" name="companySite" type="text"
                           class="form-control input-settings company-site"
                           value="<?= $companyData['site'] ?>">
                </div>
                <div>
                    <small class="text-muted text-muted-reg">
                        <?= $GLOBALS['_websitecompanysettings'] ?>
                    </small>
                </div>
                <div class="input-group mt-3">
                    <select id="companyTimezone" name="companyTimezone"
                            class="form-control input-settings company-timezone">
                        <option></option>
                        <?php foreach ($timeZones as $timeZone => $text): ?>
                            <option value="<?= $timeZone ?>" <?= ($companyData['timezone'] == $timeZone) ? 'selected' : '' ?>><?= $text ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <small class="text-muted text-muted-reg">
                        <?= $GLOBALS['_clockcompanysettings'] ?>
                    </small>
                </div>
                <div class="text-center mt-5 pb-4">
                    <button class="btn btn-outline-primary" id="sendCompanyChanges" type="submit">
                        <?= $GLOBALS['_savecompanysettings'] ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $(document).ready(function () {

        $("#sendCompanyChanges").on('click', function () {
            var companyName = $('#companyName').val();
            var companyFullName = $('#companyFullName').val();
            var companySite = $('#companySite').val();
            var companyDescription = $('#companyDescription').val();
            var companyTimezone = $('#companyTimezone').val();

            var fd = new FormData();
            fd.append('ajax', 'settings');
            fd.append('module', 'changeCompanyData');
            fd.append('companyName', companyName);
            fd.append('companyFullName', companyFullName);
            fd.append('companyDescription', companyDescription);
            fd.append('companySite', companySite);
            fd.append('companyTimezone', companyTimezone);

            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    location.reload();
                },
            });
        })
    });
</script>