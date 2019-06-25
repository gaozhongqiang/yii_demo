
<div class="content">
    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>管理员列表</h3>
                <div class="span10 pull-right">
                    <a href="<?php echo yii\helpers\Url::to(['manage/add'])?>" class="btn-flat success pull-right">
                        <span>&#43;</span>添加新管理员</a></div>
            </div>
            <!-- Users table -->
            <div class="row-fluid table">
                <?php
                if(Yii::$app->session->hasFlash('info')){
                    echo Yii::$app->session->getFlash('info');
                }
                ?>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="span2">管理员ID</th>
                        <th class="span2">
                            <span class="line"></span>管理员账号</th>
                        <th class="span2">
                            <span class="line"></span>管理员邮箱</th>
                        <th class="span3">
                            <span class="line"></span>最后登录时间</th>
                        <th class="span3">
                            <span class="line"></span>最后登录IP</th>
                        <th class="span2">
                            <span class="line"></span>添加时间</th>
                        <th class="span2">
                            <span class="line"></span>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($managers as $key => $value){?>
                    <tr>
                        <td><?php echo $value['id']?></td>
                        <td><?php echo $value['admin_user']?></td>
                        <td><?php echo $value['admin_email']?></td>
                        <td><?php echo $value['login_time']?></td>
                        <td><?php echo $value['login_ip']?></td>
                        <td><?php echo $value['create_time']?></td>
                        <td class="align-right">
                            <a href="<?php echo yii\helpers\Url::to(['manage/edit','id' => $value['id']])?>">修改</a>
                            <a href="<?php echo yii\helpers\Url::to(['manage/del','id' => $value['id']])?>">删除</a>
                        </td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <div class="pagination pull-right">
                <?php
                echo yii\widgets\LinkPager::widget(['pagination' => $pager, 'prevPageLabel' => '&#8249;', 'nextPageLabel' => '&#8250;'])
                ?>
            </div>
            <!-- end users table --></div>
    </div>
</div>
