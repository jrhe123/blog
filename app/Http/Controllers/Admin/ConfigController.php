<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Config;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends CommonController
{
    //get.admin/config
    /*全部配置项列表*/
    public function index()
    {
        $data = Config::orderBy('conf_order','asc')->get();
        foreach($data as $k=>$v){
            switch($v->field_type){
                case 'input':
                    $data[$k]->_html = '<input type="text" class="lg" name="conf_content[]" value="'.$v->conf_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea type="text" class="lg" name="conf_content[]">'.$v->conf_content.'</textarea>';
                    break;
                case 'radio':
                    //1|开启，0|关闭
                    $arr = explode('，',$v->field_value);
                    $str = '';
                    foreach($arr as $m=>$n){
                        $r = explode('|',$n);
                        $c = $v->conf_content == $r[0]? ' checked ':'';
                        $str .= '<input type = "radio" name="conf_content[]"'.$c.'value="'.$r[0].'">'.$r[1].'　';
                    }
                    $data[$k]->_html = $str;
                    break;
            }
        }
        return view('admin.config.index',compact('data'));
    }

    /*快速修改配置项*/
    public function changecontent()
    {
        $input = Input::all();
        foreach($input['conf_id'] as $k=>$v){
            Config::where('conf_id',$v)->update(['conf_content'=>$input['conf_content'][$k]]);
        }
        $this->putFile();
        return back()->with('errors','配置项更新成功！');
    }

    /*数据库配置项，写入config文件*/
    public function putFile()
    {
        $config = Config::pluck('conf_content','conf_name')->all();
        /*数组转字符串*/
        $str = '<?php return '.var_export($config,true).';';
        $path = base_path().'\config\web.php';
        file_put_contents($path,$str);

    }

    public function changeOrder()
    {
        /*Ajax post 接收表单*/
        $input = Input::all();
        $config = Config::find($input['conf_id']);
        $config->conf_order = $input['conf_order'];
        $result = $config->update();
        if($result){
            $data = [
                'status'=>1,
                'msg'=>'配置项排序更新成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'配置项排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //get.admin/config/create
    /*添加配置项*/
    public function create()
    {
        return view('admin.config.add');

    }

    //post.admin/config
    /*添加配置项，处理提交表单*/
    public function store()
    {
        $input = Input::except('_token');
        //定义验证规则
        $rules = [
            'conf_name' => 'required',
            'conf_title' => 'required',
        ];
        //定义错误返回信息
        $message = [
            'conf_name.required' => '配置项名称不能为空！',
            'conf_title.required' => '配置项标题不能为空！',
        ];
        //验证模型
        $validator = Validator::make($input, $rules, $message);

        if ($validator->passes()) {
            /*验证通过，处理数据库*/
            $result = Config::create($input);
            if ($result) {
                return redirect('admin/config');
            } else {
                return back()->with('errors', '配置项填充失败，请稍后重试！');
            }
        } else {
            /*输出错误信息*/
            return back()->withErrors($validator);
        }

    }

    //get.admin/config/{config}/edit
    /*编辑配置项*/
    public function edit($conf_id)
    {
        $field = Config::find($conf_id);
        return view('admin.config.edit',compact('field'));
    }

    //put.admin/config/{config}
    /*更新配置项*/
    public function update($conf_id)
    {
        $input = Input::except('_token','_method');
        $result = Config::where('conf_id',$conf_id)->update($input);
        if($result){
            $this->putFile();
            return redirect('admin/config');
        }else{
            return back()->with('errors','配置项信息更新失败，请稍后重试！');
        }
    }

    //get.admin/config/{config}
    public function show()
    {

    }

    //delete.admin/config/{config}
    /*删除配置项*/
    public function destroy($conf_id)
    {
        $result = Config::where('conf_id',$conf_id)->delete();
        if($result){
            $this->putFile();
            $data = [
                'status'=>1,
                'msg'=>'配置项删除成功！',
            ];
        }else{
            $data = [
                'status'=>0,
                'msg'=>'配置项删除失败，请稍后重试！',
            ];
        }
        return $data;
    }
}
