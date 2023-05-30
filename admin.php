<?php
include_once('Addition.php');
/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/
$login = 'admin';
$pass = '123';
$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $db->prepare("SELECT login, pass FROM admin");
$stmt->execute();
$verify = $stmt->fetch(PDO::FETCH_ASSOC);
// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != $verify['login'] ||
 (!password_verify($_SERVER['PHP_AUTH_PW'], $verify['pass']))){
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
print('Вы успешно авторизовались и видите защищенные паролем данные.');
// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********
if(isset( $_POST['delete'] ) )
{
  echo 'yes, i am';
  $stmt = $db->prepare("DELETE FROM main where AppId = :id");
  $stmt->execute([':id' => $_POST['id']]);
  $stmt = $db->prepare("DELETE FROM main_ab where AppID = :id");
  $stmt->execute([':id' => $_POST['id']]);
}
$stmt = $db->prepare("SELECT AppId, name, email, data, gender, limbs, biog, username FROM main");
$stmt->execute();
$result = $stmt->fetchAll();
$stmt = $db->prepare("SELECT * FROM main_ab");
$stmt->execute();
$res_ab = $stmt->fetchAll();
$stmt = $db->prepare("SELECT * FROM ability_add");
$stmt->execute();
$ability = $stmt->fetchAll();
$stmt = $db->prepare("SELECT COUNT(AppID) as count, AbId from main_ab group by AbId");
$stmt->execute();
$count_ab = $stmt->fetchAll();
?>
<html>
  <head>
  <link rel="stylesheet" href="tablestyle.css" type="text/css">
  </head>
<body>
<table>
<caption>Основная информация</caption>
<th>AppId</th>
<th>name</th>
<th>email</th>
<th>data</th>
<th>gender</th>
<th>limbs</th>
<th>biog</th>
<th>username</th>
<th>link</th>
<?php foreach($result as $res): ?>
<tr> 
<td> <?php echo $res['AppId'];?></td>
<td><?php echo $res['name'];?></td>
<td><?php echo $res['email'];?></td>
<td><?php echo $res['data'];?></td>
<td><?php echo $res['gender'];?></td>
<td><?php echo $res['limbs'];?></td>
<td><?php echo $res['biog'];?></td>
<td><?php echo $res['username'];?></td>
<td><a href = '/backend6/admin_edit.php?AppId=<?php echo $res["AppId"]?>'>link</a></td>
</tr>
<?php endforeach; ?>
</table>

<table>
<caption>Способности</caption>
<th>AppId</th>
<th>AbId</th>
<?php foreach($res_ab as $res): ?>
<tr> 
<td> <?php echo $res['AppID'];?></td>
<td><?php echo $res['AbId'];?></td>
</tr>
<?php endforeach; ?>
</table>

<table>
<caption>Справочник</caption>
<th>AppId</th>
<th>AbId</th>
<?php foreach($ability as $res): ?>
<tr> 
<td> <?php echo $res['AbId'];?></td>
<td><?php echo $res['AbName'];?></td>
</tr>
<?php endforeach; ?>
</table>

<table>
<caption>Статистика</caption>
<th>CountAppId</th>
<th>AbId</th>
<?php foreach($count_ab as $res): ?>
<tr> 
<td> <?php echo $res['count'];?></td>
<td><?php echo $res['AbId'];?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
