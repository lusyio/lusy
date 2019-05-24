<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-body p-5">
                <div class="row mb-5">
                    <div class="col-6">
                        <img class="rounded-circle" id="avatar" src="/<?= getAvatarLink($id) ?>" alt="avatar">
                    </div>
                    <div class="col text-center align-center">
                        <h4 class="mb-3"><?= $fio ?></h4>
                        <p><?= $about ?></p>
                    </div>
                    <div class="float-right">
                        <a href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-secondary"><i class="fas fa-phone mr-3"></i> <?= $phone ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-secondary"><i class="fas fa-envelope mr-3"></i> <?= $email ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="socials text-reg">
                            <span><i href="" class="fab fa-vk icon-social mr-3 vk"></i> <i href="" class="fab fa-facebook-f icon-social facebook"></i> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--        <div class="card mt-3 bg-dark text-white">-->
        <!--            <div class="card-body position-relative">-->
        <!--                <div class="position-absolute" style=" right: 10px; font-size: 12px; top: 8px; color: #9e9e9e; ">В этом-->
        <!--                    месяце <i class="fas fa-sort-down"></i></div>-->
        <!--                <div class="row">-->
        <!--                    <div class="col-sm-3">-->
        <!--                        <div class="p-3 rounded" style="background: #2d3035;">-->
        <!--                            <h3 class="font-weight-bold text-warning">782</h3>-->
        <!--                            <small>Баллов заработано</small>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    <div class="col-sm-9">-->
        <!--                        <div class="row">-->
        <!--                            <div class="col-sm-4">-->
        <!--                                <h3 class="mt-3" style=" margin-left: 10px; ">23</h3>-->
        <!--                                <div class="d-flex">-->
        <!--                                    <small style=" margin-left: -10px; width: 20px;"><i-->
        <!--                                                class="fas fa-check text-success mr-2"></i></small>-->
        <!--                                    <small>Выполнено<br>задач</small>-->
        <!--                                </div>-->
        <!--                            </div>-->
        <!--                            <div class="col-sm-4">-->
        <!--                                <h3 class="mt-3" style=" margin-left: 10px; ">3</h3>-->
        <!--                                <div class="d-flex">-->
        <!--                                    <small style=" margin-left: -10px; width: 20px;"><i-->
        <!--                                                class="fas fa-exclamation text-danger mr-2"></i></small>-->
        <!--                                    <small>Получено просрочек</small>-->
        <!--                                </div>-->
        <!--                            </div>-->
        <!--                            <div class="col-sm-4">-->
        <!--                                <h3 class="mt-3" style=" margin-left: 10px; ">32</h3>-->
        <!--                                <div class="d-flex">-->
        <!--                                    <small style=" margin-left: -10px; width: 20px;"><i-->
        <!--                                                class="fas fa-comment text-secondary mr-2"></i></small>-->
        <!--                                    <small>Оставлено комментариев</small>-->
        <!--                                </div>-->
        <!--                            </div>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!---->
        <!--        <div class="card mt-3">-->
        <!--            <div class="card-bodyl">-->
        <!--                awards-->
        <!--            </div>-->
        <!--        </div>-->

    </div>
</div>

<script>
    $(document).ready(function () {
    });
</script>
