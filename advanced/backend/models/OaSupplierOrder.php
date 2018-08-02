<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_supplierOrder".
 *
 * @property int $id
 * @property string $supplierName
 * @property string $goodsCode
 * @property string $billNumber
 * @property string $billStatus
 * @property string $purchaser
 * @property string $syncTime
 * @property int $totalNumber
 * @property string $amt
 * @property string $expressNumber
 * @property string $paymentStatus
 * @property string $orderTime
 * @property string $createdTime
 * @property string $updatedTime
 * @property string $expressStatus
 * @property string $deliveryStatus
 */
class OaSupplierOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oa_supplierOrder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplierName', 'goodsCode', 'billNumber', 'billStatus','deliveryStatus','expressStatus', 'purchaser', 'expressNumber', 'paymentStatus'], 'string'],
            [['syncTime', 'orderTime', 'createdTime', 'updatedTime'], 'safe'],
            [['totalNumber'], 'integer'],
            [['amt'], 'number'],
            [['billNumber'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplierName' => '供应商名称',
            'goodsCode' => '商品编码',
            'billNumber' => '采购单号',
            'billStatus' => '采购单状态',
            'purchaser' => '线下采购',
            'syncTime' => '同步时间',
            'totalNumber' => '总数量',
            'amt' => '总金额',
            'deliveryStatus' => '发货状态',
            'expressNumber' => '物流单号',
            'paymentStatus' => '支付状态',
            'expressStatus' => '物流状态',
            'orderTime' => '下单时间',
            'createdTime' => '创建时间',
            'updatedTime' => '更新时间',
        ];
    }


    /**
     * 获取普源订单列表
     * @param $post
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getPyOrderData($post){
        $sql = "SELECT 	om.BillNumber,om.CHECKfLAG,s.SupplierName,om.MakeDate,om.Recorder,om.DelivDate,om.OrderAmount,om.OrderMoney 
                    FROM [dbo].[CG_StockOrderM](nolock) om
                    LEFT JOIN  CG_StockOrderD od ON od.StockOrderNID=om.NID
                    LEFT JOIN B_Goods b ON b.NID=od.GoodsID
                    LEFT JOIN B_GoodsSKU bs ON bs.NID=od.GoodsSKUID 
                    LEFT JOIN B_Supplier s ON s.NID=om.SupplierID
                    WHERE CHECKfLAG=0 ";
        //筛选供应商
        if ($post['supplierName']) {
            $sql .= " AND s.SupplierName LIKE '%".$post['supplierName']."%'";
        }
        //筛选订单时间
        if ($post['daterange']) {
            $date = explode(' - ', $post['daterange']);
            $sql .= " AND om.MakeDate BETWEEN '".$date[0]."' AND '".$date[1]." 23:59:59'";
        }
        //筛选订单号
        if ($post['billNumber']) {
            $sql .= " AND om.billNumber LIKE '%".$post['billNumber']."%'";
        }
        //筛选商品编码
        if ($post['supplierName']) {
            $sql .= " AND b.goodsCode LIKE '%".$post['goodsCode']."%'";
        }
        //筛选SKU
        if ($post['supplierName']) {
            $sql .= " AND bs.sku LIKE '%".$post['sku']."%'";
        }
        $sql .= " GROUP BY om.BillNumber,om.CHECKfLAG,s.SupplierName,om.MakeDate,om.Recorder,om.DelivDate,om.OrderAmount,om.OrderMoney";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }
}
