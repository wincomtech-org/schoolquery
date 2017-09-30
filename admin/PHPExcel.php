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
 * Release Date: 2015-10-16
 */
define('IN_DOUCO', true);

    require (dirname(__FILE__) . '/include/init.php');
     require ('PHPExcel/Classes/PHPExcel.php');
     //exit;
    // 验证并获取合法的ID
    $id = $check->is_number($_REQUEST['id']) ? $_REQUEST['id'] : '';
    if(empty($id)){
        exit;
    }
    $query = $dou->select($dou->table('customer'), '*', '`id` = \'' . $id . '\'');
    $customer = $dou->fetch_array($query);
    //print_r($school);
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    $customer['school_name']= explode("|", $customer['school_name']); 
     $customer['school_adress']= explode("|", $customer['school_adress']); 
    $customer['school_tel']= explode("|", $customer['school_tel']); 
    $customer['school_year']= explode("|", $customer['school_year']); 
    $customer['school_time']= explode("|", $customer['school_time']); 
    $customer['education']= explode("|", $customer['education']);
    $customer['work_name']= explode("|", $customer['work_name']);
    $customer['work_adress']= explode("|", $customer['work_adress']);
    $customer['work_tel']= explode("|", $customer['work_tel']);
    $customer['work_year']= explode("|", $customer['work_year']);
    $customer['work_time']= explode("|", $customer['work_time']);
    $customer['position']= explode("|", $customer['position']);
    
    $customer['id_cent']= explode("|", $customer['id_cent']);
    foreach($customer['id_cent'] as $val){
        $str=explode(",",$val);
        $id_cent[] =$str[(count($str)-1)];
    }
    print_r( $customer['id_cent']);
    //exit;
    $customer['id_cent']=$id_cent;
    $customer['id_phono']= explode("|", $customer['id_phono']);
    foreach($customer['id_phono'] as $val){
        $str=explode(",",$val);
        $id_phono[] =$str[(count($str)-1)];
    }
    $customer['id_phono']=$id_phono;
    $customer['mark']= explode("|", $customer['mark']);
    foreach($customer['mark'] as $val){
        $str=explode(",",$val);
        $mark[] =$str[(count($str)-1)];
    }
    $customer['mark']=$mark;
    $customer['recom']= explode("|", $customer['recom']);
    foreach($customer['recom'] as $val){
        $str=explode(",",$val);
        $recom[] =$str[(count($str)-1)];
    }
    $customer['recom']=$recom;
    $customer['engprove']= explode("|", $customer['engprove']);
    foreach($customer['engprove'] as $val){
        $str=explode(",",$val);
        $engprove[] =$str[(count($str)-1)];
    }
    $customer['engprove']=$engprove;
    $customer['work_cent']= explode("|", $customer['work_cent']);
    foreach($customer['work_cent'] as $val){
        $str=explode(",",$val);
        $work_cent[] =$str[(count($str)-1)];
    }
    $customer['work_cent']=$work_cent;
    $customer['other']= explode("|", $customer['other']);
    foreach($customer['other'] as $val){
        $str=explode(",",$val);
        $other[] =$str[(count($str)-1)];
    }
    $customer['other']=$other;
    $customer['tutor_cent']= explode("|", $customer['tutor_cent']);
    foreach($customer['tutor_cent'] as $val){
        $str=explode(",",$val);
        $tutor_cent[] =$str[(count($str)-1)];
    }
    $customer['tutor_cent']=$tutor_cent;
    $customer['tutor_idphono']= explode("|", $customer['tutor_idphono']);
    foreach($customer['tutor_idphono'] as $val){
        $str=explode(",",$val);
        $tutor_idphono[] =$str[(count($str)-1)];
    }
    $customer['tutor_idphono']=$tutor_idphono;
    if($customer['sex']==0){
       $customer['sex']='男'; 
    }else{
        $customer['sex']='女';
    }
    if($customer['konw_gd']==0){
       $customer['konw_gd']='懂'; 
    }else{
        $customer['konw_gd']='不懂';
    }
    if($customer['tutor_sex']==0){
       $customer['tutor_sex']='男'; 
    }else{
        $customer['tutor_sex']='女';
    }
  print_r($customer);
  $obj=new PHPExcel();
  $obj->getProperties()  
        ->setCreator("WOLF")  
        ->setLastModifiedBy("WOLF")  
        ->setTitle("Office 2007 XLSX Test Document")  
        ->setSubject("Office 2007 XLSX Test Document")  
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP clas");

    //3.填充表格 
  
