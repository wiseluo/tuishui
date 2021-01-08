<?php

namespace App\Services\Member;

use App\Repositories\PurchaseRepository;
use App\Repositories\PurchaseproductRepository;
use DB;

class PurchaseService
{
    protected $purchaseRepository;
    protected $purchaseproductRepository;

    public function __construct(PurchaseRepository $purchaseRepository, PurchaseproductRepository $purchaseproductRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->purchaseproductRepository = $purchaseproductRepository;
    }

    public function savePurchaseFromErp($params)
    {
        DB::beginTransaction();
        $this->purchaseproductRepository->forceDeleteByPurchaseId($params['purchase']['id']);
        $this->purchaseRepository->forceDeleteById($params['purchase']['id']);
        //$params['purchase']['opinion'] = $params['purchase']['tax_opinion']; //退税审核意见
        $pur_res = $this->purchaseRepository->save($params['purchase']);
        $all_is_ok = 0;
        if($pur_res){
            foreach($params['purchase_products'] as $k => $v){
                $pur_pro_res = $this->purchaseproductRepository->save($v);
                if(!$pur_pro_res){
                    $all_is_ok == 1;
                }
            }
        }else{
            $all_is_ok == 1;
        }
        if($all_is_ok == 1){
            DB::rollback();
            return 0;
        }else{
            DB::commit();
            return 1;
        }
    }

    public function getPurchaseAndProductList($id)
    {
        $purchase = $this->purchaseRepository->findOrFail($id);
        $productList = $this->purchaseproductRepository->productListByPurchaseId($id);
        return ['purchase'=> $purchase, 'productList'=> $productList];
    }

    public function update($params, $id)
    {
        return $this->purchaseRepository->update($params, $id);
    }


}
