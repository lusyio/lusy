<div style="display: none;font-size: 1px;color: #333333;line-height: 1px;max-height: 0px;opacity: 0;overflow: hidden">
    {$workerName} запрашивает перенос срока задачи - {$taskName} : {$request}
</div>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
    <tr>
        <td style="word-break:break-word;" align="center">
            <div style="vertical-align:top;text-align:left;width:100%;max-width:600px;">
                <div align="center" class="block center" style="padding: 20px;text-align: center;max-width: 600px;background-color: #ffffff;">
                    <h1 style="font-weight: 900">Запрошен перенос срока задачи</h1>
                    <p class="margin20t" style="margin-top: 20px;line-height: 2;">{$workerName} запрашивает перенос срока задачи - {$taskName}</p>
                    <img width="200px" src="https://lusy.io/public/images/mail/calendar.png" alt="" style="width: 200px">
                    <p class="margin20t" style="margin-top: 20px;line-height: 2;">{$request}</p>
                    <p class="margin30t" style="margin-top: 30px;line-height: 2;"><a class="button" href="https://s.lusy.io/task/{$taskId}/" style="background-color: #535ad3;border-color: #535ad3;color: #fff;font-weight: 400;text-align: center;vertical-align: middle;border: 1px solid transparent;padding-left: 25px;padding-right: 25px;font-size: 1rem;line-height: 1.5;border-radius: 20px;text-decoration: none;">Перейти к задаче</a></p>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>