<?php
namespace app\models;

use app\components\LogActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\behaviors\TimestampBehavior;
use app\models\queries\HostQuery;

/**
 * Host model
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
class HostGroupInfo extends LogActiveRecord
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '组名',
            'creator' => '创建人',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'creator'], 'string'],
        ];
    }

    /**
     * @param $id
     * @return string
     * 获取组名
     */
    public static function getNameById($id){
        $groupName = "";
        $group = static::findOne($id);
        if($group){
            $groupName = $group->name;
        }
        return $groupName;
    }

    /**
     * @return mixed
     * 获取所有分组信息
     */
    public static function getGroupList(){
        return static::find()->all();
    }
}
