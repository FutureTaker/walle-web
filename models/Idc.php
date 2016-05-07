<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\behaviors\TimestampBehavior;
use app\models\queries\HostQuery;

/**
 * Idc model
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
class Idc extends ActiveRecord
{

    /**
     * @param $id
     * @return string
     */
    public static function getNameById($id){
        $idcName="";
        $idc = static::findOne($id);
        if($idc){
            $idcName = $idc->name;
        }
        return $idcName;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '机房名',
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

    /**
     * @return mixed
     * 获取所有idc信息
     */
    public static function getIdcList(){
        return static::find()->all();
    }

}
