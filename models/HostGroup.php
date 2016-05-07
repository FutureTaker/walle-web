<?php
namespace app\models;

use app\components\LogActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * HostGroup model
 *
 * @property integer $id
 * @property string $username
 * @property string $is_email_verified
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirmation_token
 * @property string $email
 * @property string $avatar
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $password write-only password
 */
class HostGroup extends LogActiveRecord
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'host' => '主机id',
            'group' => '所属组id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['host', 'group'], 'int'],
        ];
    }

    /**
     * @param $hostId
     * @return mixed
     * 获取主机所有组关联
     */
    public function findAllByHost($hostId){
        return static::find()
            ->where(['host' => $hostId])->all();
    }

    /**
     * @param $hostId
     * @return string
     * 获取所在所有组名
     */
    public static function findGroupNameStr($hostId){
        $groupName = array();
        $hostGroupList = self::findAllByHost($hostId);
        foreach ($hostGroupList as $hostGroup){
            $name = HostGroupInfo::getNameById($hostGroup->group);
            if($name){
                array_push($groupName,$name);
            }
        }
        return implode(",", $groupName);
    }

}
