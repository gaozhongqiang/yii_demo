<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/6/27
 * Time: 17:18
 * 商品分类
 */
namespace app\models;
use yii\data\Pagination;
use yii\db\ActiveRecord;

class Category extends ActiveRecord{
    public static function tableName()
    {
        return "{{%category}}";
    }
    public function attributeLabels()
    {
        return [
            'title' => '分类名称',
            'parentid' => '上级分类'
        ];
    }
    public function rules()
    {
        return [
            ['parentid', 'required', 'message' => '上级分类不存在','except' => 'rename'],
            ['title', 'required', 'message' => '标题名称不能为空'],
            ['createtime', 'safe']
        ];
    }
    public function add($data){
        $data['Category']['createtime'] = time();
        if($this->load($data) && $this->save()){
            return true;
        }
        return false;
    }
    //判断父级id是否存在
    public function validateParentid(){
        if(!empty($this->parentid)){
            $data = self::find()->where('cateid = :id',[':id'=>$this->parentid])->one();
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
        return self::find()->asArray()->all();
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
    public static function getMenu(){
        $top = self::find()->where("parentid = :pid",[':pid' => 0])->asArray()->all();
        $data = [];
        foreach ($top as $key => $cate){
            $cate['children'] = self::find()->where('parentid = :pid',[':pid'=>$cate['cateid']])->asArray()->all();
            $data[$key] = $cate;
        }
        return $data;
    }
    /**
     * 查询顶级分类
     * */
    public function getPrimaryCate(){
        $data = self::find()->where('parentid = :pid',[':pid' => 0]);
        if(empty($data)){
            return [];
        }
        $pager = new Pagination(['totalCount' => $data->count(),'pageSize' => 10]);
        $data = $data->orderBy('createtime desc')->offset($pager->offset)->limit($pager->limit)->all();
        if(empty($data)){
            return [];
        }
        $primary = [];
        foreach ($data as $cate){
            $primary[] = [
              'id' => $cate->cateid,
              'text' => $cate->title,
              'children' => $this->getChild($cate->cateid)
            ];
        }
        return ['data' => $primary,'pager' => $pager];
    }

    /**
     * @param $cateid
     */
    public function getchild($cateid){
        $data = self::find()->where('parentid = :pid',[':pid' => $cateid])->all();
        if(empty($data)){
            return [];
        }
        $children = [];
        foreach ($data as $child){
            $children[] = [
                'id' => $child->cateid,
                'text' => $child->title,
                'children' => $this->getchild($child->cateid)
            ];
        }
        return $children;
    }
}