<?php

return $private_config = array(
                                'DB_PREFIX' => 'dc_',
                                'DB_DSN' => 'mysql://root:820819@localhost:3306/fengyi',

                                'SHOW_PAGE_TRACE' => true,
                                'DEFAULT_FILTER'=>'htmlspecialchars,stripslashes',

                                'LAYOUT_ON' => true,

                                'URL_ROUTER_ON' => true,
                                'URL_CASE_INSENSITIVE' =>true,
                                'URL_ROUTE_RULES' => array(
                                                      'shop/detail/:shopid' => 'Shop/index'
                                                      ));//私有配置

