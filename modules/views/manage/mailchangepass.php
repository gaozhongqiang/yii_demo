<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
?>



    <div class="row-fluid login-wrapper">
        <a class="brand" href="index.html"></a>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => '{error}{input}',
            ],
        ]); ?>
        <div class="span4 box">
            <div class="content-wrap">
                <h6>慕课商城 - 修改密码</h6>
                <?php
                    if (Yii::$app->session->hasFlash('info')) {
                        echo Yii::$app->session->getFlash('info');
                    }
                ?>
                <?php echo $form->field($model, 'admin_user')->hiddenInput(); ?>
                <?php echo $form->field($model, 'admin_pass')->passwordInput(["class" => "span12", "placeholder" => "新密码"]); ?>
                <?php echo $form->field($model, 'repass')->passwordInput(["class" => "span12", "placeholder" => "确认密码"]); ?>
                <a href="<?php echo yii\helpers\Url::to(['public/login']); ?>" class="forgot">返回登录</a>
                <?php echo Html::submitButton('修改', ["class" => "btn-glow primary login"]); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

	<!-- scripts -->
    <script src="assets/admin/js/jquery-latest.js"></script>
    <script src="assets/admin/js/bootstrap.min.js"></script>
    <script src="assets/admin/js/theme.js"></script>

    <!-- pre load bg imgs -->
    <script type="text/javascript">
        $(function () {
            // bg switcher
            var $btns = $(".bg-switch .bg");
            $btns.click(function (e) {
                e.preventDefault();
                $btns.removeClass("active");
                $(this).addClass("active");
                var bg = $(this).data("img");

                $("html").css("background-image", "url('img/bgs/" + bg + "')");
            });

        });
    </script>

