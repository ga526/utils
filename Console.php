<?php
/**
 * console::log  模拟前端console.log 打印输出内容
 *  @author Elijah
 *  @create_time 21-3-10 下午3:08
 */

class Console
{
    public static function log($data){
        echo self::_fetch($data);
    }

    public static function _fetch($data){
        if(is_string($data)){
            return self::fetchString($data);
        }elseif(is_array($data)){
            return static::fetchArray($data);
        }elseif(is_numeric($data)){
            return $data;
        }elseif (is_null($data)){
            return "null";
        }elseif(is_object($data)){
            return static::fetchObject($data);
        }
    }

    public static function fetchObject($data){
        if($data instanceof \JsonSerializable){
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }elseif($data instanceof \Jsonable){
            return $data ->toJson();
        }elseif($data instanceof  \ArrayAccess || $data instanceof \Arrayable){
            $res = [];
            foreach ($data as $v){
                $res[] = static::_fetch($v);
            }
            return "[".implode(",",$res)."]";
        }else{
            return static::_fetch(get_object_vars($data));
        }

    }

    public static function fetchString($str){
        return $str;
    }

    public static function fetchArray($data){
        if(static::isAssocArray($data)){
            return implode(",",$data);
        }else{
            $res = [];
            foreach ($data as $k =>$v){
                $v = static::_fetch($v);
                $res[] = "{$k}:{$v}";
            }
            return "[".implode(",",$data)."]";
        }
    }


    public static function isAssocArray(array $data){
        $index = 0;
        foreach (array_keys($data) as $key) {
            if ($index++ != $key) {
                return false;
            }
        }
        return true;
    }

}
