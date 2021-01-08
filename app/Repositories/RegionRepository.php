<?php
/**
 * 文件名 RegionRepository.php
 * 创建者 赵航
 * 邮箱 zhaoh008@gmail.com
 * 创建时间 2019-07-05 15:21
 * 项目名称 tuishui
 */


namespace App\Repositories;


use App\Region;
class RegionRepository
{
		public function getProvince($region_level){
			return Region::where('region_level',$region_level)->get();
		}
		public function getRegionByParentCode($code){
			return Region::where('region_parent_id',$code)->get();
		}
		public function getRegionByCode($code){
			return Region::where('region_code',$code)->get();
		}

}