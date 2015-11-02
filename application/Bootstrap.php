<?php
/**
 * @name Bootstrap
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 *
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{

	/**
	 * @return [type]
	 * 做浏览器检查，IE不安全不建议使用
	 */
	public function _initWebkit()
	{
		if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 6.0")){
			header("location:index.php");
		}
	}

    public function _initConfig() 
    {

        Yaf_Dispatcher::getInstance()->autoRender(FALSE);  // 关闭自动加载模板
		//把配置保存起来
		$arrConfig = Yaf_Application::app()->getConfig();
		Yaf_Registry::set('config', $arrConfig);
		
	}

	//在这里注册自己的路由协议,默认使用简单路由
	public function _initRoute(Yaf_Dispatcher $dispatcher) 
	{	
		$router = $dispatcher->getRouter();
		$route = new Yaf_Route_Simple("rm", "ro", "ra");
		$router->addRoute("myroute", $route);
	}

}
