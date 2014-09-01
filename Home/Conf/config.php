<?php
//公共配置
$common_config = include APP_PATH.'../Conf/config.php';

//私有配置
$private_config = array(
                        'URL_ROUTER_ON' => true,
                        'URL_CASE_INSENSITIVE' =>true,
                        'URL_ROUTE_RULES' => array(
                                                  'edulist/:sid' => 'Edu/lists',
                                                  'detailorder/:joid/:jeid' => 'User/detailorder',
                                                  'wx/:uid' => 'Index/index',
                                                  'wx/:uid/:send' => 'Index/send'
                                                  )
                        );

return array_merge($common_config, $private_config);
