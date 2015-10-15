<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace voskobovich\gii\generators\model;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Generator
 * @package app\gii\generators\model
 */
class Generator extends \yii\gii\generators\model\Generator
{
    public $ns = 'app\models';
    public $baseClass = 'voskobovich\base\db\ActiveRecord';
    public $useTablePrefix = true;
    public $enableI18N = true;
    public $messageCategory = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                ['db', 'ns', 'tableName', 'modelClass', 'baseClass', 'queryNs', 'queryClass', 'queryBaseClass'],
                'filter',
                'filter' => 'trim'
            ],
            [
                ['ns', 'queryNs'],
                'filter',
                'filter' => function ($value) {
                    return trim($value, '\\');
                }
            ],
            [['db', 'tableName', 'baseClass', 'queryNs', 'queryBaseClass'], 'required'],
            [
                ['db', 'modelClass', 'queryClass'],
                'match',
                'pattern' => '/^\w+$/',
                'message' => 'Only word characters are allowed.'
            ],
            [
                ['ns', 'baseClass', 'queryNs', 'queryBaseClass'],
                'match',
                'pattern' => '/^[\w\\\\]+$/',
                'message' => 'Only word characters and backslashes are allowed.'
            ],
            [
                ['tableName'],
                'match',
                'pattern' => '/^([\w ]+\.)?([\w\* ]+)$/',
                'message' => 'Only word characters, and optionally spaces, an asterisk and/or a dot are allowed.'
            ],
            [['db'], 'validateDb'],
            [['ns', 'queryNs'], 'validateNamespace'],
            [['tableName'], 'validateTableName'],
            [['modelClass'], 'validateModelClass', 'skipOnEmpty' => false],
            [['baseClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::className()]],
            [['queryBaseClass'], 'validateClass', 'params' => ['extends' => ActiveQuery::className()]],
            [
                ['generateRelations'],
                'in',
                'range' => [self::RELATIONS_NONE, self::RELATIONS_ALL, self::RELATIONS_ALL_INVERSE]
            ],
            [['generateLabelsFromComments', 'useTablePrefix', 'useSchemaName', 'generateQuery'], 'boolean'],
            [['enableI18N'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if ($this->template == 'base') {
            $this->ns = 'app\base\models';
        } else {
            $this->ns = 'app\models';
        }

        return parent::beforeValidate();
    }
}