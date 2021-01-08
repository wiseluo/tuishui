<?php

namespace App\Repositories;

use Excel;

class ExcelTplRepository
{
    public function export($tplInfo, $data)
    {
//        ['tpl'=> $tpl, 'fileName'=> $fileName, 'type'=> $type] = $tplInfo + ['tpl'=> null, 'fileName'=> 'default', 'type'=> 'export'];
        $arr = $tplInfo + ['tpl'=> null, 'fileName'=> 'default', 'type'=> 'export'];
        $tpl = $arr['tpl'];
        $fileName = $arr['fileName'];
        $type = $arr['type'];

        $obj = Excel::load(static::TPL_PATH . $tpl, function ($excel) use ($data) {
                $excel->sheet(0, function ($sheet) use ($data) {
                    collect($data)->each(function ($item, $key) use ($sheet) {
                        $sheet->cell($key, function($cell) use ($item) {
                            $cell->setValue($item);
                        });
                    });
                });
            })->setFileName($fileName);
        if ($type == 'zip') {
            return $obj->store();
        }
    }
}