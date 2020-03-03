<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 7/20/2017
 * Time: 6:06 PM
 */

namespace PocFramework\Support\Validation;


use PocFramework\Utils\AcceptLanguage;

class CustomMessages implements MessageInterface
{

    /**
     * Retrieve messages
     * Example:
     * [
     *      // The rule messages
     *      'rule_messages' => [
     *          'rule-name1' => 'message1',
     *          'rule-name2' => 'message2',
     *      ],
     *
     *      // The custom messages
     *      'custom_messages' => [
     *          'attribute-name1' => [
     *              'rule-name1' => 'custom-message1',
     *          ],
     *          'attribute-name2' => 'custom-message2',
     *      ],
     *
     *      // The custom Validation Attributes
     *      // This array is used to swap attribute place-holders
     *      // with something more reader friendly such as E-Mail Address instead
     *      // of "email". This simply helps us make messages a little cleaner.
     *      'attributes' => [],
     * ]
     *
     * @return array
     */
    protected $messages = [
        'zh' =>
            [
                'rule_messages' => [
                    'required' => '请输入:attribute',

                    'integer' => ':attribute必须是integer类型',
                    'boolean' => ':attribute的值必须是true或者false',
                    'string' => ':attribute必须是string类型',
                    'numeric' => ':attribute必须是数字',
                    'alpha_num' => ':attribute只能由数字或英文字母组成',
                    'float' => ':attribute必须是float类型',
                    'array' => ':attribute必须是个数组',

                    'max' => [
                        'integer' => ':attribute不能大于:max',
                        'string' => ':attribute不能超过:max个字符',
                        'array' => ':attribute的元素不能超过:max个',
                    ],
                    'min' => [
                        'integer' => ':attribute不能小于:min',
                        'string' => ':attribute不能少于:min个字符',
                        'array' => ':attribute的元素不能少于:min个',
                    ],
                    'between' => [
                        'integer' => ':attribute必须在:min和:max之间',
                        'string' => ':attribute的字符数必须在:min和:max之间',
                        'array' => ':attribute的元素个数必须在:min和:max之间',
                    ],
                    'between_as_int' => ':attribute的数值必须在:min和:max之间',
                    'in' => ':attribute必须是这些值中的一个(:values)',
                    'contain' => ':attribute必须包含\':phrase\'',
                    'no_space' => ':attribute不能包含空格',
                    'size' => [
                        'integer' => ':attribute必须是:size.',
                        'string' => ':attribute必须由:size个字符组成',
                        'array' => ':attribute的元素必须有:size个',
                    ],

                    'ip' => ':attribute必须是合法的ip地址',
                    'email' => ':attribute必须是合法的邮箱地址',
                    'cn_mobile' => ':attribute必须是合法的手机号码',
                    'cn_id_card' => ':attribute必须是合法的中国身份证号码',

                    'date_format' => ':attribute的格式必须是:date_format',
                    'regex' => ':attribute格式不正确',
                    'json' => ':attribute无法转为json格式',
                ],

                'custom_message' => [
                ],

                'attributes' => [
                    'username' => '用户名',
                    'email' => '邮箱',
                    'mobile' => '手机号',
                    'password' => '密码',
                    'repassword' => '重复密码',
                    'capid' => '验证码',
                    'imgcode' => '验证码',
                    'code' => '验证码',
                    'sms_code' => '短信验证码',
                    'type' => '类型',
                    'account_id' => '主账号ID',
                ],
            ],
    ];

    public function getMessages()
    {
        $al = AcceptLanguage::get();
        if (array_key_exists($al, $this->messages)) {
            return $this->messages[$al];
        }

        return Variable::getDefaultRuleMessages();
    }
}