<div class="container footer">
    <div class="row">
        <div class="col-sm-12">
            <a href="https://lusy.io/ru/history/" class="float-left small text-secondary" target="_blank">LUSY.IO
                v1.0.4</a>
            <?php if (!is_null($id)): ?>
                <a href="/mail/1/" class="small text-secondary float-right">Обратная связь</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/common.js?3"></script>
<script>
    $(".help-link").on('mouseenter mouseleave', function (e) {

        var elm = $(this);
        var off = elm.offset();
        var t = off.top;
        var l = off.left;
        var h = elm.find('.help-link-tooltip').height();
        var w = elm.find('.help-link-tooltip').width();
        var docH = e.target.getBoundingClientRect().top;
        var docW = $(window).width();

        var isEntirelyVisibleR = (docW - l < w);

        if (isEntirelyVisibleR) {
            $(this).find('.help-link-tooltip').addClass('edge-right');
        } else {
            $(this).find('.help-link-tooltip').removeClass('edge-right');
        }

        var isEntirelyVisibleMobile = (docW < 576);

        if (isEntirelyVisibleMobile) {
            $(this).find('.help-link-tooltip').addClass('edge-right__mobile');
        } else {
            $(this).find('.help-link-tooltip').removeClass('edge-right__mobile');
        }

        var isEntirelyVisibleB = (docH - h < h);

        if (isEntirelyVisibleB) {
            $(this).find('.help-link-tooltip').addClass('edge-bottom');
        } else {
            $(this).find('.help-link-tooltip').removeClass('edge-bottom');
        }
    });
</script>
</body>
</html>
