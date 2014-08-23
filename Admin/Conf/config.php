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
                                                  'deledu/:eduid' => 'Edu/deledu'
                                                  )
                        );

return array_merge($common_config, $private_config);
