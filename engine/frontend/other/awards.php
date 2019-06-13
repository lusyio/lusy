<script src="/assets/js/circle-progress.min.js"></script>

<h3>Прогесс достижений</h3>
<div class="progress col-5 mb-2 p-0">
    <div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="1" aria-valuemin="0"
         aria-valuemax="100" title="Файлы компании"></div>
</div>

<div class="row mt-5">
    <div class="col-4">
        <div class="card">
            <div class="card-body text-center">
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle" data-value="0.5"></div>
                <div class="avards d-inline-block">
                    <div class="award-star">
                        <i class="fas fa-star" style="color: white;"></i>
                    </div>
                </div>
                <div class="text-muted">Сделано 5/10 задач</div>
                <hr>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body text-center">
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle" data-value="0.9"
                     data-size="60"
                     data-thickness="20"
                     data-animation-start-value="1.0"
                     data-fill="{
                    &quot;color&quot;: &quot;rgba(0, 0, 0, .3)&quot;,
                    &quot;image&quot;: &quot;http://i.imgur.com/pT0i89v.png&quot;
                                }"
                     data-reverse="true"></div>
                <div class="avards d-inline-block">
                    <div class="award-star">
                        <i class="fas fa-star" style="color: white;"></i>
                    </div>
                </div>
                <div class="text-muted">Сделано 5/10 задач</div>
                <hr>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body text-center">
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle"></div>
                <div class="avards d-inline-block">
                    <div class="award-star">
                        <i class="fas fa-star" style="color: white;"></i>
                    </div>
                </div>
                <div class="text-muted">Сделано 5/10 задач</div>
                <hr>
            </div>
        </div>
    </div>
</div>

<script>
    $('.circle').circleProgress({
        size: 75,
        fill: {
            gradient: ["red", "orange"]
        }
    });
</script>


