<?php
/**
 * 文件名 Region.php
 * 创建者 赵航
 * 邮箱 zhaoh008@gmail.com
 * 创建时间 2019-07-05 15:18
 * 项目名称 tuishui
 */


namespace App;


class Region extends BaseModel
{
	protected $fillable = [
		"region_id",
		"region_name",
		'region_code',
		'region_parent_id',
		'region_level'
	];
}