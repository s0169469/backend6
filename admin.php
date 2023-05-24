<?php
if($_SERVER['REQUEST_METHOD']=='GET'){
  $user = 'u51489';
  $pass = '7565858';
  $db = new PDO('mysql:host=localhost;dbname=u51489', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  
  $pass_hash=array();
  try{
    $get=$db->prepare("select pass from admin where user=?");
    $get->execute(array('admin'));
    $pass_hash=$get->fetchAll()[0][0];
  }
  catch(PDOException $e){
    print('Error: '.$e->getMessage());
  }
  //аутентификация
  if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      $_SERVER['PHP_AUTH_USER'] != 'admin' ||
      md5($_SERVER['PHP_AUTH_PW']) != $pass_hash) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
  }
  if(!empty($_COOKIE['del'])){
    echo 'Пользователь '.$_COOKIE['del_user'].' был удалён <br>';
    setcookie('del','');
    setcookie('del_user','');
  }
  print('Вы успешно авторизовались и видите защищенные паролем данные');
  $users=array();
  $pwrs=array();
  $pwr_def=array('1','2','3','4');
  $pwrs_count=array();
  try{
    $get=$db->prepare("select * from application");
    $get->execute();
    $inf=$get->fetchALL();
    $get2=$db->prepare("select application_id, ability_id from ability_application");
    $get2->execute();
    $inf2=$get2->fetchALL();
    $count=$db->prepare("select count(*) from ability_application where ability_id=?");
    foreach($pwr_def as $pw){
      $i=0;
      $count->execute(array($pw));
      $pwrs_count[]=$count->fetchAll()[$i][0];
      $i++;
    }
  }
  catch(PDOException $e){
    print('Error: '.$e->getMessage());
    exit();
  }
  $users=$inf;
  $pwrs=$inf2;
  include('tab.php');
}
