<?php
/**
 * +----------------------------------------------------------------------
 * | UniAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://uniadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

namespace app\core\util\xybuilder;

/**
 * XyBuilderForm动态表单
 *
 * @author jry <ijry@qq.com>
 */
class XyBuilderForm {

    // 数据
    private $data;
    public $form_type = [
        'hidden' => '隐藏元素',
        'text' => '单行文本',
        'password' => '密码',
        'url' => 'URL网址',
        'email' => '邮箱',
        'date' => '日期',
        'number' => '数字',
        'digit' => '浮点型数字',
        'tel' => '手机号',
        'textarea' => '多行文本',
        'array' => '自定义数组',
        'markdown' => 'Markdown',
        'tinymce' => 'TinyMCE富文本',
        'select' => '下拉框',
        'radio' => '单选框',
        'checkbox' => '多选框',
        'switch' => '开关',
        'slider' => '滑块',
        'datepicker' => '日期',
        'timepicker' => '时刻',
        'datetimepicker' => '时间',
        'rate' => '星级评分',
        'cascader' => '级联选择',
        'colorpicker' => '颜色选择器',
        'image' => '单图上传',
        'images' => '多图上传',
        'file' => '单文件上传',
        'files' => '多文件上传',
        'checkboxtree' => '树状表格复选',
    ];

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    public function init() {
        $this->data = [
            'alertList' => [
                'top' => [],
                'bottom' => []
            ],
            'formMethod' => 'post',
            'formItems' => [],
            'formValues' => [],
            'formRules' => [],
            'formTabs' => [],
        ];
        return $this;
    }

    /**
     * 设置提交方法
     * @author jry <ijry@qq.com>
     */
    public function setFormMethod($method = 'post') {
        $this->data['formMethod'] = $method;
        return $this;
    }

    /**
     * 添加表达项目
     * @author jry <ijry@qq.com>
     */
    public function addFormItem(
        $name,
        $title,
        $type = 'text',
        $value = '' ,
        $extra = []
    ) {
        $item['name'] = $name;
        $item['title'] = $title;
        $item['type'] = $type;
        $item['value'] = $value;
        $item['temp'] = ''; // 临时使用
        $item['showModal'] = false; // 显示弹窗
        $extra['placeholder'] = isset($extra['placeholder']) ? $extra['placeholder'] : '';
        $extra['tip'] = isset($extra['tip']) ? $extra['tip'] : '';
        $extra['position'] = isset($extra['position']) ? $extra['position'] : '';
        // 上传
        if (in_array($item['type'], ['image', 'images', 'file', 'files', 'html', 'tinymce', 'markdown'])) {
            // 上传接口
            if (isset($extra['action'])) {
                $extra['action'] = $extra['action'];
            } else {
                $extra['action'] = request()->root(true) . '/api/v1/core/index/upload/';
            }
            // 文件格式
            if (isset($extra['format'])) {
                $extra['format'] = $extra['format'];
            } else {
                if (in_array($item['type'], ['image', 'images'])) {
                    $extra['format'] = ['jpg','jpeg','png','gif','ico'];
                } else {
                    $extra['format'] = ['jpg','jpeg','png','gif','ico', 'swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'mp4', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'wps', 'txt', 'zip', 'rar', 'gz', 'bz2', '7z', 'ipa', 'apk', 'dmg', 'iso', 'pem', 'p12', 'wgt'];
                }
            }
            // 文件大小限制
            if (isset($extra['maxSize'])) {
                $extra['maxSize'] = $extra['maxSize'];
            } else {
                $extra['maxSize'] = 512;
            }
            $extra['driver'] = ''; // 默认上传驱动，不填默认本地上传
            $extra['data'] = [];
        }
        if (isset($extra['options'])) {
            $options = [];
            if (is_array($extra['options'])) {
                foreach ($extra['options'] as $key => $val) {
                    if (!is_array($val)) {
                        $tmp['title'] = $val;
                        $tmp['value'] = $key . ''; // 转换成字符串，防止本来是数字，但是存在2,3形式再explode后变成字符串类属性导致不匹配
                        $options[] = $tmp;
                    } else {
                        $options[] = $val;
                    }
                }
                $extra['options'] = $options;
            }
        }
        $item['extra'] = $extra;
        $this->data['formItems'][] = $item;
        return $this;
    }

    /**
     * 添加表单验证
     * @author jry <ijry@qq.com>
     */
    public function addFormRule($name, $rule){
        $this->data['formRules'][$name] = $rule;
        return $this;
    }

    /**
     * 设置表单数据
     * @author jry <ijry@qq.com>
     */
    public function setFormValues($data = []) {
        foreach ($this->data['formItems'] as $key => &$val) {
            if (isset($data[$val['name']])) {
                $val['value'] = $data[$val['name']];
            }
            if (in_array($val['type'], ['static'])) {
                continue;
            }
            $this->data['formValues'][$val['name']] = '';
            if (isset($val['value'])) {
                $this->data['formValues'][$val['name']] = $val['value'];
            }
        }
        return $this;
    }

    /**
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public function getData() {
        return $this->data;
    }
}
