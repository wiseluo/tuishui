<?php
namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Pagination
{
    //生成给前端的分页
    protected function paginateRender(Builder $builder, $pageSize = 10)
    {
        $lengthAwarePaginator = $builder->orderBy('id', 'DESC')->paginate($pageSize);

    	$paginate = $lengthAwarePaginator->toArray();

        $current_page = $paginate['current_page'];

        $per_page = $paginate['per_page'];

        //dd($paginate);

        $prev = $current_page > 1 ? 'data-page="' . ($current_page - 1) . '"': 'class="disabled"';
        
        $spans = [
           "<span data-page='1'>首页</span><span {$prev}>上一页</span>" 
        ];
        for ($i= ($current_page - 5 > 0 ? $current_page - 5 : 1); $i <= $paginate['last_page'] && ($i < $current_page); $i++) {
            $tag = $i == $current_page ? 'class="me"' : "data-page='{$i}'";
            $spans[] = "<span {$tag}>{$i}</span>";
        }

        for ($i=$current_page; $i <= $paginate['last_page'] && ($i < $current_page + 5); $i++) { 
            $tag = $i == $current_page ? 'class="me"' : "data-page='{$i}'";
            $spans[] = "<span {$tag}>{$i}</span>";
        }
        $spans[] = '<span ' . ($current_page == $paginate['last_page'] ? 'disable' : 'data-page="' . ($current_page + 1))  . '">下一页</span>';
        
        $spans = implode('', $spans);
        
        $options = implode('', array_map(function ($value) use ($per_page) {
            return "<option value='{$value}' " . ($per_page == $value ? 'selected' : '') . ">{$value} 条/页</option>";
        }, [10, 20]));

        $page = <<<PAGE
            <select>
            {$options}
            </select>
            {$spans}
            第 
            <input type="text" 
                class="page_input" 
                name="custompage" 
                size="3" 
                data-pagenum="{$paginate['last_page']}">
             页 
            共 {$paginate['total']} 条
PAGE;
        $i = 1;
        return [
            'data' => array_map(function ($value) use (&$i, $current_page, $per_page){
                $value['serial'] = ($current_page - 1) * $per_page + ($i++);
                return $value;
            }, array_get($paginate, 'data')),
            'page' => $page,
        ];
    }
}
