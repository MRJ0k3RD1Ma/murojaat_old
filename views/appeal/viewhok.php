<div class="row">
    <div class="col-md-6">

        <?= $this->render('view/_appeal',['model'=>$model])?>

    </div>
    <div class="col-md-6">

        <?= $this->render('view/_appealer',['model'=>$model])?>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php
            if($model->status == 4){
        ?>
                <?= $this->render('view/_close',['model'=>$model])?>

            <?php }else{?>
                <h4>Мурожаат кўриб чиқиш жараёнида</h4>
        <?php } ?>

    </div>
</div>