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
    
     /*
        *判断上传的文件是否为图片格式
     */
    function img($name){
        $arr=array('jpg','png','svg');
        foreach($name as $v){
            $d=substr($v, strrpos($v, '.')+1);
            if(in_array($d,$arr)){
                $imgname[]=$v;
            }
        }
        return $imgname;
    }

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
    $id_cent=img($id_cent);
    $customer['id_cent']=$id_cent;

    $customer['id_phono']= explode("|", $customer['id_phono']);
    foreach($customer['id_phono'] as $val){
        $str=explode(",",$val);
        $id_phono[] =$str[(count($str)-1)];
    }
    $id_phono=img($id_phono);
    $customer['id_phono']=$id_phono;

    $customer['mark']= explode("|", $customer['mark']);
    foreach($customer['mark'] as $val){
        $str=explode(",",$val);
        $mark[] =$str[(count($str)-1)];
    }
    $mark=img($mark);
    $customer['mark']=$mark;

    $customer['recom']= explode("|", $customer['recom']);
    foreach($customer['recom'] as $val){
        $str=explode(",",$val);
        $recom[] =$str[(count($str)-1)];
    }
    $recom=img($recom);
    $customer['recom']=$recom;

    $customer['engprove']= explode("|", $customer['engprove']);
    foreach($customer['engprove'] as $val){
        $str=explode(",",$val);
        $engprove[] =$str[(count($str)-1)];
    }
    $engprove=img($engprove);
    $customer['engprove']=$engprove;

    $customer['work_cent']= explode("|", $customer['work_cent']);
    foreach($customer['work_cent'] as $val){
        $str=explode(",",$val);
        $work_cent[] =$str[(count($str)-1)];
    }
    $work_cent=img($work_cent);
    $customer['work_cent']=$work_cent;

    $customer['other']= explode("|", $customer['other']);
    foreach($customer['other'] as $val){
        $str=explode(",",$val);
        $other[] =$str[(count($str)-1)];
    }
    $other=img($other);
    $customer['other']=$other;

    $customer['tutor_cent']= explode("|", $customer['tutor_cent']);
    foreach($customer['tutor_cent'] as $val){
        $str=explode(",",$val);
        $tutor_cent[] =$str[(count($str)-1)];
    }
    $tutor_cent=img($tutor_cent);
    $customer['tutor_cent']=$tutor_cent;

    $customer['tutor_idphono']= explode("|", $customer['tutor_idphono']);
    foreach($customer['tutor_idphono'] as $val){
        $str=explode(",",$val);
        $tutor_idphono[] =$str[(count($str)-1)];
    }
    $tutor_idphono=img($tutor_idphono);
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
  //print_r($customer);
  $obj=new PHPExcel();
  $obj->getProperties()  
        ->setCreator("WOLF")  
        ->setLastModifiedBy("WOLF")  
        ->setTitle("Office 2007 XLSX Test Document")  
        ->setSubject("Office 2007 XLSX Test Document")  
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP clas");

    //3.填充表格 
  
// $obj->setActiveSheetIndex()->getDefaultStyle()->getAlignment()>setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$obj->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objActSheet = $obj->setActiveSheetIndex(0); //填充表头  

