#########################################################################
# File Name: news_to_redis.sh
# Author: liukai
# mail: liukai-ps@360.cn
# Created Time: Wed 08 Jul 2015 05:50:32 PM CST
#########################################################################
#!/bin/bash

case $1 in
    release)
	#线上目录地址
    TASK_DIR="/online_base_path/cron"
    ;;
    dev)
	#开发目录地址
    TASK_DIR="/dev_base_path/cron"
    ;;
    *)
    ;;
esac

PHP="/usr/local/php/bin/php"
echo `date "+%Y-%m-%d %H:%M:%S"` $*

cd $TASK_DIR
$PHP -f cache_list.php $*

