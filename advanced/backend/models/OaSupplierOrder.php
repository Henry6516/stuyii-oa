<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_supplierOrder".
 *
 * @property int $id
 * @property string $supplierName
 * @property string $goodsName
 * @property string $billNumber
 * @property string $billStatus
 * @property string $purchaser
 * @property string $syncTime
 * @property int $totalNumber
 * @property string $amt
 * @property string $expressNumber
 * @property string $paymentStatus
 * @property string $orderTime
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
            [['supplierName','goodsName', 'billNumber','expressNumber', 'billStatus', 'deliveryStatus', 'purchaser', 'paymentStatus'], 'string'],
            [['syncTime', 'orderTime', 'updatedTime'], 'safe'],
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
            'goodsName' => '商品名称',
            'billNumber' => '采购单号',
            'billStatus' => '采购单状态',
            'purchaser' => '线下采购',
            'syncTime' => '同步时间',
            'totalNumber' => '总数量',
            'amt' => '总金额',
            'deliveryStatus' => '发货状态',
            'expressNumber' => '物流单号',
            'paymentStatus' => '支付状态',
            'orderTime' => '下单时间',
            'updatedTime' => '更新时间',
        ];
    }


    /**
     * 获取普源订单列表
     * @param $post
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getPyOrderData($post)
    {
        $sql = "SELECT 	om.nid,om.BillNumber,om.CHECKfLAG,s.SupplierName,om.MakeDate,om.Recorder,om.DelivDate,om.OrderAmount,om.OrderMoney 
                    FROM [dbo].[CG_StockOrderM](nolock) om
                    LEFT JOIN  CG_StockOrderD od ON od.StockOrderNID=om.NID
                    LEFT JOIN B_Goods b ON b.NID=od.GoodsID
                    LEFT JOIN B_GoodsSKU bs ON bs.NID=od.GoodsSKUID 
                    LEFT JOIN B_Supplier s ON s.NID=om.SupplierID
                    WHERE CHECKfLAG=0 ";
        //筛选供应商
        if ($post['supplierName']) {
            $sql .= " AND s.SupplierName LIKE '%" . $post['supplierName'] . "%'";
        }
        //筛选订单时间
        if ($post['daterange']) {
            $date = explode(' - ', $post['daterange']);
            $sql .= " AND om.MakeDate BETWEEN '" . $date[0] . "' AND '" . $date[1] . " 23:59:59'";
        }
        //筛选订单号
        if ($post['billNumber']) {
            $sql .= " AND om.billNumber LIKE '%" . $post['billNumber'] . "%'";
        }
        //筛选商品编码
        if ($post['goodsCode']) {
            $sql .= " AND b.goodsCode LIKE '%" . $post['goodsCode'] . "%'";
        }
        //筛选SKU
        if ($post['sku']) {
            $sql .= " AND bs.sku LIKE '%" . $post['sku'] . "%'";
        }
        //过滤为同步的数据
        $sql .= " AND NOT EXISTS (SELECT billNumber FROM oa_supplierOrder WHERE oa_supplierOrder.billNumber=om.BillNumber)";
        $sql .= " GROUP BY om.nid,om.BillNumber,om.CHECKfLAG,s.SupplierName,om.MakeDate,om.Recorder,om.DelivDate,om.OrderAmount,om.OrderMoney";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }


    /**
     * 获取订单明细
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getPyOrderDetail($id)
    {
        $sql = "select m.NID,d.GoodsID,s.Goodscode,s.Goodsname, s.Class,s.Unit,d.amount,gs.BmpFileName,
                      isnull(d.MInPrice,0) as MInPrice,d.price,d.money,D.TaxRate,s.Model,s.Material, 
                      ( select SUM(isnull(id.amount,0)) from CG_StockInD(nolock) id  
                                  inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                                  where im.checkflag = 1 and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber and im.storeid=m.storeid
                      ) as inAmount,  
                      case when d.amount -isnull((select SUM(isnull(id.amount,0)) from CG_StockInD(nolock) id  
                                                  inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                                                  where im.checkflag = 1 and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber and im.storeid=m.storeid
                                                  ),0) > 0 
                          then  d.amount -isnull((select SUM(isnull(id.amount,0)) from CG_StockInD(nolock) id 
                                                  inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                                                  where im.checkflag = 1 and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber and im.storeid=m.storeid),0) 
                        else 0 end as DNoinAmount,  
                      (   select SUM(isnull(id.amount,0)) from CG_StockInD(nolock) id  
                           inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                           where im.checkflag = 0 and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber 
                      ) as CheckAmount,  
                      (  select SUM(isnull(id.CheckQty,0)) from CG_StockInD(nolock) id  
                        inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                        where im.checkflag in (0,1) and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber 
                      ) as zjAmount, 
                      d.TaxPrice,D.TaxMoney,D.AllMoney,s.MaxNum,s.MinNum, D.Remark,gs.SKU,gs.property1,gs.property2,gs.property3, 
                      isnull(s.PackageCount,0) PackageCount,  gs.GoodsSKUStatus as GoodsStatus,  
                      isnull((select number-ReservationNum from KC_CurrentStock(nolock) k  
                              where k.GoodsSKUID=d.GoodsSKUID and k.storeid=m.storeid 
                      ),0) as storenum, d.SupplierName,d.Telphone,d.StockAddress, 
                      case when kc.sellcount1=0 then   cast(gs.SellCount1 as varchar)+'/'+cast(gs.SellCount2 as varchar) +'/'+cast(gs.SellCount3 as varchar) 
                      else  cast(kc.SellCount1 as varchar)+'/'+cast(kc.SellCount2 as varchar)  +'/'+cast(kc.SellCount3 as varchar) 
                      end as salecount,  
                      (case when kc.sellcount1=0 then   cast(gs.SellCount1 as varchar) else  cast(kc.SellCount1 as varchar)  end) as salecount1,  
                      (case when kc.sellcount1=0 then   cast(gs.SellCount2 as varchar) else  cast(kc.SellCount2 as varchar)  end) as salecount2,  
                      (case when kc.sellcount1=0 then   cast(gs.SellCount3 as varchar) else  cast(kc.SellCount3 as varchar)  end) as salecount3,  
                      s.linkurl,s.linkurl2,s.linkurl3,s.linkurl4,s.linkurl5,s.linkurl6,Bsl.LocationName,s.BMPFileName,  
                      case when  (d.amount -isnull((select SUM(isnull(id.amount,0)) from CG_StockInD(nolock) id  
                                                    inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                                                    where im.checkflag = 1 and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber 
                                                    and im.storeid=m.storeid),0))* TaxPrice > 0 
                      then  (d.amount -isnull((select SUM(isnull(id.amount,0)) from CG_StockInD(nolock) id  
                                                        inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                                                        where im.checkflag = 1 and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber 
                                                        and im.storeid=m.storeid),0))* TaxPrice 
                      else 0 end as NoInTaxMoney,  0 as hopeUseNum,  
                      (isnull((select SUM(isnull(id.amount*id.TaxPrice,0)) from CG_StockInD(nolock) id  
                                inner join CG_StockInM(nolock) im on im.NID=id.StockInNID 
                                where im.checkflag = 1 and   id.GoodsSKUID= d.GoodsSKUID and im.StockOrder=m.Billnumber ),0)
                      ) as InTaxMoney,  
                      gs.SkuName, s.Style,  s.PackMsg, s.Notes,   
                      case when IsNull(gs.CostPrice,0) <> 0 then gs.CostPrice else IsNull(s.CostPrice,0) end as CostPrice,  
                      BeforeAvgPrice=convert(numeric(18,2),BeforeAvgPrice), AllAmount = (select Sum(IsNull(Amount,0)) from CG_StockOrderD(nolock) A  inner join CG_StockOrderM(nolock) B on A.StockOrderNid = B.Nid  where B.CheckFlag = 1 and A.GoodsSkuID = d.GoodsSkuID  group by GoodsSKUID ),
                      s.salername,s.salername2  
                      from CG_StockOrderD(nolock) d  
                      inner join  CG_StockOrderM(nolock) M on m.NID=d.StockOrderNID  
                      inner join B_GoodsSKU(nolock) gs on gs.NID=d.GoodsSKUID  
                      inner join B_Goods(nolock) s on s.NID=gs.GoodsID  
                      left join B_GoodsSKULocation(nolock) tmpb on d.GoodsSKUID = tmpb.GoodsSKUID and tmpb.StoreID = M.StoreID  
                      left join kc_currentstock(nolock) kc on d.GoodsSKUID = kc.GoodsSKUID and kc.StoreID = M.StoreID  
                      left join B_Storelocation Bsl on tmpb.LocationID  = Bsl.NID  
                      where m.NID in (" . $id . ") order by M.nid,gs.SKU";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }


    /**
     * 同步订单保存数据
     * @param $pyOrderID
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public static function syncPyOrders($pyOrderID)
    {
        //获取订单信息
        $orderSql = "SELECT om.billNumber,CASE WHEN om.CHECKfLAG=1 THEN '已审核' ELSE '未审核' END AS billStatus,
                            s.supplierName,om.MakeDate AS orderTime,om.OrderAmount AS totalNumber,om.OrderMoney AS amt
                    FROM [dbo].[CG_StockOrderM](nolock) om 
                    LEFT JOIN B_Supplier s ON s.NID=om.SupplierID
                    WHERE om.nid=" . $pyOrderID;
        $res = Yii::$app->db->createCommand($orderSql)->queryOne();
        //根据订单供应商获取线下采购
        $purchaser = '';
        if($res['supplierName']){
            $supplierModel = OaSupplier::findOne(['supplierName' => $res['supplierName']]);
            //print_r($supplierModel);exit;
            $purchaser = $supplierModel?$supplierModel['purchase']:'';
        }

        $orderModel = new OaSupplierOrder();
        $orderModel->attributes = $res;
        $orderModel->totalNumber = (int)$res['totalNumber'];
        $orderModel->purchaser = $purchaser;
        $orderModel->syncTime = date('Y-m-d H:i:s');
        $res = $orderModel->save();
        if(!$res){
            throw new \Exception('Synchronize order data failed!');
        }
        //保存订单明细
        $detail = self::getPyOrderDetail($pyOrderID);
        foreach ($detail as $v){
            $detailModel = new OaSupplierOrderDetail();
            $detailModel->orderId = $orderModel->id;
            $detailModel->sku = $v['SKU'];
            $detailModel->goodsCode = $v['Goodscode'];
            $detailModel->image = $v['BmpFileName'];
            $detailModel->goodsName = $v['Goodsname'];
            $detailModel->property1 = $v['property1'];
            $detailModel->property2 = $v['property2'];
            $detailModel->property3 = $v['property3'];
            $detailModel->purchaseNumber = (int)$v['amount'];
            $detailModel->purchasePrice = $v['price'];
            $result = $detailModel->save();
            if(!$result){
                throw new \Exception('Synchronize order detail data failed!');
            }
        }
        return true;
    }

}
