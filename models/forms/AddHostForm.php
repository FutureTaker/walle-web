<?php

namespace app\models\forms;

use app\models\Host;
use app\models\HostGroup;
use yii;
use yii\base\Model;

class AddHostForm extends Model {
    public $id;
    public $ip;
    public $idc;
    public $state;
    public $desc;
    public $created_at;
    public $updated_at;
    public $creator;
    public $host_group;


    public function attributeLabels()
    {
        return [
            'ip' => '主机ip',
            'idc' => '所属机房',
            'state' => '状态',
            'desc' => '描述',
            'host_group'=>'主机分组',
        ];
    }

    public function rules() {
        return [
            ['ip','ip'],
            [['idc', 'state', 'desc', 'host_group'], 'required'],
            ['state', 'in', 'range' => [Host::HOST_ACTIVE,Host::HOST_INACTIVE]],
        ];
    }

    public function saveHost($host_group) {
        if ($this->validate()) {
            /***涉及多表操作,采用事务**/
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $current_date = date("Y-m-d H:i:s");
                $host = Host::findById($this->id);
                if (empty($host)) {
                    $host = new Host();
                    $host->ip = $this->ip;
                } else {
                    //先删除之前分组
                    HostGroup::deleteByHostId($this->id);
                }
                $host->idc = $this->idc;
                $host->state = $this->state;
                $host->desc = $this->desc;
                $host->created_at = $current_date;
                $host->updated_at = $current_date;
                $host->creator = Yii::$app->user->id;
                if ($host->save(false)) {
                    $id = $host->attributes['id'];
                    //批量生成主机与组间关系
                    foreach ($host_group as $group) {
                        $host_group_model = new HostGroup();
                        $host_group_model->host = $id;
                        $host_group_model->group = $group;
                        $host_group_model->save(false);
                    }
                    $transaction->commit();
                    return $host;
                }
            }catch (\Exception $e){
                $transaction->rollBack();
            }

            return null;
        }
    }
}