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
            'id'=>'主键id',
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
     * @param $groupId
     * @return mixed
     * 找到组内所有记录
     */
    public function findAllByGroup($groupId){
        return static::find()
            ->where(['group' => $groupId])->all();
    }

    /**
     * @param $groupId
     * @return mixed
     * 获取组内所有主机id
     */
    public static function finAllHostIdByGroup($groupId){
        $host_array = array();
        $group_list =  self::findAllByGroup($groupId);
        foreach ($group_list as $group){
            array_push($host_array, $group->host);
        }
        return $host_array;
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

    /**
     * @param $hostId
     * @return string
     * 获取所在所有组Id
     */
    public static function findGroupId($hostId){
        $groupId = array();
        $hostGroupList = self::findAllByHost($hostId);
        foreach ($hostGroupList as $hostGroup){
            array_push($groupId,$hostGroup->group);
        }
        return $groupId;
    }

    /**
     * @param $host_id
     * 删除主机所在分组关联
     */
    public static function deleteByHostId($host_id){
        HostGroup::deleteAll(['host' => $host_id]);
    }

}
