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
        $hostList = Host::find()->orderBy('id desc');
        $groupList = HostGroupInfo::getGroupList();
        if(\Yii::$app->request->isPost) {
            $ip = \Yii::$app->request->post('ip');
            $idc = \Yii::$app->request->post('idc');
            $group = \Yii::$app->request->post('group');
            $state = \Yii::$app->request->post('state');
        }else{
            $ip = \Yii::$app->request->get('ip');
            $idc = \Yii::$app->request->get('idc');
            $group = \Yii::$app->request->get('group');
            $state = \Yii::$app->request->get('state');
        }

        if ($ip) {
            $hostList->andFilterWhere(['like', "ip", $ip]);
        }
        if ($idc){
            $hostList->andFilterWhere(['like', "idc", (int)$idc]);
        }
        if ($state) {
            $hostList->andFilterWhere(['like', "state", $state]);
        }
        if ($group){
            //找到组里所有的host_id
            $hostList->andFilterWhere(['in', "id", HostGroup::finAllHostIdByGroup((int)$group)]);
        }

        $totalCount = $hostList->count();
        $pages = new Pagination(['totalCount' => $totalCount, 'pageSize' => $size,
            'params'=>array_merge($_GET, ['ip' => $ip, 'idc' => $idc, 'group' => $group, 'state' => $state])]);
        $hostList = $hostList->offset(($page - 1) * $size)->limit($size)->asArray()->all();
        return $this->render('list', [
            'idcSelect'=>$idc,
            'ipSelect'=>$ip,
            'stateSelect'=>$state,
            'groupSelect'=>$group,
            'hostList' => $hostList,
            'idcList' => $idcList,
            'groupList' => $groupList,
            'pages' => $pages,
            'totalCount'=> $totalCount,
        ]);
    }

    /**
     * @return mixed
     * 主机下线
     */
    public function actionChange(){
        if(Yii::$app->request->get()) {
            $id = Yii::$app->request->get()['id'];
            $state = Yii::$app->request->get()['state'];
            if($state == Host::HOST_INACTIVE){
                $state = Host::HOST_ACTIVE;
            }else{
                $state = Host::HOST_INACTIVE;
            }
            Host::updateStateById($id, $state);
        }
        return $this->redirect('@web/host/list');
    }


    /**
     * 新增主机
     */
    public function actionAdd() {
        $model = new AddHostForm();
        $id = 0;
        if(Yii::$app->request->get()) {
            $id = Yii::$app->request->get()['id'];
        }
        if ($model->load(Yii::$app->request->post()) ) {
            $model->id = $id;
            $host_group = Yii::$app->request->post()['AddHostForm']['host_group'];
            if ($host = $model->saveHost($host_group)) {
                return $this->redirect('@web/host/list');
            }
            else {
                throw new \Exception(yii::t('host', 'ip exists'));
            }
        }else{
            if($id){
                $host = Host::findById($id);
                if($host){
                    $model->ip = $host->ip;
                    $model->idc = $host->idc;
                    $model->state = $host->state;
                    $model->desc = $host->desc;
                    //获取主机所在所有组id
                    $model->host_group = HostGroup::findGroupId($id);
                }
            }
        }
        //获取所有分组
        $group_array = HostGroupInfo::getGroupArray();
        return $this->render('add', [
            'model' => $model,
            'id'=>$id,
            'group_array' => $group_array
        ]);
    }

}
