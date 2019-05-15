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
<p><b>Отправить приглашение</b></p>
<form method="post" action="">
    <input type="text" name="invitee-name" placeholder="имя получателя" required>
    <input type="text" name="invitee-mail" placeholder="почта получателя" required>
    <select name="position" required>
        <option disabled selected style='display:none;'>Роль</option>
        <option value="admin">Admin</option>
        <option value="worker">Worker</option>
    </select>
    <input type="submit">
</form>
