<?php
/**
 * 文件名 RegionController.php
 * 创建者 赵航
 * 邮箱 zhaoh008@gmail.com
 * 创建时间 2019-07-05 17:19
 * 项目名称 tuishui
 */


namespace App\Http\Controllers\Admin;
use App\Services\Admin\RegionService;
use Illuminate\Http\Request;


class RegionController extends Controller
{
	public function __construct(RegionService $regionService)
	{
		parent::__construct();
		$this->regionService=$regionService;
	}
		public function getRegionByParentCode(Request $request,$code){
			if ($request->ajax()) {
			  $data=$this->regionService->getCityByParentCode($code);
				return response()->json(['code'=>200, 'data'=>$data]);
		}
	}
}