<?php

namespace app\models\forms;

use app\models\Host;
use yii;
use yii\base\Model;

class AddHostForm extends Model {

    public $ip;
    public $idc;
    public $state;
    public $desc;
    public $created_at;
    public $updated_at;
    public $creator;


    public function attributeLabels()
    {
        return [
            'ip' => '主机ip',
            'idc' => '所属机房',
            'state' => '状态',
            'desc' => '描述',
        ];
    }

    public function rules() {
        return [
            [['ip',  'idc', 'state', 'desc'], 'required'],
            ['state', 'in', 'range' => [Host::HOST_ACTIVE,Host::HOST_INACTIVE]],
        ];
    }

    public function saveHost() {
        if ($this->validate()) {
            $current_date = date("Y-m-d H:i:s");
            $host = new Host();
            $host->ip = $this->ip;
            $host->idc = $this->idc;
            $host->state = $this->state;
            $host->desc = $this->desc;
            $host->created_at = $current_date;
            $host->updated_at = $current_date;
            $host->creator = Yii::$app->user->id;

            if ($host->save(false)) {
                print_r($host->getErrors());
                return $host;
            }

            return null;
        }
    }
}