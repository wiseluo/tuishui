<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3
 * Time: 9:04
 */

namespace App\Repositories;

use App\Country;
use App\Port;

class PortRepository
{
    public function getCountryPort($country)
    {
        return array_column(Port::where('port_count', $country)->get()->toArray(), 'port_c_cod', 'id');
    }

    public function getChinaPort($where=[])
    {
        return array_column(Port::where('port_count', 142)->where($where)->get()->toArray(), 'port_c_cod', 'id');
    }
    
    public function getOtherPort()
    {
        return array_column(Port::where('port_count','<>', 142)->get()->toArray(), 'port_c_cod', 'id');
    }

    public function getPortByCountryco($country_co, $where=[])
    {
        return array_column(Port::where('port_count', $country_co)->where($where)->get()->toArray(), 'port_c_cod', 'id');
    }

    public function getPortByCountryId($country_id, $where=[])
    {
        $country = Country::find($country_id);
        return array_column(Port::where('port_count', $country->country_co)->where($where)->get()->toArray(), 'port_c_cod', 'id');
    }

    public function portListWithPage($param)
    {
        $where = [];
        if(isset($param['country_id']) && $param['country_id'] != '') {
            $country = Country::find($param['country_id']);
            $where[] = ['port_count', '=', $country->country_co];
        }
        if(isset($param['keyword']) && $param['keyword'] != '') {
            $where[] = ['port_c_cod', 'LIKE', '%'. $param['keyword'] .'%'];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return Port::where($where)
            ->paginate(10)
            ->toArray();
    }
    
    public function findWhere($where)
    {
        return Port::where($where)->first();
    }
}