<?php
namespace Admin\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class CommonController extends Controller {
    public function _initialize(){
        if (!session('?cdlchd_adminid')) {
            $this->redirect('Logreg/login', '', 0, '');
        }
        $info = M('admin')->where('user_id = '.session('cdlchd_adminid'))->find();
        $menu = include(dirname(__DIR__).'/Common/action_list.php');
        $this->assign('nav',$this->nowNav());
        $this->assign('menu',$menu);
        $this->assign('header',$info);
    }
    /**
     * 管理员日志
     */
    public function admin_log($info){
        $data = [
            'log_time' => time(),
            'user_id' => session('cdlchd_adminid'),
            'log_info' => $info,
            'ip_address' => $this->get_ip()
        ];
        M('admin_log')->add($data);
    }
    /**
     * excel下载方法
     */
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $xlsTitle;
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Reader.Excel2007");

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("CDLCHD") //创建人
                                    ->setLastModifiedBy("CDLCHD") //最后修改人
                                    ->setTitle("CDLCHD") //标题
                                    ->setSubject("CDLCHD") //题目
                                    ->setDescription("CDLCHD") //描述
                                    ->setKeywords("CDLCHD") //关键字
                                    ->setCategory("CDLCHD"); //种类
        // 设置行高
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);  //-1自动
        //设置列宽
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);

        //对第二列赋值(列头)
        for($i = 0,$j = 'A';$i<$cellNum;$i++,$j++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($j.'2', $expCellName[$i][1]);
            $objPHPExcel->getActiveSheet()->getStyle($j.'2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //设置列头居中
        }
        $objPHPExcel->getActiveSheet()->freezePane('A2'); //冻结首行

        // Miscellaneous glyphs, UTF-8
        for($i = 0;$i<$dataNum;$i++){
            for($j = 0,$k = 'A';$j<$cellNum;$j++,$k++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($k.($i+3), $expTableData[$i][$expCellName[$j][0]]." ");
                $end = $k;
            }
        }
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$end.'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle); //设置表名(第一行)
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置首行居中
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet(0)->setTitle($expTitle);

        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=". $fileName .".xlsx");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }
    /**
     * 获取ip
     */
    public function get_ip(){
        static $realip;
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }
    /**
     * 当前位置
     */
    public function nowNav($now = ''){
        $menu = include(dirname(__DIR__).'/Common/action_list.php');
        foreach($menu as $key => $val){
            foreach ($val['menu'] as $k => $v){
                if($v[0] == ACTION_NAME){
                    $nav = $key;
                    $nav_1 = $val['name'];
                    $nav_2 = $v[1];
                    $nav_url = $v[2];
                    break;
                }
            }
        }
        if(!isset($nav_1) && empty($now)){//首页
            return "<div class='admin-c-nav'><ol class='am-breadcrumb'  style='margin-bottom:-20px'>当前位置：<li><a href='".U('Index/index')."'>首页</a></li></ol></div><hr>";
        }
        if(empty($now)){
            return "<script>".'$'."(function(){".'$'."('#collapse-".$nav."').collapse({toggle: true})})</script><div class='admin-c-nav'><ol class='am-breadcrumb' style='margin-bottom:-20px'>当前位置：<li><a href='".U('Index/index')."'>首页</a></li><li class='am-active'>".$nav_1." </li><li class='am-active'>".$nav_2."</li></ol></div><hr>";
        }else{
            /*if(!isset($nav_1)){
                $top = '';
            }else{
                $top = "<li class='am-active'>".$nav_1."</li><li><a href='".$nav_url."'>".$nav_2."</a></li>";
            }*/
            $top = '';
            $con = '';
            foreach($now as $k => $v){
                if($v[1] == ''){
                    $con .= "<li class='am-active'>".$v[0]."</li>";
                }else{
                    $con .= "<li><a href='".$v[1]."'>".$v[0]."</a></li>";
                }
            }
            return "<script>".'$'."(function(){".'$'."('#collapse-".$nav."').collapse({toggle: true})})</script><div class='admin-c-nav'><ol class='am-breadcrumb' style='margin-bottom:-20px'>当前位置：<li><a href='".U('Index/index')."'>首页</a></li>".$top.$con."</ol></div><hr>";
        }
    }
}