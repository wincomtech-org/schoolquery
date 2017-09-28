<?php
/**
 * DouPHP
 * --------------------------------------------------------------------------------------------------
 * 版权所有 2013-2015 漳州豆壳网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.douco.com
 * --------------------------------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在遵守授权协议前提下对程序代码进行修改和使用；不允许对程序代码以任何形式任何目的的再发布。
 * 授权协议：http://www.douco.com/license.html
 * --------------------------------------------------------------------------------------------------
 * Author: DouCo
 * Release Date: 2015-06-10
 */
if (!defined('IN_DOUCO')) {
    die('Hacking attempt');
}
class Excel {
    /**
     * +----------------------------------------------------------
     * 导出会员资料
     * +----------------------------------------------------------
     */
    function export_excel($data = '') {
        require (ROOT_PATH . ADMIN_PATH . '/include/PHPExcel/PHPExcel.php');
        require (ROOT_PATH . ADMIN_PATH . '/include/PHPExcel/Excel5.php');
        
        // 创建一个处理对象实例
        $objExcel = new PHPExcel();
        
        // 创建文件格式写入对象实例, uncomment
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        
        //*************************************       
        //设置当前的sheet索引，用于后续的内容操作。       
        //一般只有在使用多个sheet的时候才需要显示调用。       
        //缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0       
        $objExcel->setActiveSheetIndex(0);       
        $objActSheet = $objExcel->getActiveSheet();       
    
        // 设置单元格宽度     
        $objActSheet->getColumnDimension('C')->setAutoSize(30);
        $objActSheet->getColumnDimension('K')->setAutoSize(true);
        
        // 设置单元格的值     
        $objActSheet->setCellValue('A1', $GLOBALS['_CFG']['site_name'] . '-' . $GLOBALS['_LANG']['user_list']);    
        // 合并单元格   
        $objActSheet->mergeCells('A1:K1');    
        
        // 设置表格标题栏内容
        $objActSheet->setCellValue('A2', $GLOBALS['_LANG']['user_email']);    
        $objActSheet->setCellValue('B2', $GLOBALS['_LANG']['user_nickname']);    
        $objActSheet->setCellValue('C2', $GLOBALS['_LANG']['user_telphone']);    
        $objActSheet->setCellValue('D2', $GLOBALS['_LANG']['user_contact']);    
        $objActSheet->setCellValue('E2', $GLOBALS['_LANG']['user_address']);    
        $objActSheet->setCellValue('F2', $GLOBALS['_LANG']['user_postcode']);    
        $objActSheet->setCellValue('G2', $GLOBALS['_LANG']['user_sex']);    
        
        $n=2;
        // 循环复制
        if ($data) $where = " WHERE user_id IN ('" . implode(',', $data) . "')";
        $sql = "SELECT * FROM " . $GLOBALS['dou']->table('user') . $where . " ORDER BY user_id DESC";
        $query = $GLOBALS['dou']->query($sql);
        while ($user = $GLOBALS['dou']->fetch_array($query)) {
            $n++;
            $user['sex'] = $user['sex'] ? $GLOBALS['_LANG']['user_man'] : $GLOBALS['_LANG']['user_woman'];
            
            $objActSheet->setCellValue('A'.$n, $user['email']);  
            $objActSheet->setCellValue('B'.$n, $user['nickname']); 
            $objActSheet->setCellValue('C'.$n, $user['telphone']); 
            $objActSheet->setCellValue('D'.$n, $user['contact']); 
            $objActSheet->setCellValue('E'.$n, $user['address']); 
            $objActSheet->setCellValue('F'.$n, $user['postcode']); 
            $objActSheet->setCellValue('G'.$n, $user['sex']); 
        }
        
        // 输出内容       
        $outputFileName = 'User' . date('Ymdhi').".xls";   
        
        // 到文件       
        $objWriter->save(ROOT_PATH . 'cache/' . $outputFileName);       
       
        // 文件直接输出到浏览器
        header ( 'Pragma:public');
        header ( 'Expires:0');
        header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header ( 'Content-Type:application/force-download');
        header ( 'Content-Type:application/vnd.ms-excel');
        header ( 'Content-Type:application/octet-stream');
        header ( 'Content-Type:application/download');
        header ( 'Content-Disposition:attachment;filename='. $outputFileName );
        header ( 'Content-Transfer-Encoding:binary');
        $objWriter->save ( 'php://output');
         
        @unlink(ROOT_PATH . 'cache/' . $outputFileName);
    } 
}
?>