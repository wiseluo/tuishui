<?php

namespace App\Repositories;

class ZjportRepository
{
    public function ScmOrder($ordObj) {

        foreach ($ordObj->drawerProducts as $key=>$drawerProduct) {
            $productDetail[$key]['scmordcode']=$ordObj->id; //订单号
            $productDetail[$key]['hscode']=$drawerProduct->product->hscode; //Hscode
            $productDetail[$key]['gcodedesc']=$drawerProduct->product->name;    //产品名称
            $productDetail[$key]['enamedesc']=$drawerProduct->product->en_name;
            $productDetail[$key]['gattr1']=$drawerProduct->product->standard;   //规格型号
            $productDetail[$key]['brand']=!empty($drawerProduct->brand_id)?$drawerProduct->product->brand->name:'无';    //品牌类型
            $productDetail[$key]['qtc']=$drawerProduct->number; //产品数量
            $productDetail[$key]['qtcunit']=$drawerProduct->unit;   //单位
            $productDetail[$key]['fcode']=$ordObj->currencyData->name;  //报关币种
            $productDetail[$key]['upric']=$drawerProduct->single_price; //单价
            $productDetail[$key]['fcy']=$drawerProduct->value;  //开票金额
            $productDetail[$key]['qty']=$drawerProduct->default_num;    //法定数量
            $productDetail[$key]['qtyunit']=$drawerProduct->default_unit;   //法定单位
        }
        $drawerData = [
            'scmordcode'=>$ordObj->ordnumber,  //业务编号
            'shipmode'=>$ordObj->transPortData->key,   //运输方式
            'loadingportdesc'=>'',
            'dischargeportdesc'=>$ordObj->unloadingport->code_pzx,
            'consigndate'=>'',
            'vessel'=>'',
            'voyage'=>'',
            'containerno'=>'',
            'bolcodelist'=>'',
            'scmorderg-loop'=>$productDetail
        ];

        return [
            'data'=>[
                'root'=>[
                    'head'=>[
                        'source'=>$ordObj->company->name, //经营单位
                        'target'=>'EDI.Scmorder.Imp',
                        'docType'=>'出口订单接口',
                    ],
                    'body'=>[
                        'scmorder'=>[
                            'scmordcode'=>$ordObj->ordnumber,  //订单号
                            'ccodetrust'=>$ordObj->customer->id,    //客户ID
                            'ccodetrustname'=>$ordObj->customer->name,  //客户名称
                            'ccodetrustncode'=>$ordObj->customer->address,  //地址
                            'ccode'=>$ordObj->receive->id,   //贸易商ID
                            'ccname'=>$ordObj->receive->name,    //贸易商
                            'salordcodex'=>$ordObj->ordnumber,  //合同号
                            'etd'=>$ordObj->sailing_at, //开船日期
                            'customport'=>$ordObj->clearancePortData->key, //报关口岸
                            'ncode'=>$ordObj->country->country_co,  //抵运国
                            'consignee'=>'',
                            'ccodenamedesc'=>'',
                            'fcode'=>$ordObj->currencyData->key,   //报关币种
                            'priceterm'=>$ordObj->priceClauseData->key,    //价格条款
                            'cono'=>'',
                            'scmorderext-loop'=>$drawerData,
                            'scmordersettlement-loop'=>[
                                'scmordersettlement'=>[
                                    'scmordcode'=>$ordObj->ordnumber,
                                    'abgoods'=>'10',
                                    'paymode'=>'',
                                    'payrate'=>'',
                                    'fcode'=>'',
                                    'fcy'=>'',
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function ExpCustoms($order)
    {
        foreach ($order->drawerProducts as $key=>$drawerProduct) {
            $productDetail[$key]['expclcode']=$order->id; //预录入编号
            $productDetail[$key]['itemno']=$drawerProduct->product->hscode; //款号
            $productDetail[$key]['hscode']=$drawerProduct->product->hscode;    //Hscode
            $productDetail[$key]['cnamedesc']=$drawerProduct->product->name;    //名称
            $productDetail[$key]['gattr1']=$drawerProduct->product->standard;   //规格型号
            $productDetail[$key]['brand']=isset($drawerProduct->brand->name)?$drawerProduct->product->brand->name:'无';    //品牌类型
            $productDetail[$key]['qty']=(string)round($drawerProduct->pivot->default_num,2); //法定数量
            $defaultUnitArr = unitFunc($drawerProduct->pivot->default_unit_id);
            $productDetail[$key]['qtyunit']=$defaultUnitArr?$defaultUnitArr['key']:'';   //法定单位
            $productDetail[$key]['qtyupric']=(string)round($order->total_value/$drawerProduct->pivot->default_num, 2);  // 报关金额/法定数量
            $productDetail[$key]['qtc']=$drawerProduct->pivot->number; //产品数量
            $measureUnitArr = unitFunc($drawerProduct->pivot->measure_unit);
            $productDetail[$key]['qtcunit']=$measureUnitArr?$measureUnitArr['key']:'';  //发货单位
            $productDetail[$key]['upric']=(string)round($drawerProduct->pivot->single_price, 2);    //单价
            $productDetail[$key]['fcode']=$order->currencyData->key;   //报关币种
            $productDetail[$key]['fcy']=(string)round($drawerProduct->pivot->total_price,2);   //报关金额
            $productDetail[$key]['exempty']=countriesFunc($drawerProduct->pivot->origin_country_id)->country_co;   //原产地
            $productDetail[$key]['destinationcountry']=countriesFunc($drawerProduct->pivot->destination_country_id)->country_co;   //最终目的国
            $productDetail[$key]['supplyofgoods']=districtsFunc($drawerProduct->pivot->domestic_source_id)->district_code;   //境内货源地
            $productDetail[$key]['dutymode']='1';   //照章征税
        }

        return [
            'data'=>[
                'root'=>[
                    'head'=>[
                        'source'=>$order->company->name, //经营单位
                        'target'=>'EDI.Expcustoms.Imp',
                        'docType'=>'报关申报接口',
                    ],
                    'body'=>[
                        'expcustoms'=>[
                            'expclcode'=>$order->id,    //业务编号
                            'tradeco'=>'',
                            'tradename'=>$order->company->name,  //经营单位
                            'consignee'=>$order->receive->name,   //贸易商
                            'ownercode'=>'',
                            'ownername'=>$order->company->name,  //经营单位
                            'customport'=>'2921',   //出口口岸
                            'expdate'=>$order->sailing_at,   //开船日期
                            'appldate'=>$order->customs_at,  //报关日期
                            'recordnum'=>'',
                            'shipmode'=>$order->transPortData->key,  //运输方式
                            'vessel'=>'',
                            'bolcodelist'=>'',
                            'trademode'=>'0110',    //一般贸易
                            'taxnature'=>'101',     //一般征税
                            'liccode'=>'',
                            'ordcodelist'=>$order->ordnumber,   //合同号
                            'ncode'=>$order->country->country_co,    //最终目的国
                            'tarnationdesc'=>$order->country->country_co,    //最终目的国
                            'dischargeport'=>$order->unloadingport->code_pzx,    //抵运港
                            'packtype'=>$order->orderPackage->key,   //包装方式
                            'qtx'=>$order->total_num,    //产品数量
                            'grossweight'=>(string)round($order->total_weight,2),    //总毛重
                            'netweight'=>(string)round($order->total_net_weight, 2), //总净重
                            'volume'=>(string)round($order->total_volume, 2),    //总体积
                            'pricetermdesc'=>$order->priceClauseData->key, //价格条款
                            'freightrcy2'=>'',
                            'insurancercy2'=>'',
                            'incidentalscy'=>'',
                            'markdesc'=>'',
                            'relconfirm'=>'',
                            'upricconfirm'=>'',
                            'allowpayfee'=>'',
                            'scmordcode'=>$order->ordnumber, //合同号
                            'expcustomsgo-loop'=>[
                                'expcustomsgo'=>$productDetail
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function ScmOrderext($ordObj, $money, $remitStatus)
    {
        return [
            'data'=>[
                'root'=>[
                    'head'=>[
                        'source'=>$ordObj->company->name,
                        'target'=>'EDI.Recpay.Imp',
                        'docType'=>'订单收汇接口',
                    ],
                    'body'=>[
                        'recpay'=>[
                            'scmordcode'=>$ordObj->ordnumber,
                            'fcode'=>$ordObj->currencyData->key,
                            'incomefcy'=>$money,
                            'getallfcy'=>$remitStatus,
                        ]
                    ]
                ]
            ]
        ];
    }

    public function purinvoice($invoiceObj)
    {
        $total_natfcy = 0;
        $total_untaxed_amount = 0;
        $total_fcy = 0;
        foreach ($invoiceObj->drawerProductOrders as $key=>$drawerProductOrder) {

            $productDetail[$key]['purinvcode']=$invoiceObj->number;
            $productDetail[$key]['hscode']=$drawerProductOrder->drawerProduct->product->hscode;
            $productDetail[$key]['gcodedesc']=$drawerProductOrder->drawerProduct->product->name;
            $productDetail[$key]['gattr1']=$drawerProductOrder->drawerProduct->product->standard;
            $productDetail[$key]['qtc']=$drawerProductOrder->number;
            $measureUnitArr = unitFunc($drawerProductOrder->measure_unit);
            $productDetail[$key]['qtcunit']=$measureUnitArr?$measureUnitArr['key']:'';
            $productDetail[$key]['upric']=(string)round($drawerProductOrder->pivot->single_price*(1+$drawerProductOrder->pivot->tax_rate/100), 2);
            $productDetail[$key]['natupric']=$drawerProductOrder->pivot->single_price;
            $productDetail[$key]['fcy']=(string)$drawerProductOrder->pivot->amount+round($drawerProductOrder->pivot->product_untaxed_amount*$drawerProductOrder->pivot->tax_rate/100,2);
            $productDetail[$key]['natfcy']=$drawerProductOrder->pivot->amount;
            $productDetail[$key]['addtaxrate']=$drawerProductOrder->pivot->tax_rate/100;
            $productDetail[$key]['addtaxscy']=(string)round($drawerProductOrder->pivot->product_untaxed_amount*$drawerProductOrder->pivot->tax_rate/100,2);
            $productDetail[$key]['backtaxrate']=$drawerProductOrder->drawerProduct->product->tax_refund_rate;
            $productDetail[$key]['backtaxscy']=$drawerProductOrder->pivot->refund_tax_amount;

            $total_natfcy+=$drawerProductOrder->pivot->amount;
            $total_untaxed_amount+=$drawerProductOrder->pivot->product_untaxed_amount;
            $total_fcy+=round($drawerProductOrder->pivot->product_untaxed_amount*$drawerProductOrder->pivot->tax_rate/100,2);
        }

        return [
            'data'=>[
                'root'=>[
                    'head'=>[
                        'source'=>$invoiceObj->order->company->name,
                        'target'=>'EDI.Purinvoice.Imp',
                        'docType'=>'进项发票接口',
                    ],
                    'body'=>[
                        'purinvoice'=>[
                            'purinvcode'=>$invoiceObj->number,
                            'scmordcode'=>$invoiceObj->order->ordnumber,
                            'ccode'=>$invoiceObj->order->customer_id,
                            'odate'=>$invoiceObj->received_at,
                            'invtype'=>'0101',
                            'fcy'=>(string)$total_fcy,
                            'natfcy'=>(string)$total_natfcy,
                            'addtaxscy'=>(string)$total_untaxed_amount,
                            'phyinvno'=>$invoiceObj->number,
                            'purinvoiceg-loop'=>[
                                'purinvoiceg'=>$productDetail
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
    
    public function scmordtaxback($settleObj)
    {
        return [
            'data'=>[
                'root'=>[
                    'head'=>[
                        'source'=>$settleObj->order->company->name,
                        'target'=>'EDI.Declarationmain.Imp',
                        'docType'=>'订单退税接口',
                    ],
                    'body'=>[
                        'declarationmain'=>[
                           'scmordcode'=>$settleObj->order->ordnumber,
                           'expclcode'=>'',
                           'phyinvno'=>'',
                           'odate'=>$settleObj->settle_at,
                           'backrmb'=>$settleObj->payable_refund_tax_sum,
                        ]
                    ]
                ]
            ]
        ];
    }
}