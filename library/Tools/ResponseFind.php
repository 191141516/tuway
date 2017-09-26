<?php
namespace Library\Tools;

/**
 * 获取一个stdClass/array里的值
 * Class FindResponseValue
 * @package Library\Tools
 */
class ResponseFind
{
    private $_response;

    public function __construct($response)
    {
        $this->_response = $response;
    }

    /**
     * 获取多层的结果
     * @param $name string eg: OrderArray.Order.TransactionArray.Transaction.FinalValueFee
     * @param string $default
     * @return string
     */
    public function find($name, $default='')
    {
        $result = $default;

        $arr = explode('.', $name);
        $response = $this->_response;

        foreach ($arr as $property){
            if (is_object($response) && property_exists($response, $property)) {
                $result = $response = $this->getProperty($response, $property);

            }else if (is_array($response) && key_exists($property, $response)){
                $result = $response = $this->getItem($response, $property);
            }else{
                $result = $default;
            }
        }

        return $result;
    }

    private function getProperty($class, $property)
    {
        return $class->{$property};
    }

    private function getItem($array, $key)
    {
        return $array[$key];
    }
}