<?php
/**
 * Created by PhpStorm.
 * User: aiguoxin
 * Date: 16/5/6
 * Time: 下午5:29
 */

namespace app\components;
use yii\db\ActiveRecord;


class LogActiveRecord extends ActiveRecord
{
    /**
     * Handler to the current Log File.
     * @var mixed
     */
    protected static $logFile = null;

    public static function log($message) {
        if (empty(\Yii::$app->params['log.dir'])) return;

        $logDir = \Yii::$app->params['log.dir'];
        if (!file_exists($logDir)) return;

        $logFile = realpath($logDir) . '/walle-' . date('Ymd') . '.log';
        if (self::$logFile === null) {
            self::$logFile = fopen($logFile, 'a');
        }

        $message = date('Y-m-d H:i:s -- ') . $message;
        fwrite(self::$logFile, $message . PHP_EOL);
    }

}