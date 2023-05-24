<style>
body{
    background-color: rgba(143, 189, 234, 0.6);
    display: flex;
    justify-content:center;
    margin-top:5%;
    margin-bottom:5%;
}
  .form1{
    max-width: 960px;
    text-align: left;
    margin: 0 auto;
  }
  .error {
    border: 3px solid red;
  }
  .hidden{
    display: none;
  }
  .table1{
    border: 1 solid collapse;
  }
</style>
<body>
  <div class="table1">
    <table >
      <tr>
        <th>Name</th>
        <th>EMail</th>
        <th>Year</th>
        <th>Gender</th>
        <th>Limbs</th>
        <th>Superpowers</th>
        <th>Bio</th>
      </tr>
      <?php
      foreach($users as $user){
          echo '
            <tr>
              <td>'.$user['name'].'</td>
              <td>'.$user['email'].'</td>
              <td>'.$user['year'].'</td>
              <td>'.$user['gender'].'</td>
              <td>'.$user['limbs'].'</td>
              <td>';
                $user_pwrs=array(
                    "1"=>FALSE,
                    "2"=>FALSE,
                    "3"=>FALSE,
                    "4"=>FALSE,
                );
                foreach($pwrs as $pwr){
                    if($pwr['application_id']==$user['id']){
                        if($pwr['ability_id']=='1'){
                            $user_pwrs['1']=TRUE;
                        }
                        if($pwr['ability_id']=='2'){
                            $user_pwrs['2']=TRUE;
                        }
                        if($pwr['ability_id']=='3'){
                            $user_pwrs['3']=TRUE;
                        }
                        if($pwr['ability_id']=='4'){
                            $user_pwrs['4']=TRUE;
                        }                      
                    }
                }
                if($user_pwrs['1']){echo 'none<br>';}
                if($user_pwrs['2']){echo 'бессмертие<br>';}
                if($user_pwrs['3']){echo 'невидимость<br>';}
                if($user_pwrs['4']){echo 'левитация<br>';}
              echo '</td>
              <td>'.$user['biography'].'</td>
              <td>
                <form method="get" action="index.php">
                <input name=edit_id value='.$user['id'].' hidden>
                  <input type="submit" value=Edit>
                </form>
              </td>
            </tr>';
       }
      ?>
    </table>
    <?php
    printf('Users with none: %d <br>',$pwrs_count[0]);
    printf('Users with бессмертие: %d <br>',$pwrs_count[1]);
    printf('Users with невидимость: %d <br>',$pwrs_count[2]);
    printf('Users with левитация: %d <br>',$pwrs_count[3]);
    ?>
  </div>
</body>
