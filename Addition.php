<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
include_once('Addition.php');
$param = $_GET["AppId"];
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
try {
  $stmt = $db->prepare("UPDATE main SET name = :name, email = :email, data = :data, gender = :gender, limbs = :limbs, biog = :biog WHERE AppId = :id");
  $stmt->execute(array(':name' => $_POST['name'], ':email' => $_POST['email'], ':data' => $_POST['date'],
  ':gender' => $_POST['gender'],':limbs' => $_POST['limbs'],':biog' => $_POST['biog'],':id' => $_POST['login']));
  $stmt = $db->prepare("SELECT AppId from main WHERE AppId = :id");
  $stmt->execute([':id' => $_POST['login']]);
  $res = $stmt->fetch();
  $stmt = $db->prepare("DELETE FROM main_ab where AppID = :id");
  $stmt->execute([':id' => $res['AppId']]);
  $sql = 'INSERT INTO main_ab(AppID, AbId) VALUES(:AppID, :AbId)';
  $stmt = $db->prepare($sql);
  foreach($_POST['abilities'] as $ability)
  {
      $row = [
            'AppID' => $res["AppId"],
            'AbId' =>  $ability
      ];
      $stmt->execute($row); 
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}
header('Location: ?save=1');
