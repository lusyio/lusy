<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/cropper.js"></script>
<link href="/assets/css/cropper.css" rel="stylesheet">

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-10">
        <div class="card">
            <div class="card-header border-top bg-mail">
                <div class="position-relative">
                    <div class="text-reg" style="font-weight: 300;">
                        Тариф
                    </div>
                </div>
            </div>
            <div class="card-body">

            </div>
        </div>
        <?php if ($roleu == 'ceo'): ?>
            <div class="card">
                <div class="card-header bg-mail">
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
        <?php endif; ?>
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