<script src="/assets/js/circle-progress.min.js"></script>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <span class="h3">Прогесс достижений</span><span class="text-muted-reg ml-3">3/123</span>
                <div class="progress col mt-2 mb-2 p-0">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="1" aria-valuemin="0"
                         aria-valuemax="100" title="Файлы компании"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-4">
        <div class="card">
            <div class="card-body text-center">
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle" data-value="0.5"></div>
                <div class="award-star">
                    <i class="fas fa-star" style="color: white;"></i>
                </div>
                <div class="text-muted mt-3">Сделано 5/10 задач</div>
                <hr>
                <span class="badge badge-primary">50%</span>
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
                <div class="award-star">
                    <i class="fas fa-star" style="color: white;"></i>
                </div>
                <div class="text-muted mt-3">Сделано 9/10 задач</div>
                <hr>
                <span class="badge badge-primary">90%</span>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body text-center">
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle"></div>
                <div class="award-star">
                    <i class="fas fa-star" style="color: white;"></i>
                </div>
                <div class="text-muted mt-3">Сделано 0/10 задач</div>
                <hr>
                <span class="badge badge-primary">0%</span>
            </div>
        </div>
    </div>
    <div class="col-4 mt-3">
        <div class="card">
            <div class="card-body text-center" style="opacity: 0.4">
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle"></div>
                <div class="award-star">
                    <i class="fas fa-star" style="color: white;"></i>
                </div>
                <div class="text-muted mt-3">Сделано 0/10 задач</div>
                <hr>
                <span class="badge badge-primary">0%</span>
            </div>
        </div>
    </div>
    <div class="col-4 mt-3">
        <div class="card">
            <div class="card-body text-center ">
                <h4>Решебник 1</h4>
                <hr>
                <div style="opacity: 0.4">
                    <div class="circle"></div>
                    <div class="award-star">
                        <i class="fas fa-star" style="color: white;"></i>
                    </div>
                </div>
                <div class="text-muted mt-3">Сделано 0/10 задач</div>
                <hr>
                <span class="badge badge-primary">0%</span>
            </div>
        </div>
    </div>
    <div class="col-4 mt-3">
        <div class="card">
            <div class="card-body text-center">
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle" data-value="1"></div>
                <div class="award-star">
                    <i class="fas fa-star" style="color: white;"></i>
                </div>
                <div class="text-muted mt-3">Завершить 10/10 задач</div>
                <hr>
                <small class="text-muted-reg">
                    17/06/19
                </small>
            </div>
        </div>
    </div>
    <div class="col-4 mt-3">
        <div class="card">
            <div class="card-body text-center">
                <h4>Комментатор 1</h4>
                <hr>
                <div class="circle" data-value="1"></div>
                <div class="award-star bg-warning">
                    <i class="fas fa-comments" style="color: white;"></i>
                </div>
                <div class="text-muted mt-3">Написать 10/10 комментариев</div>
                <hr>
                <small class="text-muted-reg">
                    17/06/19
                </small>
            </div>
        </div>
    </div>
    <div class="col-4 mt-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="complete-awards" style="background-image: url(/upload/bg-sprinkles.svg)"></div>
                <h4>Решебник 1</h4>
                <hr>
                <div class="circle" data-value="1"></div>
                <div class="award-star">
                    <i class="fas fa-star" style="color: white;"></i>
                </div>
                <div class="text-muted mt-3">Завершить 10/10 задач</div>
                <hr>
                <small class="text-muted-reg">
                    17/06/19
                </small>
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


