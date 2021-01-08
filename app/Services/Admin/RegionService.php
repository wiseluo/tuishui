<?php
/**
 * 文件名 RegionService.php
 * 创建者 赵航
 * 邮箱 zhaoh008@gmail.com
 * 创建时间 2019-07-05 16:43
 * 项目名称 tuishui
 */


namespace App\Services\Admin;


use App\Repositories\RegionRepository;

class RegionService
{
	protected $regionRepository;

	public function __construct(RegionRepository $regionRepository)
	{
		$this->regionRepository = $regionRepository;
	}
	public function getProvince($region_level){
		$province=$this->regionRepository->getProvince($region_level);

		return $this->toCodeName($province);
	}
	public function getCityByParentCode($code){
		return $city=$this->regionRepository->getRegionByParentCode($code);

	}
	public function getDistrictByParentCode($code){
		return $district=$this->regionRepository->getRegionByParentCode($code);
	}
	public function getRegionByCode($code){
		$region=$this->regionRepository->getRegionByCode($code);
		return $this->toCodeName($region);
	}
	protected function toCodeName($region){
		foreach ($region as  $key=>$value){
			$data[$value['region_code']]=$value['region_name'];
		}
		return $data;
	}
}