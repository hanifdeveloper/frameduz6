<header style="margin-top: -25px;">
    <div class="collapse <?= $background; ?>" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">Description</h4>
                    <p class="text-white"><?= $description; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark <?= $background; ?> shadow-sm">
        <div class="container d-flex justify-content-between">
            <a href="javascript:void(0);" class="navbar-brand d-flex align-items-center"><strong><?= $page_title ; ?></strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        </div>
    </div>
</header>