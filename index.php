<?php

$pass_hash=array();$user = 'u51489';
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

if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      $_SERVER['PHP_AUTH_USER'] != 'admin' ||
      md5($_SERVER['PHP_AUTH_PW']) != $pass_hash) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Unauthorized (Требуется авторизация)</h1>');
    exit();
}
if(empty($_GET['edit_id'])){
  header('Location: admin.php');
}

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
    setcookie('fio_value', '', 100000);
    setcookie('email_value', '', 100000);
    setcookie('year_value', '', 100000);
    setcookie('gender_value', '', 100000);
    setcookie('limbs_value', '', 100000);
    setcookie('biography_value', '', 100000);
    setcookie('ability_value', '', 100000);
  }
  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['ability'] = !empty($_COOKIE['ability_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['fio']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя. Допустимые символы: A-Z, a-z, А-Я, а-я, " " .</div>';}
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните email. Пример: "example@example.ex".</div>';}
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Заполните год. Выберете одно поле из списка.</div>';}
  if ($errors['gender']) {
    setcookie('gender_error', '', 100000);
    $messages[] = '<div class="error">Заполните пол. Выберете одно из допустимых значений: "ж","м".</div>';}
  if ($errors['limbs']) {
    setcookie('limbs_error', '', 100000);
    $messages[] = '<div class="error">Заполните количество конечностей. Выберете одно из допустимых значений: "1","2","3","4".</div>';}
  if ($errors['ability']) {
    setcookie('ability_error', '', 100000);
    $messages[] = '<div class="error">Заполните сверхспособности. Выберете одно или несколько полей из списка.</div>';}
  if ($errors['biography']) {
    setcookie('biography_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию. Допустимые значения: 0-9, A-Z, a-z, А-Я, а-я, " ", ".", пробельные символы.</div>';}
 
  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['1'] = 0;
  $values['2'] = 0;
  $values['3'] = 0;
  $values['4'] = 0;

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  
  $user = 'u51489';
  $pass = '7565858';
  $db = new PDO('mysql:host=localhost;dbname=u51489', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  try{
    $id=$_GET['edit_id'];
    $stmt=$db->prepare("SELECT * FROM application WHERE id=?");
    $stmt->bindParam(1,$id);
    $stmt->execute();
    $arr1=$stmt->fetchALL();
    $values['fio']=$arr1[0]['name'];
    $values['email']=$arr1[0]['email'];
    $values['year']=$arr1[0]['year'];
    $values['gender']=$arr1[0]['gender'];
    $values['limbs']=$arr1[0]['limbs'];
    $values['biography']=$arr1[0]['biography'];

    $stmt=$db->prepare("SELECT ability_id FROM ability_application WHERE application_id=?");
    $stmt->bindParam(1,$id); 
    $stmt->execute();
    $inf2=$stmt->fetchALL();
      for($i=0;$i<count($inf2);$i++){
        if($inf2[$i]['ability_id']=='1'){
          $values['1']=1;
        }
        if($inf2[$i]['ability_id']=='2'){
          $values['2']=1;
        }
        if($inf2[$i]['ability_id']=='3'){
          $values['3']=1;
        }
        if($inf2[$i]['ability_id']=='4'){
          $values['4']=1;
        }
      }
  }
  catch(PDOException $e){
    print('Error: '.$e->getMessage());
    exit();
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
  // Завершаем работу скрипта.
}

// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else{  
  if(!empty($_POST['save'])){
  $id=$_POST['dd'];
  $name = $_POST['fio'];
  $email = $_POST['email'];
  $year = $_POST['year'];
  $pol=$_POST['gender'];
  $limbs=$_POST['limbs'];
  $powers=$_POST['ability'];
  $bio=$_POST['biography'];
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['fio']) || !preg_match('/^([a-zA-Zа-яА-Я\s]{1,})$/', $_POST['fio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;}
  else {
  // Сохраняем ранее введенное в форму значение на месяц.
  setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60); }
  if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;}
  else {
  setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);}
  if (empty($_POST['email']) || !preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u',$_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;}
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);}
  if (empty($_POST['gender']) || ($_POST['gender']!='m' && $_POST['gender']!='w')) {
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;}
  else {
    setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);}
  if (empty($_POST['limbs']) || ($_POST['limbs']!='1' && $_POST['limbs']!='2' && $_POST['limbs']!='3' && $_POST['limbs']!='4')) {
    setcookie('limbs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;}
  else {
    setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);}

  if (empty($_POST['biography']) || !preg_match('/^([0-9a-zA-Zа-яА-Я\,\.\s]{1,})$/', $_POST['biography']) ){
    setcookie('biography_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;}
  else {
    setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);}
  foreach ($_POST['ability'] as $ability) {
    if($ability != '1' && $ability != '2' && $ability != '3' && $ability != '4'){
      setcookie('ability_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
      break;}}
  if (!empty($_POST['ability'])) {
    setcookie('ability_value', json_encode($_POST['ability']), time() + 24 * 60 * 60);}


  if ($errors) {
    setcookie('save','',100000);
    header('Location: index.php?edit_id='.$id);
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('biography_error', '', 100000);
    setcookie('ability_error', '', 100000);
  }

  $user = 'u51489';
	$pass = '7565858';	
    $db = new PDO('mysql:host=localhost;dbname=u51489', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!$errors) {
    $upd=$db->prepare("UPDATE application SET name=:name, email=:email, year=:byear, gender=:pol, limbs=:limbs, biography=:bio WHERE id=:id");
    $cols=array(
      ':name'=>$name,
      ':email'=>$email,
      ':byear'=>$year,
      ':pol'=>$pol,
      ':limbs'=>$limbs,
      ':bio'=>$bio
    );
    foreach($cols as $k=>&$v){
      $upd->bindParam($k,$v);
    }
    $upd->bindParam(':id',$id);
    $upd->execute();
    $del=$db->prepare("DELETE FROM ability_application WHERE application_id=?");
    $del->execute(array($id));
    $upd1=$db->prepare("INSERT INTO ability_application SET ability_id=:power, application_id=:id");
    $upd1->bindParam(':id',$id);
    foreach($powers as $pwr){
      $upd1->bindParam(':power',$pwr);
      $upd1->execute();
    }
  }
  if(!$errors){
    setcookie('save', '1');
  }
  header('Location: index.php?edit_id='.$id);
}
  else {
    $id=$_POST['dd'];
    $user = 'u51489';
   $pass = '7565858';	
   $db = new PDO('mysql:host=localhost;dbname=u51489', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

   try {
     $del=$db->prepare("DELETE FROM ability_application WHERE application_id=?");
     $del->execute(array($id));
     $stmt = $db->prepare("DELETE FROM application WHERE id=?");
     $stmt -> execute(array($id));
   }
   catch(PDOException $e){
     print('Error : ' . $e->getMessage());
     exit();
   }
   setcookie('del','1');
   setcookie('del_user',$id);
   header('Location: admin.php');
  }
}
?>
