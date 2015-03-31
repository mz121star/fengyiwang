<?php
//公共配置
$common_config = include APP_PATH.'../Conf/config.php';

//私有配置
$private_config = array(
                        'LAYOUT_ON' => true,
                        'URL_ROUTER_ON' => true,
                        'URL_CASE_INSENSITIVE' =>true,
                        'URL_ROUTE_RULES' => array(
                                                  'modsection/:sid' => 'Section/modsection',
                                                  'delsection/:sid' => 'Section/delsection',
                                                  'modedu/:eduid' => 'Edu/modedu',
                                                  'deledu/:eduid' => 'Edu/deledu',
                                                  'moduser/:uid' => 'User/moduser',
                                                  'deluser/:uid' => 'User/deluser',
                                                  'eduorder/:order' => 'Edu/eduorder',
                                                  'recommend/:oid' => 'Order/recommend',
                                                  'sign/:oid' => 'Order/sign',
                                                  'modorder/:oid' => 'Order/modorder',
                                                  'delorder/:oid' => 'Order/delorder',
                                                  'dellogo/:logoid' => 'System/dellogo',
                                                  'deltuan/:tuanid' => 'System/deltuan',
                                                  'delqrcode/:qrid' => 'Qrcode/delqrcode',
                                                    'edittuangou/:id'=>'TuanGou/modtuangou',
                                                     'editdaijin/:id'=>'Daijin/moddaijin',
                                               'downloadfile/:id'=>'Download/moddownload'
                                                  )
                        );

return array_merge($common_config, $private_config);
