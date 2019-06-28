<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/27
 * Time: 17:18
 * 商品分类
 */
namespace app\models;
use yii\db\ActiveRecord;

class Category extends ActiveRecord{
    public static function tableName()
    {
        return "{{%category}}";
    }
    public function attributeLabels()
    {
        return [
            'title' => '分类',
            'parentid' => '父级id'
        ];
    }
    public function rules()
    {
        return [
            ['parentid', 'validateParentid', 'message' => '上级分类不存在'],
            ['title', 'required', 'message' => '标题名称不能为空'],
            ['createtime', 'safe']
        ];
    }
    public function add($data){
        if($this->load($data) && $this->validate()){
            $this->createtime = time();
            if($this->save()){
                return true;
            }
            return false;
        }
        return false;
    }
    //判断父级id是否存在
    public function validateParentid(){
        if(!empty($this->parentid)){
            $data = self::find()->where('cateid = :id and del = 0',[':id'=>$this->parentid])->one();
            if(empty($data)){
                $this->addError('parentid','父级id不存在');
            }
        }
    }
    //获取树形结构
    public function getTreeList(){
        $data = $this->getData();
        $tree = $this->getTree($data);
        return $this->setPrefix($tree);
    }
    //获取全部数据
    public function getData(){
        return self::find()->where('del = 0')->asArray()->all();
    }
    //父级数据遍历
    public function getTree($data,$pid = 0){
        $tree = [];
        foreach ($data as $value){
            if($value['parentid'] == $pid){
                $tree[] = $value;
                $tree = array_merge($tree,$this->getTree($data,$value['cateid']));
            }
        }
        return $tree;
    }
    //数据拼接前缀
    public function setPrefix($data, $p = '|------'){
        $tree = [];
        $num = 1;
        $prefix = [0 => 1];
        while($val = current($data)) {
            $key = key($data);
            if ($key > 0) {
                if ($data[$key - 1]['parentid'] != $val['parentid']) {
                    $num ++;
                }
            }
            if (array_key_exists($val['parentid'], $prefix)) {
                $num = $prefix[$val['parentid']];
            }
            $val['title'] = str_repeat($p, $num).$val['title'];
            $prefix[$val['parentid']] = $num;
            $tree[] = $val;
            next($data);
        }
        return $tree;
    }
    public function getOptions()
    {
        $data = $this->getData();
        $tree = $this->getTree($data);
        $tree = $this->setPrefix($tree);
        $options = ['添加顶级分类'];
        foreach($tree as $cate) {
            $options[$cate['cateid']] = $cate['title'];
        }
        return $options;
    }

}