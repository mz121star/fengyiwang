<?php
//公共配置
$common_config = include APP_PATH.'../Conf/config.php';

//私有配置
$private_config = array(
                        'TMPL_ACTION_ERROR' => APP_PATH . 'Tpl/Index/jump.html',
                        'TMPL_ACTION_SUCCESS' => APP_PATH . 'Tpl/Index/jump.html',
                        'URL_ROUTER_ON' => true,
                        'URL_CASE_INSENSITIVE' =>true,
                        'URL_ROUTE_RULES' => array(
                                                  'edulist/:sid' => 'Edu/lists',
                                                  'syspic/:picid' => 'Index/syspic',
                                                  'detailedu/:eid' => 'Edu/detailedu',
                                                     'detail/:id' => 'TuanGou/detail',
                                                  'detailorder/:joid/:jeid' => 'User/detailorder',
                                                  'wx/:uid' => 'Index/home',
                                                  'setsource/:uid/:from' => 'Index/sendsource',
                                                  'weixin/:uid/:send' => 'Index/send',
                                                  'putlogo/:logoid' => 'Index/putlogo',
                                                  )
                        );

return array_merge($common_config, $private_config);
