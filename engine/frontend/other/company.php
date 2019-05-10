<div class="card mb-3">
	<div class="card-body text-center">
		<h2 class="text-uppercase font-weight-bold"><?=$namecompany?></h2>
	</div>
</div>
<div class="card">
<?php $i=0; foreach ($sql as $n) { $i++;  ?>
	<div class="card-body border-bottom">
		<div class="row">
			<div class="col-5">
				<div class="d-flex">
					<p class="font-weight-bold mr-3">#<?=$i?></p>
					<?=userpic($n["id"])?>
					<p class="ml-3 mt-4"><a href="/profile/<?=$n["id"]?>/"><?=$n["name"]?> <?=$n["surname"]?></a></p>
				</div>
			</div>
		</div>
	</div>
<?php  } ?>
</div>

<script src="/assets/js/CometServerApi.js"></script>
<script>
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id:<?= $id ?>, user_key: '<?= $cometHash ?>', node: "app.comet-server.ru"});
        cometApi.subscription("msg.new", function (e) {
            console.log(e);
            var messagesCount = $('#messagesCount').text();
            messagesCount++;
            $('#messagesCount').text(messagesCount);
            $('#messagesIcon').removeClass('text-white').addClass('text-warning');
        });
    });
</script>