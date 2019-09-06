<div class="card tasks-search-bar">
    <div class="card-body">
        <div class="input-group">
            <input id="searchInput" autocomplete="off" class="form-control form-control-sm form-control-borderless"
                   type="text" placeholder="<?= $GLOBALS["_searchbar"] ?>...">
            <span class="icon-searchbar"><i class="fas fa-search"></i></span>
        </div>
    </div>
</div>
<hr>
<div class="search-container tasks-search-container">
    <div id="searchResult">
        <div class="card">
            <div class="card-body search-empty">
                <p><?= $GLOBALS['_emptysearchbar'] ?></p>
            </div>
        </div>
    </div>
</div>
