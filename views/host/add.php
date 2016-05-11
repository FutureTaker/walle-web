<?php
/**
 * @var yii\web\View $this
 */

if($id){
    $this->title = yii::t('host', 'update host');
}else{
    $this->title = yii::t('host', 'add host');
}

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\user;
?>

<div class="box col-xs-8">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
        <?php if($id){?>
            <?= $form->field($model, 'ip')
                ->textInput(['class' => 'col-xs-5','disabled'=>true])
                ->label(Yii::t('host', 'ip'), ['class' => 'text-right bolder blue col-xs-2']) ?>
        <?php } ?>
        <?php if(empty($id)){?>
            <?= $form->field($model, 'ip')
                ->textInput(['class' => 'col-xs-5',])
                ->label(Yii::t('host', 'ip'), ['class' => 'text-right bolder blue col-xs-2']) ?>
        <?php } ?>


        <div class="clearfix"></div>
        <?= $form->field($model, 'idc')->label(Yii::t('host', 'idc'), ['class' => 'text-right bolder blue col-xs-2'])
            ->dropDownList(\app\models\Idc::getIdcArray(), ['class' => 'col-xs-5',]) ?>
        <div class="clearfix"></div>

        <?= $form->field($model, 'desc')
            ->textarea(['class' => 'col-xs-5',])
            ->label(Yii::t('host', 'desc'), ['class' => 'text-right bolder blue col-xs-2']) ?>
        <div class="clearfix"></div>
        <?= $form->field($model, 'state')->label(Yii::t('host', 'state'), ['class' => 'text-right bolder blue col-xs-2'])
            ->dropDownList([
            \app\models\Host::HOST_ACTIVE => "在线",
            \app\models\Host::HOST_INACTIVE => "下线",
        ], ['class' => 'col-xs-5',]) ?>
        <div class="clearfix"></div>
        <?= $form->field($model, 'host_group')->label(Yii::t('host', 'host_group'), ['class' => 'text-right bolder blue col-xs-2'])
            ->checkBoxList($group_array, ['class' => 'col-xs-5',]) ?>
        <div class="clearfix"></div>
        <div class="box-footer">
        <div class="col-xs-2"></div>
        <div class="form-group" style="margin-top:40px">
            <?php if($id){?>
                <?= Html::submitButton(yii::t('host','update host'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            <?php } ?>
            <?php if(empty($id)){?>
                <?= Html::submitButton(yii::t('host','add host'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            <?php } ?>        </div>
            </div>
        <input type="hidden" name="AddHostForm[id]" value="<?=$id?>">
    </div><!-- /.box-body -->
    <?php ActiveForm::end(); ?>
</div>
