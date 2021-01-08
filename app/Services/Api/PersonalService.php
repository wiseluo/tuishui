<?php

namespace App\Services\Api;

use App\Repositories\PersonalRepository;
use App\Repositories\DrawerRepository;

class PersonalService
{
    public function __construct(PersonalRepository $personalRepository, DrawerRepository $drawerRepository)
    {
        $this->personalRepository = $personalRepository;
        $this->drawerRepository = $drawerRepository;
    }

    public function index($param)
    {
        $where['created_user_id'] = $param['created_user_id'];
        $where['cid'] = $param['cid'];
        $personal = $this->personalRepository->getOnePersonalByWhere($where);
        if($personal){
            $drawer = $this->drawerRepository->getOneDrawerByWhere($where);
            $data = $personal;
            if($drawer){
                $data['drawer_status'] = $drawer->status;
            }else{
                $data['drawer_status'] = 0;
            }
        }else{ //未找到该公司下该账号的客户信息，查找其他公司下该账号客户信息
            $where2['created_user_id'] = $param['created_user_id'];
            $personal2 = $this->personalRepository->getOnePersonalByWhere($where2);
            if($personal2){
                $data = $personal2;
                $data['status'] = 0;
                $data['drawer_status'] = 0;
            }else{ //所有公司下都未找到该账号客户信息
                $data = [];
            }
        }
        
        return $data;
    }

    public function read($id)
    {
        return $this->personalRepository->find($id);
    }

    public function save($data, $param)
    {
        $where['created_user_id'] = $param['created_user_id'];
        $where['cid'] = $param['cid'];
        $personal = $this->personalRepository->getOnePersonalByWhere($where);
        if($personal){
            $data['status'] = 2;
            $data['ucenterid'] = $param['ucenterid'];
            return $this->personalRepository->update($data, $personal->id);
        }else{
            //新增个人信息
            $data['status'] = 2;
            $data['ucenterid'] = $param['ucenterid'];
            $personal_id = $this->personalRepository->save($data);
            return $personal_id;
        }
        
    }

    public function update($param, $id)
    {
        return $this->personalRepository->update($param, $id);
    }

    public function destroy($id)
    {
        return $this->personalRepository->destroy($id);
    }

}
