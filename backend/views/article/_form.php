<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yuncms\article\models\Article;
use yuncms\article\models\Category;
use xutl\ueditor\UEditor;
use xutl\inspinia\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\article\models\Article $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>


<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'sub_title')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::getDropDownList(), 'id', 'name')); ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'status')->inline(true)->radioList([Article::STATUS_ACTIVE => Yii::t('article', 'Active'), Article::STATUS_PENDING => Yii::t('article', 'Pending')]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'cover')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'cover')->fileInput(['style' => 'max-width:200px;max-height:200px']); ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'is_top')->inline(true)->radioList([true => Yii::t('app', 'Yes'), false => Yii::t('app', 'No')]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'is_hot')->inline(true)->radioList([true => Yii::t('app', 'Yes'), false => Yii::t('app', 'No')]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'is_best')->inline(true)->radioList([true => Yii::t('app', 'Yes'), false => Yii::t('app', 'No')]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'description')->textInput(['maxlength' => true, 'rows' => 5]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'content')->widget(UEditor::className(), [

]) ?>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>


<?php ActiveForm::end(); ?>

