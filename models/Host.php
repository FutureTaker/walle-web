<?php
namespace app\models;

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
class Host extends ActiveRecord
{

    // 主机未激活
    const HOST_INACTIVE = 1;

    // 主机激活
    const HOST_ACTIVE = 0;



    /**
     * @return UserQuery custom query class with user scopes
     */
    public static function find()
    {
        return new HostQuery(get_called_class());
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function findById($id){
        $host = static::findOne($id);
        return $host;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ip' => '主机ip',
            'idc' => '所属机房',
            'desc' => '描述',
            'state' => '状态',
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
            [['desc', 'creator'], 'string'],
        ];
    }

}
