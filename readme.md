                        登录打通包开发使用说明
   1.下载包，在目录packages下执行下载
   git clone  git@gitlab.cw100.cn:root/unified.git

   2.配置composer.json，
   
    "autoload": {
              "psr-4": {
                  "App\\": "app/",
                  "Modules\\": "Modules/",
                  "Unified\\":"packages/unified/src"
              },
       }  
   
    
    3.配置app.php,providers添加下面数据
        \Unified\UnifiedServiceProvider::class,
        
    4.执行命令：composer dump_autoload 
    
    5.执行命令：php artisan vendor:publish
    
        php artisan vendor:publish --provider="Unified\UnifiedServiceProvider"
    执行完命令会在config文件生成一个名为unified的配置文件
        'go_url' => env('APP_GO_URL','http://tenant.cw100.la'),//要跳转的url地址
        'go_key'=>env('APP_GO_KEY','platform_fuwu'),//存储用户信息的键值,和加密密钥
        'minutes'=>env('UNIFIED_MINUTES',86400),//存储时长，时间戳,默认一天
        'guard'=>env('GUARD','tenant')//auth 的guard，默认tenant
        'is_domain'=>false//是否是同域名，false
        
    6.设置中间件
    《1.》配置app/Http/Kernel.php,在$routeMiddleware下添加如下数据
         'unified' =>\Unified\Middleware\UnifiedMiddleware::class,
    《2.》在路由文件中引入此中间件
    
    7.退出登录的时候，执行事件退出，清空cookie
       event(new LogoutEvent());
       
    8.当设置不同域名时需生成url
         $unified = new UnifiedManager();
         $url = $unified->getGoUrl();
    
    
    
        
                      
