<div class="row">
	<div class="col-sm-3">
		<div class="card">
			<div class="award rounded-circle bg-dark" style="height:120px; width: 120px;">
				<div class="award rounded-circle bg-dark" style="height:120px; width: 120px;">
					<i class="fas fa-trophy"></i>
				</div>
			</div>
		</div>
	</div>
</div>
<img src="/upload/awards.png">

<script src="/assets/js/CometServerApi.js"></script>
<script>
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id:<?= $id ?>, user_key: '<?= $cometHash ?>', node: "app.comet-server.ru"});
        subscribeToMessagesNotification();
    });
</script>