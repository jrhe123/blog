<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'cate_id';
    public $timestamps = false;
    /*排除不能填充的字段*/
    protected $guarded = [];

    //静态方法
/*    public static function tree()
    {
        $categorys = Category::all();
        return (new Category())->getTree($categorys,'cate_name','cate_id','cate_pid');
    }*/

    public function tree()
    {
        $categorys = $this->orderBy('cate_order','asc')->get();
        return $this->getTree($categorys,'cate_name','cate_id','cate_pid');
    }


    /*分类构造父子关系*/
    public function getTree($data,$field_name,$field_id='id',$field_pid='pid',$pid=0)
    {
        $arr = array();
        foreach($data as $k=>$v){
            if($v->$field_pid == $pid){
                $data[$k]['_'.$field_name] = $data[$k][$field_name];
                $arr[] = $data[$k];
                foreach($data as $m=>$n){
                    if($n->$field_pid == $v->$field_id){
                        $data[$m]['_'.$field_name] = '├─ '.$data[$m][$field_name];
                        $arr[] = $data[$m];
                    }
                }
            }
        }
        return $arr;
    }

}
