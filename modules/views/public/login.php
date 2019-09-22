<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AdminLoginAsset;
AdminLoginAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?php Yii::$app->language;?>">

<head>
    <title><?php echo $this->title;?></title>
    <meta charset="<?php Yii::$app->charset;?>">
    <?php
    $this->registerMetaTag(['name' => 'viewport','content' => 'width=device-width, initial-scale=1.0']);
    $this->registerMetaTag(['http-equiv' => 'Content-Type','content' => 'text/html; charset=utf-8']);
    $this->registerCsrfMetaTags();
    $this->head();
    ?>

</head>
<?php
$this->beginBody();
?>
<body class="login-bg">
<div class="row-fluid login-wrapper">
    <?php
    $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => '{error}{input}'
        ]
    ])
    ?>
    <a class="brand" href="index.html"></a>
        <div class="span4 box">
            <div class="content-wrap">
                <h6><?php echo \Yii::$app->params['admin']['title']?></h6>
                <?php echo $form->field($model,'adminuser')->textInput(['class' => 'span12','placeholder'=>'管理员账号']);?>
                <?php echo $form->field($model,'adminpass')->textInput(['class' => 'span12','placeholder'=>'管理员密码'])?>
                <a href="<?php echo \yii\helpers\Url::to(['public/seekpassword'])?>" class="forgot">忘记密码?</a>
                <?php echo $form->field($model,'rememberMe')->checkbox([
                    'id' => 'remember-me',
                    'template' => '<div class="remember">{input}<label for="remember-me">记住我</label></div>'])?>
                <?php echo Html::submitButton('登录',['class' => 'btn-glow primary login'])?>
            </div>
        </div>
    <?php ActiveForm::end();?>
</div>
<?php
$Js = <<<JS
$(function() {
 // bg switcher
        var btns = $(".bg-switch .bg");
        btns.click(function(e) {
            e.preventDefault();
            btns.removeClass("active");
            $(this).addClass("active");
            var bg = $(this).data("img");

            $("html").css("background-image", "url('img/bgs/" + bg + "')");
        });

    });
JS;
$this->registerJs($Js);
?>
</body>
<?php
$this->endBody();
?>
</html>
<?php
$this->endPage();
?>