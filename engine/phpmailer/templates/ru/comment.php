<div style="display: none;font-size: 1px;color: #333333;line-height: 1px;max-height: 0px;opacity: 0;overflow: hidden">
    {$authorName} оставил комментарий к задаче - {$taskName} : {$commentText}. Перейти к нему можно по ссылкe из письма.
</div>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
    <tr>
        <td style="word-break:break-word;" align="center">
            <div style="vertical-align:top;text-align:left;max-width:600px;">
                <div align="center" class="block center" style="padding: 20px;text-align: center;max-width: 600px;background-color: #ffffff;">
                    <h1 style="font-size: 30px;font-weight: 900; color:#000000;">Новый комментарий</h1>
                    <p class="margin20t" style="margin-top: 20px;line-height: 2;font-size: 16px;color: #353b41;">{$authorName} оставил комментарий к задаче - {$taskName}</p>
                    <img width="200px" src="https://lusy.io/public/images/mail/feedback.png" alt="" style="width: 200px">
                    <p class="margin20t" style="margin-top: 20px;line-height: 2;font-size: 16px;color: #353b41;">{$commentText}</p>
                    <p class="margin30t" style="margin-top: 30px;line-height: 2;"><a class="button" href="https://s.lusy.io/task/{$taskId}/#{$commentId}" style="padding: .375rem .75rem;background-color: #535ad3;border-color: #535ad3;color: #fff;font-weight: 400;text-align: center;vertical-align: middle;border: 1px solid transparent;padding-left: 25px;padding-right: 25px;font-size: 1rem;line-height: 1.5;border-radius: 20px;text-decoration: none;">Перейти к комментарию</a></p>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
