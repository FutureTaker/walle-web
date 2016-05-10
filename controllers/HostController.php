<?php

namespace app\controllers;

use app\models\forms\AddHostForm;
use app\models\Host;
use app\models\HostGroup;
use app\models\HostGroupInfo;
use app\models\Idc;
use yii;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use yii\base\InvalidParamException;
use yii\data\Pagination;


class HostController extends Controller {

    /**
     * 主机管理
     */
    public function actionList($page = 1, $size = 20) {
        //获取IDC机房信息
        $idcList = Idc::getIdcList();
        //获取分组信息
        $groupList = HostGroupInfo::getGroupList();

        $hostList = Host::find()->orderBy('id desc');
        $ip = \Yii::$app->request->post('ip');
        $idc = \Yii::$app->request->post('idc');
        $group = \Yii::$app->request->post('group');

        if ($ip) {
            $hostList->andFilterWhere(['like', "ip", $ip]);
        }
        if ($idc){
            $hostList->andFilterWhere(['like', "idc", (int)$idc]);
        }
        if ($group){
            //找到组里所有的host_id
            $hostList->andFilterWhere(['in', "id", HostGroup::finAllHostIdByGroup((int)$group)]);
        }

        $pages = new Pagination(['totalCount' => $hostList->count(), 'pageSize' => $size]);
        $hostList = $hostList->offset(($page - 1) * $size)->limit($size)->asArray()->all();
        return $this->render('list', [
            'idcSelect'=>$idc,
            'ipSelect'=>$ip,
            'groupSelect'=>$group,
            'hostList' => $hostList,
            'idcList' => $idcList,
            'groupList' => $groupList,
            'pages' => $pages,
        ]);
    }


    /**
     * 新增主机
     */
    public function actionAdd() {
        $model = new AddHostForm();

        if ($model->load(Yii::$app->request->post()) ) {
            if ($host = $model->saveHost()) {
                return $this->redirect('@web/host/list');
            }
            else {
                throw new \Exception(yii::t('host', 'ip exists'));
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

}