$obj->setActiveSheetIndex()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //设置单元格宽度
    $obj->getActiveSheet()->getColumnDimension('A')->setWidth(30);
    $obj->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $obj->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $obj->getActiveSheet()->getRowDimension('1')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('2')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('3')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('4')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('5')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('6')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('7')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('8')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('9')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('10')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('11')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('12')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('13')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('14')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('15')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('16')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('17')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('18')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('19')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('20')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('21')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('22')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('23')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('24')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('25')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('26')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('27')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('28')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('29')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('30')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('31')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('32')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('33')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('34')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('35')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('36')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('37')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('38')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('39')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('40')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('41')->setRowHeight(20);//行高
    $obj->getActiveSheet()->getRowDimension('42')->setRowHeight(100);//行高
    $obj->getActiveSheet()->getRowDimension('43')->setRowHeight(100);//行高
   
 $obj->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    $objActSheet->setCellValue('A1','ID');  
    $objActSheet->setCellValue('A2','姓名');  
    $objActSheet->setCellValue('A3','英文名');  
    $objActSheet->setCellValue('A4','性别'); 
    $objActSheet->setCellValue('A5','出生年月');
    $objActSheet->setCellValue('A6','国籍');
    $objActSheet->setCellValue('A7','户籍');
    $objActSheet->setCellValue('A8','手机号');
    $objActSheet->setCellValue('A9','香港手机号');
    $objActSheet->setCellValue('A10','内地身份证号');
    $objActSheet->setCellValue('A11','港澳台通行证/护照号码');
    $objActSheet->setCellValue('A12','港澳身份证号');
    $objActSheet->setCellValue('A13','内地住址');
    $objActSheet->setCellValue('A14','香港住址');
    $objActSheet->setCellValue('A15','学历');
    $objActSheet->setCellValue('A19','工作经历');

    $objActSheet->setCellValue('A23','懂广东话');
    $objActSheet->setCellValue('A24','特殊学习需要');
    $objActSheet->setCellValue('A25','健康问题');
    $objActSheet->setCellValue('A26','身份证明文件');
    $objActSheet->setCellValue('A27','证件照');
    $objActSheet->setCellValue('A28','成绩表/毕业证');
    $objActSheet->setCellValue('A29','推荐信');
    $objActSheet->setCellValue('A30','工作证明信');
    $objActSheet->setCellValue('A31','英语证明');
    $objActSheet->setCellValue('A32','其他相关材料');
    $objActSheet->setCellValue('A33','监护人姓名');
    $objActSheet->setCellValue('A34','监护人英语名');
    $objActSheet->setCellValue('A35','监护人性别');
    $objActSheet->setCellValue('A36','监护人年龄');
    $objActSheet->setCellValue('A37','监护人内地身份证号');
    $objActSheet->setCellValue('A38','监护人通行证/护照号');
    $objActSheet->setCellValue('A39','监护人港澳身份证号');
    $objActSheet->setCellValue('A40','监护人职业');
    $objActSheet->setCellValue('A41','与客户关系');
    $objActSheet->setCellValue('A42','监护人身份证明材料');
    $objActSheet->setCellValue('A43','监护人证件照');

    $objActSheet->setCellValue('B15','学校名称');
    $objActSheet->setCellValue('C15','学校地址');
    $objActSheet->setCellValue('D15','学校电话');
    $objActSheet->setCellValue('E15','修读年期');
    $objActSheet->setCellValue('F15','修读时间');
    $objActSheet->setCellValue('G15','所获学历');

    $objActSheet->setCellValue('B19','公司名称');
    $objActSheet->setCellValue('C19','公司地址');
    $objActSheet->setCellValue('D19','公司电话');
    $objActSheet->setCellValue('E19','工作年期');
    $objActSheet->setCellValue('F19','入职时间');
    $objActSheet->setCellValue('G19','职位');

    $obj->getActiveSheet()->mergeCells('A15:A18'); 
    $obj->getActiveSheet()->mergeCells('A19:A22');
    $obj->getActiveSheet()->mergeCells('B1:G1');
    $obj->getActiveSheet()->mergeCells('B2:G2');
    $obj->getActiveSheet()->mergeCells('B3:G3');
    $obj->getActiveSheet()->mergeCells('B4:G4');
    $obj->getActiveSheet()->mergeCells('B5:G5');
    $obj->getActiveSheet()->mergeCells('B6:G6');
    $obj->getActiveSheet()->mergeCells('B7:G7');
    $obj->getActiveSheet()->mergeCells('B8:G8');
    $obj->getActiveSheet()->mergeCells('B9:G9');
    $obj->getActiveSheet()->mergeCells('B10:G10');
    $obj->getActiveSheet()->mergeCells('B11:G11');
    $obj->getActiveSheet()->mergeCells('B12:G12');
    $obj->getActiveSheet()->mergeCells('B13:G13');
    $obj->getActiveSheet()->mergeCells('B14:G14');
    $obj->getActiveSheet()->mergeCells('B23:G23');
    $obj->getActiveSheet()->mergeCells('B24:G24');
    $obj->getActiveSheet()->mergeCells('B25:G25');
    $obj->getActiveSheet()->mergeCells('B26:G26');
    $obj->getActiveSheet()->mergeCells('B27:G27');
    $obj->getActiveSheet()->mergeCells('B28:G28');
    $obj->getActiveSheet()->mergeCells('B29:G29');
    $obj->getActiveSheet()->mergeCells('B30:G30');
    $obj->getActiveSheet()->mergeCells('B31:G31');
    $obj->getActiveSheet()->mergeCells('B32:G32');
    $obj->getActiveSheet()->mergeCells('B33:G33');
    $obj->getActiveSheet()->mergeCells('B34:G34');
    $obj->getActiveSheet()->mergeCells('B35:G35');
    $obj->getActiveSheet()->mergeCells('B36:G36');
    $obj->getActiveSheet()->mergeCells('B37:G37');
    $obj->getActiveSheet()->mergeCells('B38:G38');
    $obj->getActiveSheet()->mergeCells('B39:G39');
    $obj->getActiveSheet()->mergeCells('B40:G40');
    $obj->getActiveSheet()->mergeCells('B41:G41');
    $obj->getActiveSheet()->mergeCells('B42:G42');
    $obj->getActiveSheet()->mergeCells('B43:G43');



    $objActSheet->setCellValue('B1',$customer['id']);  
    $objActSheet->setCellValue('B2',$customer['name']);  
    $objActSheet->setCellValue('B3',$customer['engname']);  
    $objActSheet->setCellValue('B4',$customer['sex']); 
    $objActSheet->setCellValue('B5',$customer['data']); 
    $objActSheet->setCellValue('B6',$customer['nation']); 
    $objActSheet->setCellValue('B7',$customer['contry']); 
    $objActSheet->setCellValue('B8',$customer['phone']); 
    $objActSheet->setCellValue('B9',$customer['gphone']); 
    $objActSheet->setCellValue('B10',$customer['id_number']); 
    $objActSheet->setCellValue('B11',$customer['pass_check']); 
    $objActSheet->setCellValue('B12',$customer['gid']); 
    $objActSheet->setCellValue('B13',$customer['n_adress']);
    $objActSheet->setCellValue('B14',$customer['g_adress']);

    //学习经历
    $objActSheet->setCellValue('B16',$customer['school_name'][0]);
    $objActSheet->setCellValue('B17',$customer['school_name'][1]);
    $objActSheet->setCellValue('B18',$customer['school_name'][2]);
    $objActSheet->setCellValue('C16',$customer['school_adress'][0]);
    $objActSheet->setCellValue('C17',$customer['school_adress'][1]);
    $objActSheet->setCellValue('C18',$customer['school_adress'][2]);
    $objActSheet->setCellValue('D16',$customer['school_tel'][0]);
    $objActSheet->setCellValue('D17',$customer['school_tel'][1]);
    $objActSheet->setCellValue('D18',$customer['school_tel'][2]);
    $objActSheet->setCellValue('E16',$customer['school_year'][0]);
    $objActSheet->setCellValue('E17',$customer['school_year'][1]);
    $objActSheet->setCellValue('E18',$customer['school_year'][2]);
    $objActSheet->setCellValue('F16',$customer['school_time'][0]);
    $objActSheet->setCellValue('F17',$customer['school_time'][1]);
    $objActSheet->setCellValue('F18',$customer['school_time'][2]);
    $objActSheet->setCellValue('G16',$customer['education'][0]);
    $objActSheet->setCellValue('G17',$customer['education'][1]);
    $objActSheet->setCellValue('G18',$customer['education'][2]);
    //工作经历
    $objActSheet->setCellValue('B20',$customer['work_name'][0]);
    $objActSheet->setCellValue('B21',$customer['work_name'][1]);
    $objActSheet->setCellValue('B22',$customer['work_name'][2]);
    $objActSheet->setCellValue('C20',$customer['work_adress'][0]);
    $objActSheet->setCellValue('C21',$customer['work_adress'][1]);
    $objActSheet->setCellValue('C22',$customer['work_adress'][2]);
    $objActSheet->setCellValue('D20',$customer['work_tel'][0]);
    $objActSheet->setCellValue('D21',$customer['work_tel'][1]);
    $objActSheet->setCellValue('D22',$customer['work_tel'][2]);
    $objActSheet->setCellValue('E20',$customer['work_year'][0]);
    $objActSheet->setCellValue('E21',$customer['work_year'][1]);
    $objActSheet->setCellValue('E22',$customer['work_year'][2]);
    $objActSheet->setCellValue('F20',$customer['work_time'][0]);
    $objActSheet->setCellValue('F21',$customer['work_time'][1]);
    $objActSheet->setCellValue('F22',$customer['work_time'][2]);
    $objActSheet->setCellValue('G20',$customer['position'][0]);
    $objActSheet->setCellValue('G21',$customer['position'][1]);
    $objActSheet->setCellValue('G22',$customer['position'][2]);

    // //
      $objActSheet->setCellValue('B23',$customer['konw_gd']);
      $objActSheet->setCellValue('B24',$customer['specail']);
      $objActSheet->setCellValue('B25',$customer['health']);
    //插入图片
    
        $i=2;
        foreach($customer['id_cent'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("B26");
            $objDrawing->setWidth(100);
            $objDrawing->setHeight(100);
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
            $objDrawing->setCoordinates("B27");
            $objDrawing->setHeight(100);
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
            $objDrawing->setCoordinates("B28");
            $objDrawing->setHeight(100);
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
            $objDrawing->setCoordinates("B29");
            $objDrawing->setHeight(100);
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
            $objDrawing->setCoordinates("B30");
            $objDrawing->setHeight(100);
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
            $objDrawing->setCoordinates("B31");
            $objDrawing->setHeight(100);
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
            $objDrawing->setCoordinates("B32");
            $objDrawing->setHeight(100);
            $objDrawing->setOffsetX(200);
            $objDrawing->setRotation(200);
            $objDrawing->getShadow()->setVisible(true);
            $objDrawing->getShadow()->setDirection(50);
            $objDrawing->setWorksheet($obj->getActiveSheet());
            $i++;
            }
        }
        //监护人信息
        $objActSheet->setCellValue('B33',$customer['tutor_name']);
        $objActSheet->setCellValue('B34',$customer['tutor_engname']);
        $objActSheet->setCellValue('B35',$customer['tutor_sex']);
        $objActSheet->setCellValue('B36',$customer['tutor_year']);
        $objActSheet->setCellValue('B37',$customer['tutor_id']);
        $objActSheet->setCellValue('B38',$customer['tutor_pass']);
        $objActSheet->setCellValue('B39',$customer['tutor_gid']);
        $objActSheet->setCellValue('B40',$customer['tutor_work']);
        $objActSheet->setCellValue('B41',$customer['relation']);

         $i=2;
        foreach($customer['tutor_cent'] as $v){
        if(!empty($v)){
             $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath(ROOT_PATH.$v);
            /*设置图片高度*/
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates("B42");
            $objDrawing->setHeight(100);
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
            $objDrawing->setCoordinates("B43");
            $objDrawing->setHeight(100);
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
    $filename = $customer['name'].'信息表.xls';  
    ob_end_clean();//清除缓冲区,避免乱码  
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");  
    header('Content-Disposition: attachment;filename='.$filename);  
    header('Cache-Control: max-age=0');  
    $objWriter = PHPExcel_IOFactory::createWriter($obj,'Excel5');  
    $objWriter->save('php://output');  
    exit;   
?>