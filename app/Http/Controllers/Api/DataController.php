<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/25
 * Time: 15:31
 */

namespace App\Http\Controllers\Api;

use App\Repositories\DataRepository;

class DataController extends BaseController
{
    public function index(DataRepository $dataRepository)
    {
        return response()->json(['code'=>200, 'data'=>$dataRepository->getTypes()]);
    }
    
    public function father(DataRepository $dataRepository, $id)
    {
        $result = $dataRepository->getTypesByFatherId($id);
        return response()->json(['code'=>200, 'data'=>$result]);
    }
}