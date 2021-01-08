<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3
 * Time: 9:04
 */

namespace App\Repositories;

use App\Pzxsend;

class PzxsendRepository
{
    public function save($data, ...$args)
    {
        $pzxsend = new Pzxsend($data);
        $pzxsend->save();
        return $pzxsend->id;
    }

    public function update($param, $id)
    {
        $pzxsend = Pzxsend::find($id);
        return (int) $pzxsend->update($param);
    }

    public function delete($id)
    {
        return Pzxsend::destroy($id);
    }

    public function find($id)
    {
        return Pzxsend::find($id);
    }

    public function findByWhere($where)
    {
        return Pzxsend::where($where)->first();
    }
}