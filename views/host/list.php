<?php
/**
 * @var yii\web\View $this
 */
$this->title = yii::t('host', 'hosts');
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\User;
?>
<div class="box">
    <div class="box-header">
        <form action="<?= Url::to('@web/host/list') ?>" method="POST">
            <input type="hidden" value="<?= \Yii::$app->request->getCsrfToken(); ?>" name="_csrf">
            <div class="col-xs-12 col-sm-8" style="padding-left: 0;margin-bottom: 10px;">
                <div class="input-group">
                    <input type="text" name="ip" class="search-query" placeholder="<?= yii::t('host', 'ip_placeholder') ?>" value="<?= $ipSelect?>">
                    <select style="margin: 10px" name="idc" class="search-query">
                        <option value ="">-- 请选择机房 --</option>
                        <?php foreach ($idcList as $idc) {?>
                            <?php if($idc['id']==$idcSelect){?>
                                <option value="<?= $idc['id']?>" selected><?= $idc['name']?></option>
                            <?php } else{?>
                                <option value="<?= $idc['id']?>"><?= $idc['name']?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>

                    <select style="margin: 10px" name="group" class="search-query">
                        <option value ="">-- 请选择主机组 --</option>
                        <?php foreach ($groupList as $group) {?>
                            <?php if($group['id']==$groupSelect){?>
                                <option value="<?= $group['id']?>" selected><?= $group['name']?></option>
                            <?php } else{?>
                                <option value="<?= $group['id']?>"><?= $group['name']?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <select style="margin: 10px" name="state" class="search-query">
                        <option value ="">-- 请选择主机状态 --</option>
                        <?php if($stateSelect === ""){?>
                            <option value="<?= \app\models\Host::HOST_ACTIVE?>">在线</option>
                            <option value="<?= \app\models\Host::HOST_INACTIVE?>">下线</option>
                        <?php } else if(\app\models\Host::HOST_INACTIVE == $stateSelect){?>
                            <option value="<?= \app\models\Host::HOST_ACTIVE?>">在线</option>
                            <option value="<?= \app\models\Host::HOST_INACTIVE?>" selected>下线</option>
                        <?php }else{?>
                            <option value="<?= \app\models\Host::HOST_ACTIVE ?>" selected>在线</option>
                            <option value="<?= \app\models\Host::HOST_INACTIVE?>">下线</option>
                        <?php } ?>

                    </select>
                    <span class="input-group-btn">
                        <button type="submit"
                                class="btn btn-default btn-sm">
                            查询
                            <i class="icon-search icon-on-right bigger-110"></i>
                        </button>
                    </span>
                </div>
            </div>
        </form>
        <a style="margin: 8px" class="btn btn-default btn-sm" href="<?= Url::to('@web/host/add') ?>">
            <i class="icon-pencil align-top bigger-125"></i>
            <?= yii::t('host', 'add_host') ?>
        </a>
    </div><!-- /.box-header -->
    <div class="clearfix" style="width: 200px;">
        <span style="padding-left: 0px">查询到[<?= $totalCount ?>]条记录</span>
    </div>
    <div class="box-body table-responsive no-padding clearfix">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
<!--                    <th>id</th>-->
                    <th><?= yii::t('host', 'ip') ?></th>
                    <th><?= yii::t('host', 'idc') ?></th>
                    <th><?= yii::t('host', 'host_group') ?></th>
                    <th><?= yii::t('host', 'desc') ?></th>
                    <th><?= yii::t('host', 'state') ?></th>
                    <th><?= yii::t('host', 'creator') ?></th>
                    <th><?= yii::t('host', 'created_at') ?></th>
                    <th><?= yii::t('user', 'option') ?></th>
                </tr>
                <?php foreach ($hostList as $row) {?>
                    <tr>
<!--                        <td>--><?//= $row['id'] ?><!--</td>-->
                        <td><?= $row['ip'] ?></td>
                        <td><?= \app\models\Idc::getNameById($row['idc']) ?></td>
                        <td><?= \app\models\HostGroup::findGroupNameStr($row['id']) ?></td>
                        <td><?= $row['desc'] ?></td>
                        <?php if($row['state']==\app\models\Host::HOST_ACTIVE){?>
                            <td class="text-success">在线</td>
                        <?php } ?>
                        <?php if($row['state']==\app\models\Host::HOST_INACTIVE){?>
                            <td class="text-danger">已下线</td>
                        <?php } ?>
                        <td><?= User::getNameById($row['creator']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <div class="nav">
                                <li>
                                    <a style="padding: 0" data-toggle="dropdown" class="dropdown-toggle" href="javascript:void();">
                                        <i class="icon-cog"></i>&nbsp;<?= yii::t('w', 'option') ?>
                                        <i class="icon-caret-down bigger-110 width-auto"></i>
                                    </a>
                                    <ul class="dropdown-menu data-user">
                                        <li><a href="/host/add?id=<?= $row['id']?>" ><i class="icon-pencil"></i> <?= yii::t('w', 'edit') ?></a></li>
                                        <li><a href="/host/change?id=<?= $row['id']?>&state=<?=$row['state']?>"> <i class="icon-refresh"></i> <?= yii::t('w', 'change') ?></a></li>
                                    </ul>
                                </li>

                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?= LinkPager::widget(['pagination' => $pages]); ?>
        <!-- 模态框（Modal） -->
        <div class="modal fade" id="update-real-name" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?= yii::t('user', 'rename') ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="real-name" class="control-label"><?= yii::t('user', 'label real name') ?>:</label>
                            <input type="text" class="form-control" id="real-name">
                          </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= yii::t('user', 'btn cancel') ?></button>
                        <button type="button" class="btn btn-primary btn-submit"><?= yii::t('w', 'bnt sure') ?></button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.box-body -->
</div>

<script>
    jQuery(function($) {

        $('[data-rel=tooltip]').tooltip({container:'body'});
        $('[data-rel=popover]').popover({container:'body'});

        $('.cnt-user-option').click(function(e) {
            var uid = $(this).parents('.data-user').data('user-id');
            var urlKey = $(this).data('url-key')
            var url = $(this).parents('.data-user').data(urlKey);
            var confirmLabel = $(this).data('confirm')
            if (confirm(confirmLabel)) {
                $.get(url, {uid: uid}, function(o) {
                    if (!o.code) {
                        location.reload();
                    } else {
                        alert(o.msg);
                    }
                })
            }
        })

        $('#update-real-name').on('show.bs.modal', function (e) {
            var me = $(this),
                srcTar = $(e.relatedTarget).parents('.data-user'),
                modalTit = me.find('.modal-title'),
                uid = srcTar.attr('data-user-id'),
                email = srcTar.attr('data-user-email'),
                realname = srcTar.attr('data-user-realname'),
                url = srcTar.attr('data-rename-url'),
                subBtn = me.find('.btn-submit'),
                name = me.find('#real-name');
            name.val(realname)

            var title = modalTit.html();
            modalTit.html(title + '：' + email);

            subBtn.click(function () {
                $.get(url, {uid: uid, realName: name.val()}, function(o) {
                    if (!o.code) {
                        location.reload();
                    } else {
                        alert(o.msg);
                    }
                })
            });
        });
    });
</script>
