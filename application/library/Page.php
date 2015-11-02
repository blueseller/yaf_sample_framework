<?php

/**
 * author 
 */
class Page
{
	/**
     * 生成翻页代码.
     *
     * @param $total
     * @param $page_id
     * @param $pagesize
     * @param $url
     * @return string
     */
    public static function getPageHtml($total, $page_id, $pagesize = 18, $url = '')
    {/*{{{*/
        $pages      = ceil($total / $pagesize);
        $arr_return = array();
        if($page_id > 1) {
            $arr_return[]   = '<a href="'.$url.($page_id-1).'" class="prev"><em>«上一页</em></a>';
        } else {
            $arr_return[]   = '<a href="javascript:void(0);" class="prev page-blank"><em>«上一页</em></a>';
        }

        if(1 == $page_id) {
            $arr_return[]   = '<span class="cur"><em>1</em></span>';
        } else {
            $arr_return[]   = '<a href="'.$url.'1'.'"><em>1</em></a>';
        }
        $start  = ($page_id - 2);
        if(($pages - $page_id) < 4)
        {
            $start  = ($pages - 4);
        }
        if($start < 2)
        {
            $start  = 2;
        }
        $end    = $start + 4;
        if($end > $pages) {
            $end    = $pages - 1;
        }
        $out_page_id    = $start;
        for($i=1; $i<6; $i++) {
            if($out_page_id == $pages) {
                break;
            }
            if($out_page_id > $end) {
                break;
            }
            if($i == 1) {
                if($out_page_id != 2) {
                    //$arr_return[] = '<span class="page-numbers dots">&hellip;</span>';
                    $arr_return[]   = '<a href="javascript:void(0);" class="page-blank"><em>...</em></a>';
                } else {
                    $i++;
                }
            }

            if($out_page_id == $page_id) {
                $arr_return[]   = '<span class="cur"><em>'.$out_page_id.'</em></span>';
            } else {
                $arr_return[]   = '<a href="'.$url.$out_page_id.'"><em>'.$out_page_id.'</em></a>';
            }
            $out_page_id++;

        }
        //exit;
        if($out_page_id<$pages) {
            //$arr_return[]     = '<span class="page-numbers dots">&hellip;</span>';
            $arr_return[]   = '<a href="javascript:void(0);" class="page-blank"><em>...</em></a>';
        }
        if($pages > 1) {
            if($pages == $page_id) {
                $arr_return[]   = '<span class="cur"><em>'.$pages.'</em></span>';
            } else {
                $arr_return[]   = '<a href="'.$url.$pages.'"><em>'.$pages.'</em></a>';
            }
        }
        if($page_id < $pages) {
            $arr_return[]       = '<a href="'.$url.($page_id+1).'" class="next"><em>下一页»</em></a>';
        } else {
            $arr_return[]   = '<a href="javascript:void(0);" class="next page-blank"><em>下一页»</em></a>';
        }

        $str_return = implode('', $arr_return);
        if($pages < 2)
        {
            $str_return = "";
        }
        return '<div class="page"><fieldset><legend>分页</legend>'.$str_return.'</fieldset></div>';
    }
}