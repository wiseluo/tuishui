<?php

namespace App;

use App\Http\Traits\Status;

class Order extends BaseModel
{
    use Status;

    protected $fillable = [
        'logistics',
        'bnote_num',
        'customer_id',
        'ordnumber',
        'business',
        'company_id',
        'clearance_port',
        'shipment_port',
        'declare_mode',
        'currency',
        'package',
        'price_clause',
        'loading_mode',
        'package_number',
        'order_package',
        'status',
        'shipping_at',
        'clearance_mode',
        'is_pay_special',
        'is_special',
        'trade_country',
        'aim_country',
        'unloading_port',
        'customs_number',
        'export_date',
        'broker_number',
        'broker_name',
        'opinion',
        'ship_name',
        'transport',
        'district_code',
        'approved_at',
        'packing_at',
        'customs_at',
        'sailing_at',
        'trader_id',
        'white_card',
        'plate_number',
        'operator',
        'box_number',
        'box_type',
        'tdnumber',
        'total_num',
        'total_packnum',
        'total_value',
        'total_value_invoice',
        'total_weight',
        'total_net_weight',
        'total_volume',
        'cid',
        'exchange_method',
        'lc_no',
        'enter_price_method',
        'other_packages_type',
        'storage_address',
        'look_goods_task',
        'note_number',
        'shipment_notes',
        'attach_upload',
        'is_remit',
        'link_id',
        'receive_id',
        'deposit_id',
        'from_type',
        'transport_fee',
        'insurance_fee',
        'miscellaneous_fee',
        'invoice_complete',
        'shipping_mark',
    ];

    protected $appends = [
        'status_str',
        'clearance_status',
        'receipted_amount',
        'invoice_complete_str'
    ];

    //订单状态
    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
        5=>'已报关',
        6=>'已结案',
    ];

    //开票状态
    const INVOICE_COMPLETE = [
        0=>'未开票完',
        1=>'已开票完',
        2=>'已申报',
        3=>'已退税',
    ];

    public function drawerProducts()
    {
        return $this->belongsToMany(DrawerProduct::class)
                ->withPivot([
                    'id',
                    'order_id',
                    'drawer_product_id',
                    'standard',
                    'company',
                    'number',
                    'unit',
                    'single_price',
                    'default_num',
                    'default_unit_id',
                    'default_unit',
                    'value',
                    'volume',
                    'net_weight',
                    'total_weight',
                    'measure_unit',
                    'measure_unit_cn',
                    'measure_unit_en',
                    'pack_number',
                    'total_price',
                    'merge',
                    'domestic_source_id',
                    'domestic_source',
                    'production_place',
                    'origin_country_id',
                    'origin_country',
                    'destination_country_id',
                    'destination_country',
                    'goods_attribute',
                    'ciq_code',
                    'species',
                    'surface_material',
                    'section_number',
                    'enjoy_offer',
                    'exchange_cost',
                    'tax_refund_rate',
                    'tax_rate',
                    'invoice_quantity',
                    'invoice_amount',
                ])->withTimestamps();
    }

    public function currencyData()
    {
        return $this->belongsTo(Data::class, 'currency');
    }

    public function orderPackageData()
    {
        return $this->belongsTo(Data::class, 'order_package');
    }
    
    public function orderPackage()
    {
        return $this->belongsTo(Data::class, 'package');
    }

    // public function shipmentPortData()
    // {
    //     return $this->belongsTo(Data::class, 'shipment_port');
    // }
    
    public function businessData()
    {
        return $this->belongsTo(Data::class, 'business');
    }

    public function transPortData()
    {
        return $this->belongsTo(Data::class, 'transport');
    }

    public function clearancePortData()
    {
        return $this->belongsTo(Data::class, 'clearance_port');
    }

    public function priceClauseData()
    {
        return $this->belongsTo(Data::class, 'price_clause');
    }

    public function declareModeData()
    {
        return $this->belongsTo(Data::class, 'declare_mode');
    }
    
    //报关状态
    public function getClearanceStatusAttribute()
    {
        return implode('-', [
            isset($this->shipmentport->port_c_cod) ? $this->shipmentport->port_c_cod : '',
            isset($this->unloadingport->port_c_cod) ? $this->unloadingport->port_c_cod : '',
            isset($this->country->country_na) ? $this->country->country_na : '',
        ]);
    }

    //发票状态
    public function getInvoiceCompleteStrAttribute()
    {
        return array_get(static::INVOICE_COMPLETE, $this->getAttribute('invoice_complete'), '未知');
    }

    //收汇关联订单金额之和
    public function getReceiptedAmountAttribute()
    {
        return $this->receipt()->sum('order_receipt.money');
    }

    public function tradecountry()
    {
        return $this->belongsTo(Country::class, 'trade_country', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'aim_country', 'id');
    }

    //离境口岸
    public function shipmentport()
    {
        return $this->belongsTo(Port::class, 'shipment_port');
    }

    public function unloadingport()
    {
        return $this->belongsTo(Port::class, 'unloading_port');
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }
    
    public function settlement()
    {
        return $this->hasOne(Settlement::class);
    }

    public function pay()
    {
        return $this->belongsToMany(Pay::class)->withPivot(['money'])->withTimestamps();
    }
    
    public function clearance()
    {
        return $this->belongsTo(Clearance::class, 'id', 'order_id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class);
    }

    public function receive()
    {
        return $this->belongsTo(Receive::class);
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    // public function district()
    // {
    //     return $this->belongsTo(District::class, 'district_code', 'district_code');
    // }

    //经营单位
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //运费
    public function transport()
    {
        return $this->belongsToMany(Transport::class)
            ->withPivot([
                'money',
            ])->withTimestamps();
    }

    public function receipt()
    {
        return $this->belongsToMany(Receipt::class)->withPivot(['money'])->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id');
    }

    public function pzxsend()
    {
        return $this->hasOne(Pzxsend::class, 'order_id', 'id');
    }
}
