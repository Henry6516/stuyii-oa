<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * ChannelSearch represents the model behind the search form about `backend\models\Channel`.
 */
class ChannelSearch extends Channel
{
    public $cate;
    public $subCate;
    public $introducer;
    public $mainImage;
    //public $number;


    /**
     * @return string
     */
    //public function getAliasCnName(): string
    public function getAliasCnName()
    {
        return $this->AliasCnName;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stockUp', 'pid', 'IsLiquid', 'IsPowder', 'isMagnetism', 'IsCharged', 'goodsid', 'SupplierID', 'StoreID', 'bgoodsid', 'number'], 'integer'],
            [['mapPersons', 'introducer', 'isVar', 'cate', 'subCate', 'description', 'GoodsName', 'AliasCnName', 'AliasEnName', 'PackName',
                'Season', 'DictionaryName', 'SupplierName', 'StoreName', 'completeStatus', 'Purchaser', 'possessMan1', 'possessMan2',
                'picUrl', 'GoodsCode', 'achieveStatus', 'devDatetime', 'developer', 'updateTime', 'picStatus', 'AttributeName', 'cate',
                'subCat', 'wishpublish', 'goodsstatus', 'stockdays', 'mid'/*, 'extendStatus'*/], 'safe'],
            [['DeclaredValue'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param  $model_name
     * @return ActiveDataProvider
     */
    public function search($params, $model_name = '', $unit = '')
    {
        $query = ChannelSearch::find()->orderBy('devDatetime desc');

        if (!isset($params['ChannelSearch'])) {
            $params['ChannelSearch']['completeStatus'] = ['未设置', 'eBay已完善', 'Wish已完善', 'Joom已完善',
                'Wish已完善|eBay已完善', 'Wish已完善|Joom已完善', 'Joom已完善|eBay已完善'];
        }

        if (isset($params['ChannelSearch']['completeStatus']) && is_array($params['ChannelSearch']['completeStatus'])) {
            $params['ChannelSearch']['completeStatus'] = implode(',', $params['ChannelSearch']['completeStatus']);
        }
        //如果是数据中中心模块则只返回已完善数据
        if ($model_name == 'oa-data-center') {
            $query->where(['<>', 'completeStatus', '']);
        }

        //如果是数据中中心的Wish待刊登模块则只返回wish平台未完善数据
        if ($model_name == 'oa-data-center' && $unit == 'Wish待刊登') {
            $query->where(['wishpublish' => 'Y']);
            $query->andWhere(['not like', "isnull(DictionaryName,'')", 'wish']);
            $query->andWhere(['OR', ['not like', 'completeStatus', 'Wish已完善'], ['completeStatus' => null]]);
        }

        //如果是平台信息模块则默认返回去除Wish和eBay都已完善数据
        if ($model_name == 'channel') {

            $params['pageSize'] = isset($params['pageSize']) && $params['pageSize'] ? $params['pageSize'] : 6;
        }
        $query->joinWith(['oa_goods']);
        $query->joinWith(['oa_templates']);
        //返回当前登录用户
        $user = yii::$app->user->identity->username;
        //根据角色 过滤
        $role_sql = yii::$app->db->createCommand("SELECT t2.item_name as role FROM [user] t1,[auth_assignment] t2 
                    WHERE  t1.id=t2.user_id and
                    username='$user'
                    ");
        $role = $role_sql->queryAll();
        $roleList = ArrayHelper::getColumn($role, 'role');
        $roles = implode(',', $roleList);

        // 返回当前用户管辖下的用户
        $sql = "oa_P_users '{$user}'";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $users = [];


        //计算当前角色拥有的平台和仓库权限
        $constraintSql = 'SELECT t1.store,t3.plat  FROM [user] t1,[auth_assignment] t2 ,[auth_admin_role] t3
                    WHERE  t1.id=t2.user_id and t2.item_name = t3.role and
                    username=:username';

        $constraintRet = $connection->createCommand($constraintSql, [':username' => $user])->queryAll();
        $stores = '';
        $plats = '';
        foreach ($constraintRet as $ret) {
            $stores = empty($stores) ? $stores : $stores . ',';
            $stores .= $ret['store'];
            $plats = empty($plats) ? $plats : $plats . ',';
            $plats .= $ret['plat'];
        }
        $storeList = !empty($stores) ? explode(',', $stores) : [];
        $platsList = !empty($plats) ? explode(',', $plats) : [];

        foreach ($result as $user) {
            array_push($users, $user['userName']);
        }
        if ($unit == '平台信息') {
            if (strpos($roles, '销售') === false) {
                if (strpos($roles, '部门主管') !== false || $unit == 'Wish待刊登') {
                    $query->andWhere(['in', 'oa_goods.developer', $users]);
                } elseif (strpos($roles, '开发') !== false) {
                    $query->andWhere(['in', 'oa_goods.developer', $users]);
                } elseif (strpos($roles, '美工') !== false) {
                    $query->andWhere(['in', 'possessMan1', $users]);
                }
            }
            //根据仓库和平台过滤产品
            if (!empty($storeList)) {
                $query->andWhere(['in', 'oa_goodsinfo.storeName', $storeList]);
            }
            if (!empty($platsList)) {
                foreach ($platsList as $plat) {
                    $query->andWhere(['not like', 'oa_goodsinfo.dictionaryName', $plat]);
                }
            }

        }

        if ($unit == '销售产品列表') {
            //过滤销售员产品
            $map[0] = 'or';
            foreach ($users as $k => $username) {
                $map[$k + 1] = ['like', 'mapPersons', $username];
            }
            $query->andWhere($map);

            //过滤禁售平台产品
            if ($roles == 'Wish销售') {
                $query->andWhere(['not like', 'DictionaryName', 'wish']);
            }
            if ($roles == 'SMT销售') {
                $query->andWhere(['not like', 'DictionaryName', 'SMT']);
            }
            if ($roles == 'Joom销售') {
                $query->andWhere(['not like', 'DictionaryName', 'Joom']);
            }
            if ($roles == 'eBay销售') {
                $query->andWhere(['not like', 'DictionaryName', 'eBay']);
            }
            //var_dump($roles);exit;
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['pageSize']) && $params['pageSize'] ? $params['pageSize'] : 20,
            ],
//            'sort' => [
//                'defaultOrder' => [
//                ]
//            ],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                /* 其它字段不要动 */
                'GoodsCode',
                'GoodsName',
                'SupplierName',
                'StoreName',
                'developer',
                'Purchaser',
                'possessMan1',
                'devDatetime',
                'updateTime',
                'achieveStatus',
                'DictionaryName',
                'completeStatus',
                'mid',
                'extendStatus',
                'mapPersons',
                /* 下面这段是加入的 */
                /*=============*/
                'cate' => [
                    'asc' => ['oa_goods.cate' => SORT_ASC],
                    'desc' => ['oa_goods.cate' => SORT_DESC],
                    'label' => '主分类'
                ],
                'subCate' => [
                    'asc' => ['oa_goods.subCate' => SORT_ASC],
                    'desc' => ['oa_goods.subCate' => SORT_DESC],
                    'label' => '子分类'
                ],
                'introducer' => [
                    'asc' => ['oa_goods.introducer' => SORT_ASC],
                    'desc' => ['oa_goods.introducer' => SORT_DESC],
                    'label' => '推荐人'
                ],
                'isVar',
                'stockUp',
                'wishpublish',
                'goodsstatus',
                'stockdays',
                'number',
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pid' => $this->pid,
            'IsLiquid' => $this->IsLiquid,
            'IsPowder' => $this->IsPowder,
            'isMagnetism' => $this->isMagnetism,
            'IsCharged' => $this->IsCharged,
            'DeclaredValue' => $this->DeclaredValue,
            'goodsid' => $this->goodsid,
            'SupplierID' => $this->SupplierID,
            'StoreID' => $this->StoreID,
            'bgoodsid' => $this->bgoodsid,
            'isVar' => $this->isVar,
            'wishpublish' => $this->wishpublish,
            'goodsstatus' => $this->goodsstatus,
            'stockdays' => $this->stockdays,
            'number' => $this->number,
        ]);

        if ($this->devDatetime) {
            $createDate = explode('/', $this->devDatetime);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),devDatetime,121)', $createDate[0]],
                ['<=', 'convert(varchar(10),devDatetime,121)', $createDate[1]],
            ]);
        }
        if ($this->updateTime) {
            $updateDate = explode('/', $this->updateTime);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),updateTime,121)', $updateDate[0]],
                ['<=', 'convert(varchar(10),updateTime,121)', $updateDate[1]],
            ]);
        }
        if ($this->mid == 'Y') {
            $query->andFilterWhere(['>', 'mid', 0]);
        }
        if ($this->mid == 'N') {
            $query->andWhere(['mid' => null]);
        }

        //推广状态
        if ($this->extendStatus == '已推广') {



            $query->andFilterWhere([
                'OR',
                [
                    'AND',
                    [
                        'EXISTS',
                        OaGoodsinfoExtendStatus::find()
                        //(new Query())->select('*')->from('oa_goodsinfo_extend_status')
                            ->where("goodsinfo_id={{oa_goodsinfo}}.pid")
                            ->andWhere(['saler' => $user])
                            ->andWhere(['status' => '已推广'])
                            //->exists()
                    ],
                    ['like', 'mapPersons', $user]
                ],
                [
                    'AND',
                    ['not like', 'mapPersons', $user],
                    ['extendStatus' => '已推广']
                ]
            ]);
        }
        if ($this->extendStatus == '未推广') {
            //var_dump(1111);exit;
            $query->andWhere([
                'OR',
                [
                    'AND',
                    ['like', 'mapPersons', $user],
                    [
                        'OR',
                        [
                            'EXISTS',
                            (new Query())->select('*')->from('oa_goodsinfo_extend_status')
                                ->where("goodsinfo_id={{oa_goodsinfo}}.pid")
                                ->andWhere(['saler' => $user])
                                ->andWhere(['status' => '未推广'])
                        ],
                        [
                            'NOT EXISTS',
                            (new Query())->select('*')->from('oa_goodsinfo_extend_status')
                                ->where("goodsinfo_id={{oa_goodsinfo}}.pid")
                                ->andWhere(['saler' => $user])
                        ]
                    ]
                ],
                [
                    'AND',
                    ['not like', 'mapPersons', $user],
                    ["ISNULL(extendStatus,'未推广')" => '未推广']
                ]

            ]);
        }

        //完成状态


        if (!empty($this->completeStatus)) {
            $complete_status_condition = explode(',', $this->completeStatus);
//            $this->completeStatus = explode(',', $this->completeStatus);
//            $complete_status_condition = $this->completeStatus;

            //var_dump($this->completeStatus);exit;
            $completeStatus = ['or'];
            foreach ($complete_status_condition as $k => $v) {
                if ($v == '未设置') {
                    $completeStatus[$k + 1] = ['or', ['completeStatus' => null], ['completeStatus' => '']];
                }
                if ($v == 'eBay已完善') {
                    $completeStatus[$k + 1] =
                        [
                            'and',
                            ['like', 'completeStatus', 'eBay已完善'],
                            [
                                'and',
                                ['not like', 'completeStatus', 'Wish已完善'],
                                ['not like', 'completeStatus', 'Joom已完善'],
                            ]
                        ];
                }
                if ($v == 'Wish已完善') {
                    $completeStatus[$k + 1] =
                        [
                            'and',
                            ['like', 'completeStatus', 'Wish已完善'],
                            [
                                'and',
                                ['not like', 'completeStatus', 'eBay已完善'],
                                ['not like', 'completeStatus', 'Joom已完善'],
                            ]
                        ];
                }
                if ($v == 'Joom已完善') {
                    $completeStatus[$k + 1] =
                        [
                            'and',
                            ['like', 'completeStatus', 'Joom已完善'],
                            [
                                'and',
                                ['not like', 'completeStatus', 'eBay已完善'],
                                ['not like', 'completeStatus', 'Wish已完善'],
                            ]
                        ];
                }
                if ($v == 'Wish已完善|eBay已完善') {
                    $completeStatus[$k + 1] = [
                        'and',
                        ['like', 'completeStatus', 'eBay已完善'],
                        ['like', 'completeStatus', 'Wish已完善'],
                        ['not like', 'completeStatus', 'Joom已完善'],
                    ];
                }
                if ($v == 'Wish已完善|Joom已完善') {
                    $completeStatus[$k + 1] = [
                        'and',
                        ['like', 'completeStatus', 'Joom已完善'],
                        ['like', 'completeStatus', 'Wish已完善'],
                        ['not like', 'completeStatus', 'eBay已完善'],
                    ];
                }
                if ($v == 'Joom已完善|eBay已完善') {
                    $completeStatus[$k + 1] = [
                        'and',
                        ['like', 'completeStatus', 'Joom已完善'],
                        ['like', 'completeStatus', 'eBay已完善'],
                        ['not like', 'completeStatus', 'Wish已完善'],
                    ];
                }
                if ($v == 'Wish已完善|eBay已完善|Joom已完善') {
                    $completeStatus[$k + 1] = [
                        'and',
                        ['like', 'completeStatus', 'Joom已完善'],
                        ['like', 'completeStatus', 'eBay已完善'],
                        ['like', 'completeStatus', 'Wish已完善'],
                    ];
                }

            }
            $query->andWhere($completeStatus);
        }

        $query
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'GoodsName', $this->GoodsName])
            ->andFilterWhere(['like', 'AliasCnName', $this->AliasCnName])
            ->andFilterWhere(['like', 'AliasEnName', $this->AliasEnName])
            ->andFilterWhere(['like', 'PackName', $this->PackName])
            ->andFilterWhere(['like', 'Season', $this->Season])
            ->andFilterWhere(['like', 'DictionaryName', $this->DictionaryName])
            ->andFilterWhere(['like', 'SupplierName', $this->SupplierName])
            ->andFilterWhere(['like', 'StoreName', $this->StoreName])
            ->andFilterWhere(['like', 'Purchaser', $this->Purchaser])
            ->andFilterWhere(['like', 'possessMan1', $this->possessMan1])
            ->andFilterWhere(['like', 'possessMan2', $this->possessMan2])
            ->andFilterWhere(['like', 'picUrl', $this->picUrl])
            ->andFilterWhere(['like', 'GoodsCode', $this->GoodsCode])
            ->andFilterWhere(['like', 'achieveStatus', $this->achieveStatus])
            ->andFilterWhere(['like', 'oa_goods.developer', $this->developer])
            ->andFilterWhere(['like', 'picStatus', '已完善'])
            ->andFilterWhere(['like', 'AttributeName', $this->AttributeName])
            ->andFilterWhere(['like', 'oa_goods.cate', $this->cate])
            ->andFilterWhere(['like', 'oa_goods.subCate', $this->subCate])
            ->andFilterWhere(['like', 'oa_goodsInfo.stockUp', $this->stockUp])
            ->andFilterWhere(['like', 'oa_goodsInfo.mapPersons', $this->mapPersons])
            ->andFilterWhere(['like', 'oa_goods.introducer', $this->introducer]);
        /*Yii::$app->db->cache(function ($db) use ($dataProvider) {
            $dataProvider->prepare();
        }, 60);*/
        return $dataProvider;
    }
}
