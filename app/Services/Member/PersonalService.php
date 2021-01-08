<?php

namespace App\Services\Member;

use App\User;
use Curl\Curl;
use App\Personal;
use Illuminate\Support\Facades\Cache;
use App\Repositories\PersonalRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\DrawerRepository;

class PersonalService
{
    protected $personalRepository;
    
    public function __construct(PersonalRepository $personalRepository, CustomerRepository $customerRepository, DrawerRepository $drawerRepository)
    {
        $this->personalRepository = $personalRepository;
        $this->customerRepository = $customerRepository;
        $this->drawerRepository = $drawerRepository;
    }

    public function read($id)
    {
        return $this->personalRepository->find($id);
    }

    public function update($param, $id)
    {
        return $this->personalRepository->update($param, $id);
    }

    public function approve($param, $id)
    {
        $personal = $this->personalRepository->find($id);
        if($param['status'] == 3){ //审核通过，建立客户与开票人资料
            //新增客户
            $cus_data = [];
            $cus_data['name'] = $personal['drawer_name'];
            $cus_data['usc_code'] = $personal['tax_id'];
            $cus_data['linkman'] = $personal['legal_person'];
            $cus_data['telephone'] = $personal['phone'];
            $cus_data['status'] = 1;
            $cus_data['u_name'] = 130274;
            $cus_data['salesman'] = '何紫颜';
            $cus_data['custype'] = 2;
            $cus_data['legal_name'] = $personal['legal_person'];
            $cus_data['picture_lic'] = $personal['pic_invoice'] .'|'. $personal['pic_verification'] .'|'. $personal['pic_register'];
            $cus_data['euid'] = $personal['created_user_id'];
            $cus_data['ucenterid'] = $personal['ucenterid'];
            $customer_id = $this->customerRepository->save($cus_data);
            //新增开票人
            if($customer_id){
                $drawer_data['customer_id'] = $customer_id;
                $drawer_data['company'] = $personal['drawer_name'];
                $drawer_data['telephone'] = $personal['phone'];
                $drawer_data['tax_id'] = $personal['tax_id'];
                $drawer_data['source'] = $personal['original_addr'];
                $drawer_data['pic_register'] = $personal['pic_register'];
                $drawer_data['pic_verification'] = $personal['pic_verification'];
                $drawer_data['pic_sales_invoice'] = $personal['pic_invoice'];
                $drawer_data['status'] = 1;
                $drawer_data['tax_at'] = $personal['tax_at'];
                $drawer_data['euid'] = $personal['created_user_id'];
                $drawer_id = $this->drawerRepository->save($drawer_data);
            }
        }
        return $this->personalRepository->update($param, $id);
    }

    public function destroy($id)
    {
        return $this->personalRepository->destroy($id);
    }

    public function resetPasswordService($userid, $personalid)
    {
        $personal = $this->personalRepository->select($personalid);
        $personalUser = User::where('id', $personal->created_user_id)->first();
        $loginUser = User::where('id', $userid)->first();
        if($personalUser == null){
            return response()->json(['code'=>400, 'msg'=> '账号不存在']);
        }

        //去用户中心重置密码，本地密码不必改
        $post_data = array(
            'token'=> $loginUser->usercenter_token,
            'username'=> $personalUser->username,
            'newpassword'=> md5('888888'),
            'action'=> 'resetOriginalPassword'
        );
        // dd($post_data);
        $curl = new Curl;
        $res_rest = $curl->post(config('app.user_center_url'). '/api/usercenter/api/RestPassword.php', $post_data)->response;
        $result = json_decode($res_rest);
        // dd($result);
        return response()->json($result);
    }
}
