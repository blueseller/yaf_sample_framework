<?php
/**
 * 示例
 * author : liukai
 */
class IndexController extends BaseController
{
	public function indexAction()
	{
		echo "123";
		exit;
	}

    /**
     * 链接DB方法
     */
    public function dbTestAction()
    {
        $con = DB::linkDb();
        echo '123';
        exit;
    }

    /**
     * 数据库操作例子
     */
    public function dbOptionAction()
    {
        $option = new OptionDbModel();
        //select 例子
        $re     = $option->seleclOption();
        var_dump($re);

        //delete 例子
        $re     = $option->delOption();
        var_dump($re);

        //insert 例子
        $re     = $option->insertOption();
        var_dump($re);

        //update 例子
        $re     = $option->updateOption();
        var_dump($re);
    }

}
