<html>
  <head>
  <link rel="stylesheet" href="style.css" type="text/css">
  </head>
<?php 
    $stmt = $db->prepare("SELECT AppId, name, email, data, gender, limbs, biog, username FROM main where AppId = :id");
    $stmt->execute([':id' => $param]);
    $str = $stmt->fetch();
      $stmt = $db->prepare("SELECT * FROM ability_add");
      $stmt->execute();
      $result = $stmt->fetchAll();
    ?>
 <body>
 <div class="wrapper">
    <div class="content">
    <h1><a id="forma"></a>Форма:</h1>
      <form action="admin_edit.php" method="POST">
        <label>Логин:<input type="text" name="login"  value="<?php print $param; ?>" readonly/></label><br/>
        <label>Ваше имя:<input type="text" name="name"placeholder="Введите имя"  value="<?php print $str['name']; ?>" /></label><br/>
        <label>Почта <input type="email" name="email" placeholder="Введите почту" value="<?php print $str['email']; ?>"/></label><br/>
        <label>Дата рождения:<input type="date" name="date" value="<?php print $str['data']; ?>"/></label><br/>
        <p>
          <label> Пол:
            <input type="radio" checked="checked" name="gender" value="Female"/>Женский</label>
          <label><input type="radio" name="gender" value="Male" />Мужской</label><br />
        </p>
        <p>
          Kоличество конечностей:<br/>
          <label><input type="radio" checked="checked" name="limbs" value="2"/>2</label>
          <label><input type="radio" name="limbs" value="4"/>4</label>
          <label><input type="radio" name="limbs" value="6"/>6</label>
          <label><input type="radio" name="limbs" value="8"/>8</label><br/>
        </p>
        <p>
        <label>
          Cверхспособности:<br />
          <select name="abilities[]" multiple="multiple">
            <?php foreach($result as $res) { ?>
              <option value="<?php echo $res['AbId']; ?>"><?php echo $res['AbName']; ?></option>
            <?php } ?>
          </select>
        </label><br />
        </p>
        <p>
        <label>Биография:<br/>
          <textarea name="biog"> <?php print $str['biog']; ?></textarea>
        </label><br />
        </p>
        <a id="bottom"></a><br/>
        <p><input type="submit" name="send" value="Изменить"/></p>
      </form>
   
    <form action="admin.php" method="POST">
          <input type="hidden" name="id"  value="<?php print $param; ?>" readonly/>
          <button type="submit" name ="delete">Удалить</button>
    </form>
    </div> </div>
  </body>
</html>
