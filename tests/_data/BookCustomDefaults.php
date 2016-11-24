<?php

namespace data;

use voskobovich\linker\LinkerBehavior;
use Yii;

/**
 * Class BookCustomDefaults
 * @package data
 */
class BookCustomDefaults extends Book
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_ids_none', 'review_ids_null', 'review_ids_constant', 'review_ids_closure'], 'safe'],
            [['name', 'year'], 'required'],
            [['year'], 'integer'],
            [['name'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'linkerBehavior' => [
                'class' => LinkerBehavior::className(),
                'relations' => [
                    'review_ids_none' => [
                        'reviews',
                    ],
                    'review_ids_null' => [
                        'reviews',
                        'updated' => [
                            'fallbackValue' => null,
                        ]
                    ],
                    'review_ids_constant' => [
                        'reviews',
                        'updater' => [
                            'fallbackValue' => 7,
                        ]
                    ],
                    'review_ids_closure' => [
                        'reviews',
                        'updater' => [
                            'fallbackValue' => function ($updater) {
                                $db = Yii::$app->db;

                                /**
                                 * This is Example code.
                                 *
                                 * $db = $model::getDb();
                                 * OR
                                 * $secondaryModelClass = $model->getRelation($relationName)->modelClass;
                                 * $db = $secondaryModelClass::getDb();
                                 */

                                $defaultValue = $db
                                    ->createCommand('SELECT value FROM settings WHERE key="default_review"')
                                    ->queryScalar();

                                return $defaultValue;
                            },
                        ]
                    ]
                ]
            ]
        ];
    }
}