// $obj->setActiveSheetIndex()->getDefaultStyle()->getAlignment()>setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$obj->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objActSheet = $obj->setActiveSheetIndex(0); //填充表头  

$obj->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //设置单元格宽度
    $obj->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('G')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('J')->setWidth(30);
    $obj->getActiveSheet()->getColumnDimension('K')->setWidth(25);
    $obj->getActiveSheet()->getColumnDimension('L')->setWidth(30);
    $obj->getActiveSheet()->getColumnDimension('M')->setWidth(50);
    $obj->getActiveSheet()->getColumnDimension('N')->setWidth(50);
    $obj->getActiveSheet()->getColumnDimension('O')->setWidth(30);
    $obj->getActiveSheet()->getColumnDimension('P')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('R')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('S')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('T')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('U')->setWidth(30);
    $obj->getActiveSheet()->getColumnDimension('V')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('W')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('X')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('Y')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('Z')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('AA')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('AB')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('AC')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('AD')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AE')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AF')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AG')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AH')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AI')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AJ')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AK')->setWidth(20);
    $obj->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
    $obj->getActiveSheet()->getColumnDimension('AM')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('AN')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('AO')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('AP')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('AQ')->setWidth(40);
    $obj->getActiveSheet()->getColumnDimension('AR')->setWidth(20);
    $obj->getActiveSheet()->getColumnDimension('AS')->setWidth(10);
    $obj->getActiveSheet()->getColumnDimension('AT')->setWidth(100);
    $obj->getActiveSheet()->getColumnDimension('AU')->setWidth(100);
   
 $obj->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    $objActSheet->setCellValue('A1','ID');  
    $objActSheet->setCellValue('B1','姓名');  
    $objActSheet->setCellValue('C1','英文名');  
    $objActSheet->setCellValue('D1','性别'); 
    $objActSheet->setCellValue('E1','出生年月');
    $objActSheet->setCellValue('F1','国籍');
    $objActSheet->setCellValue('G1','户籍');
    $objActSheet->setCellValue('H1','手机号');
    $objActSheet->setCellValue('I1','香港手机号');
    $objActSheet->setCellValue('J1','内地身份证号');
    $objActSheet->setCellValue('K1','港澳台通行证/护照号码');
    $objActSheet->setCellValue('L1','港澳身份证号');
    $objActSheet->setCellValue('M1','内地住址');
    $objActSheet->setCellValue('N1','香港住址');
    $objActSheet->setCellValue('O1','学历');
    $objActSheet->setCellValue('U1','工作经历');

    $objActSheet->setCellValue('AA1','懂广东话');
    $objActSheet->setCellValue('AB1','特殊学习需要');
    $objActSheet->setCellValue('AC1','健康问题');
    $objActSheet->setCellValue('AD1','身份证明文件');
    $objActSheet->setCellValue('AE1','证件照');
    $objActSheet->setCellValue('AF1','成绩表/毕业证');
    $objActSheet->setCellValue('AG1','推荐信');
    $objActSheet->setCellValue('AH1','工作证明信');
    $objActSheet->setCellValue('AI1','英语证明');
    $objActSheet->setCellValue('AJ1','其他相关材料');
    $objActSheet->setCellValue('AK1','监护人姓名');
    $objActSheet->setCellValue('AL1','监护人英语名');
    $objActSheet->setCellValue('AM1','监护人性别');
    $objActSheet->setCellValue('AN1','监护人年龄');
    $objActSheet->setCellValue('AO1','监护人内地身份证号');
    $objActSheet->setCellValue('AP1','监护人通行证/护照号');
    $objActSheet->setCellValue('AQ1','监护人港澳身份证号');
    $objActSheet->setCellValue('AR1','监护人职业');
    $objActSheet->setCellValue('AS1','与客户关系');
    $objActSheet->setCellValue('AT1','监护人身份证明材料');
    $objActSheet->setCellValue('AU1','监护人证件照');



    $obj->getActiveSheet()->mergeCells('A2:A5'); 
    $obj->getActiveSheet()->mergeCells('B2:B5');
    $obj->getActiveSheet()->mergeCells('C2:C5');
    $obj->getActiveSheet()->mergeCells('D2:D5');
    $obj->getActiveSheet()->mergeCells('E2:E5');
    $obj->getActiveSheet()->mergeCells('F2:F5');
    $obj->getActiveSheet()->mergeCells('G2:G5');
    $obj->getActiveSheet()->mergeCells('H2:H5');
    $obj->getActiveSheet()->mergeCells('I2:I5');
    $obj->getActiveSheet()->mergeCells('J2:J5');
    $obj->getActiveSheet()->mergeCells('K2:K5');
    $obj->getActiveSheet()->mergeCells('L2:L5');
    $obj->getActiveSheet()->mergeCells('M2:M5');
    $obj->getActiveSheet()->mergeCells('N2:N5');
    $obj->getActiveSheet()->mergeCells('O1:T1');
    $obj->getActiveSheet()->mergeCells('U1:Z1');
    $obj->getActiveSheet()->mergeCells('AA2:AA5');
    $obj->getActiveSheet()->mergeCells('AB2:AB5');
    $obj->getActiveSheet()->mergeCells('AC2:AC5');
    $obj->getActiveSheet()->mergeCells('AD2:AD5');
    $obj->getActiveSheet()->mergeCells('AE2:AE5');
    $obj->getActiveSheet()->mergeCells('AF2:AF5');
    $obj->getActiveSheet()->mergeCells('AG2:AG5');
    $obj->getActiveSheet()->mergeCells('AH2:AH5');
    $obj->getActiveSheet()->mergeCells('AI2:AI5');
    $obj->getActiveSheet()->mergeCells('AJ2:AJ5');
    $obj->getActiveSheet()->mergeCells('AK2:AK5');
    $obj->getActiveSheet()->mergeCells('AL2:AL5');
    $obj->getActiveSheet()->mergeCells('AM2:AM5');
    $obj->getActiveSheet()->mergeCells('AN2:AN5');
    $obj->getActiveSheet()->mergeCells('AO2:AO5');
    $obj->getActiveSheet()->mergeCells('AP2:AP5');
    $obj->getActiveSheet()->mergeCells('AQ2:AQ5');
    $obj->getActiveSheet()->mergeCells('AR2:AR5');
    $obj->getActiveSheet()->mergeCells('AS2:AS5');
    $obj->getActiveSheet()->mergeCells('AT2:AT5');
    $obj->getActiveSheet()->mergeCells('AU2:AU5');

  

    // $obj->getActiveSheet()->mergeCells('U1:Z1');
    // $obj->getActiveSheet()->mergeCells('U1:Z1');

    $objActSheet->setCellValue('O2','学校名称');
    $objActSheet->setCellValue('P2','学校地址');
    $objActSheet->setCellValue('Q2','学校电话');
    $objActSheet->setCellValue('R2','修读年期');
    $objActSheet->setCellValue('S2','修读时间');
    $objActSheet->setCellValue('T2','所获学历');

    $objActSheet->setCellValue('U2','公司名称');
    $objActSheet->setCellValue('V2','公司地址');
    $objActSheet->setCellValue('W2','公司电话');
    $objActSheet->setCellValue('X2','工作年期');
    $objActSheet->setCellValue('Y2','入职时间');
    $objActSheet->setCellValue('Z2','职位');
    $objActSheet->setCellValue('A2',$customer['id']);  
    $objActSheet->setCellValue('B2',$customer['name']);  
    $objActSheet->setCellValue('C2',$customer['engname']);  
    $objActSheet->setCellValue('D2',$customer['sex']); 
    $objActSheet->setCellValue('E2',$customer['data']); 
    $objActSheet->setCellValue('F2',$customer['nation']); 
    $objActSheet->setCellValue('G2',$customer['contry']); 
    $objActSheet->setCellValue('H2',$customer['phone']); 
    $objActSheet->setCellValue('I2',$customer['gphone']); 
    $objActSheet->setCellValue('J2',$customer['id_number']); 
    $objActSheet->setCellValue('K2',$customer['pass_check']); 
    $objActSheet->setCellValue('L2',$customer['gid']); 
    $objActSheet->setCellValue('M2',$customer['n_adress']);
    $objActSheet->setCellValue('N2',$customer['g_adress']);

    //学习经历
    $objActSheet->setCellValue('O3',$customer['school_name'][0]);
    $objActSheet->setCellValue('O4',$customer['school_name'][1]);
    $objActSheet->setCellValue('O5',$customer['school_name'][2]);
    $objActSheet->setCellValue('P3',$customer['school_adress'][0]);
    $objActSheet->setCellValue('P4',$customer['school_adress'][1]);
    $objActSheet->setCellValue('P5',$customer['school_adress'][2]);
    $objActSheet->setCellValue('Q3',$customer['school_tel'][0]);
    $objActSheet->setCellValue('Q4',$customer['school_tel'][1]);
    $objActSheet->setCellValue('Q5',$customer['school_tel'][2]);
    $objActSheet->setCellValue('R3',$customer['school_year'][0]);
    $objActSheet->setCellValue('R4',$customer['school_year'][1]);
    $objActSheet->setCellValue('R5',$customer['school_year'][2]);
    $objActSheet->setCellValue('S3',$customer['school_time'][0]);
    $objActSheet->setCellValue('S4',$customer['school_time'][1]);
    $objActSheet->setCellValue('S5',$customer['school_time'][2]);
    $objActSheet->setCellValue('T3',$customer['education'][0]);
    $objActSheet->setCellValue('T4',$customer['education'][1]);
    $objActSheet->setCellValue('T5',$customer['education'][2]);
    //工作经历
    $objActSheet->setCellValue('U3',$customer['work_name'][0]);
    $objActSheet->setCellValue('U4',$customer['work_name'][1]);
    $objActSheet->setCellValue('U5',$customer['work_name'][2]);
    $objActSheet->setCellValue('V3',$customer['work_adress'][0]);
    $objActSheet->setCellValue('V4',$customer['work_adress'][1]);
    $objActSheet->setCellValue('V5',$customer['work_adress'][2]);
    $objActSheet->setCellValue('W3',$customer['work_tel'][0]);
    $objActSheet->setCellValue('W4',$customer['work_tel'][1]);
    $objActSheet->setCellValue('W5',$customer['work_tel'][2]);
    $objActSheet->setCellValue('X3',$customer['work_year'][0]);
    $objActSheet->setCellValue('X4',$customer['work_year'][1]);
    $objActSheet->setCellValue('X5',$customer['work_year'][2]);
    $objActSheet->setCellValue('Y3',$customer['work_time'][0]);
    $objActSheet->setCellValue('Y4',$customer['work_time'][1]);
    $objActSheet->setCellValue('Y5',$customer['work_time'][2]);
    $objActSheet->setCellValue('Z3',$customer['position'][0]);
    $objActSheet->setCellValue('Z4',$customer['position'][1]);
    $objActSheet->setCellValue('Z5',$customer['position'][2]);

    //
      $objActSheet->setCellValue('AA2',$customer['konw_gd']);
      $objActSheet->setCellValue('AB2',$customer['specail']);
      $objActSheet->setCellValue('AC2',$customer['health']);
    //插入图片
    
        $i=2;
        foreach($customer['id_cent'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AD2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
         $i=2;
        foreach($customer['id_phono'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AE2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
         $i=2;
        foreach($customer['mark'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AF2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
         $i=2;
        foreach($customer['recom'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AG2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
         $i=2;
        foreach($customer['work_cent'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AH2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
         $i=2;
        foreach($customer['engprove'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AI2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
         foreach($customer['other'] as $v){
            if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AJ2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
        //监护人信息
        $objActSheet->setCellValue('AK2',$customer['tutor_name']);
        $objActSheet->setCellValue('AL2',$customer['tutor_engname']);
        $objActSheet->setCellValue('AM2',$customer['tutor_sex']);
        $objActSheet->setCellValue('AN2',$customer['tutor_year']);
        $objActSheet->setCellValue('AO2',$customer['tutor_id']);
        $objActSheet->setCellValue('AP2',$customer['tutor_pass']);
        $objActSheet->setCellValue('AQ2',$customer['tutor_gid']);
        $objActSheet->setCellValue('AR2',$customer['tutor_work']);
        $objActSheet->setCellValue('AS2',$customer['relation']);

         $i=2;
        foreach($customer['tutor_cent'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AT2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
         $i=2;
        foreach($customer['tutor_idphono'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("AU2");
            $objDrawing->setWidth(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }

    //导出信息
     $obj->getActiveSheet()->setTitle('客户信息表');  
    $obj->setActiveSheetIndex(0);  
    $day      = date("m-d");  
    $filename = $day.'客户信息表.xls';  
    ob_end_clean();//清除缓冲区,避免乱码  
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");  
    header('Content-Disposition: attachment;filename='.$filename);  
    header('Cache-Control: max-age=0');  
    $objWriter = PHPExcel_IOFactory::createWriter($obj,'Excel5');  
    $objWriter->save('php://output');  
    exit;   
?>