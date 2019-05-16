<table class="table">
    <?php if (count($invites)): ?>
    <thead>
    <tr>
        <th>Имя</th>
        <th>Ссылка</th>
        <th>Позиция</th>
        <th>Дата отправки</th>
        <th>Почта</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($invites as $invite): ?>
        <tr>
            <td><?= $invite['invitee_name'] ?></td>
            <td><?= $_SERVER['HTTP_HOST'] . '/i/' . $invite['code'] . '/'; ?></td>
            <td><?= $invite['invitee_position'] ?></td>
            <td><?= $invite['invite_date'] ?></td>
            <td><?= $invite['invitee_name'] ?></td>
            <td><?= $invite['status'] ?></td>
        </tr>
    <?php
    endforeach;
    endif;
    ?>
    </tbody>
</table>
<h3 class="pb-3"><b>Отправить приглашение</b></h3>
<form method="post" action="">
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="/company/"><i class="fas fa-arrow-left icon-invite"></i></a>
                <div class="text-reg text-center d-inline ml-5">
                    Чтобы пригласить сотрудника, введите его почту и укажите роль
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <input class="form-control" type="text" name="invitee-mail" placeholder="Почта получателя"
                               required>
                    </div>
                    <!--                    <p class="mb-0">Имя получателя</p>-->
                    <!--                    <div class="input-group">-->
                    <!--                        <input class="form-control" type="text" name="invitee-name" placeholder="Имя получателя" required>-->
                    <!--                    </div>-->
                </div>
                <div class="col-6">
                    <div class="input-group text-center">
                        <select class="custom-select" name="position" required>
                            <option disabled selected style='display:none;'>Роль</option>
                            <option value="admin">Admin</option>
                            <option value="worker">Worker</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
<!--                <div class="col-2 text-center mt-3">-->
<!--                    <a href="/company/" class="brn btn-outline-primary">-->
<!--                        Back-->
<!--                    </a>-->
<!--                </div>-->
                <div class="col text-center mt-3">
                    <button type="submit" class="brn btn-outline-primary rounded invite-send">Отправить</button>
                </div>
            </div>
        </div>
    </div>
    <!--    <input type="text" name="invitee-name" placeholder="имя получателя" required>-->
    <!--    <input type="text" name="invitee-mail" placeholder="почта получателя" required>-->
    <!--    <select name="position" required>-->
    <!--        <option disabled selected style='display:none;'>Роль</option>-->
    <!--        <option value="admin">Admin</option>-->
    <!--        <option value="worker">Worker</option>-->
    <!--    </select>-->
    <!--    <input type="submit">-->
</form>
