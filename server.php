<?php
function table($session, $name, $error){
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$html = '';
	$query = mysqli_query($db, "SELECT * FROM polycheno ORDER BY name_bloknota, nomer_bloknota, nomer_ekzemp");
	while($row = mysqli_fetch_array($query)){
		$data_yn = $row['data_yn'];
		$data_don = $row['data_don'];
		if($row['data_yn'] == '0000-00-00'){
			$data_yn = '';
		}
		if($row['data_don'] == '0000-00-00'){
			$data_don = '';
		}
		$html.="<tr class='tr'><td>".$row['data_i_nomer1']."</td>";
		$html.="<td>".$row['ot_kogo']."</td>";
		$html.="<td>".$row['name_bloknota']."</td>";
		$html.="<td>".$row['nomer_bloknota']."</td>";
		$html.="<td>".$row['nomer_ekzemp']."</td>";
		$html.="<td>".$row['kod']."</td>";
		$html.="<td>".$row['data_i_nomer_polychenii']."</td>";
		$html.="<td>".$row['data_i_nomer2']."</td>";
		$html.="<td>".$row['komy']."</td>";
		$html.="<td>".$row['data_i_nomer_podtv']."</td>";
		$html.="<td>".$data_yn."</td>";
		$html.="<td>".$row['nomer_akta']."</td>";
		$html.="<td>".$data_don."</td>";
		$html.="<td>".$row['nomer_don']."</td></tr>";
	}
	$data = array('response' => "<input type='' id='sort'/><br>
								<input type='submit' value='Сортировка' onclick='sort()'/><br>
								<input type='submit' value='Выйти' onclick='exit()'/>
								<table border=1 cellspacing='0'>	
								<tr>
									<th colspan=7 align=center>Получено</th>
									<th colspan=3 align=center>Отправлено</th>
									<th colspan=2 align=center>Уничтожено</th>
									<th colspan=2 align=center>Донесение об уничтожении в высший орган</th>
									</tr>
								<tr>
									<td>Дата и исходный номер сопроводительного письма</td>
									<td>От кого</td>
									<td>Наименование блокнота</td>
									<td>Номер блокнота</td>
									<td>Номер экземпляра</td>
									<td>Код</td>
									<td>Дата и исходный номер подтверждения о получении</td>
									<td>Дата и исходный номер сопроводительного письма</td>
									<td>Кому</td>
									<td>Дата и исходный номер подтверждения</td>
									<td>Дата</td>
									<td>Входящий или исходящий номер акта</td>
									<td>Дата</td>
									<td>Номер донесения</td>
									</tr>
									$html
							</table>", "sess" => $session, "name" => $name, "error" => $error);
	echo json_encode($data);
	exit;
}

function auth(){
	$login = mysqli_connect("127.0.0.1", "root", "", "kd");
	$row = mysqli_fetch_array(mysqli_query($login, "SELECT login FROM users WHERE login = 'admin'"));
	if($row['login'] != 'admin'){
		$html = '<div id="form">
		  <fieldset>
		    <legend>Создание администратора</legend>
		    <p><label for="login">Логин </label><input type="text" id="login"></p>
			<p><label for="pswd">Пароль </label><input type="text" id="pswd"></p>
			<p><label for="name">Имя </label><input type="text" id="name"></p>
		  </fieldset>
		<p><input type="submit" value="Добавить" onclick="add_admin()"></p>
		</div>';
		echo json_encode(array('response' => $html, 'error' => 'no'));
		exit;
	}
	$session=$_POST['session'];
	if($session == 'undefined'){
		echo json_encode(array('response' => "<div>
								<p id='error' align = center></p>
								<p align = center><input id = 'login' type = 'text' name = 'login'>
								<p align = center><input id = 'pswd' type = 'password' name = 'pswd'>
								<p align = center><input type = 'submit' onclick='login()' value = 'Войти'>
								</div>"));
		exit;
	}
	$row = mysqli_fetch_array(mysqli_query($login, "SELECT * FROM users WHERE session = '$session'"));
	if($row['login'] != null){
		if($row['login'] == 'admin')
		{
			$html = "<input type = 'submit' value='Пользователи' onclick='users()'>
					 <input type = 'submit' value='База данных' onclick='db()'>";
			echo json_encode(array('response' => $html, 'sess' => $row['session'], 'name' => 'admin', 'error' => 'no'));
			exit;
		}
		else table($row['session'], $row['name'], 'no');
	}
	else {
		echo json_encode(array('response' => "<div>
								<p id='error' align = center></p>
								<p align = center><input id = 'login' type = 'text' name = 'login'>
								<p align = center><input id = 'pswd' type = 'password' name = 'pswd'>
								<p align = center><input type = 'submit' onclick='login()' value = 'Войти'>
								</div>"));
		exit;
	}
}

function login(){
	$login=$_POST['login'];
	$password = $_POST['password'];
	$log = mysqli_connect("127.0.0.1", "root", "", "kd");
	$row = mysqli_fetch_array(mysqli_query($log, "SELECT * FROM users WHERE login = '$login' AND pswd = '$password'"));
	if($row['login'] != null){
		if($row['login'] == 'admin')
		{
			$html = "<input type = 'submit' value='Пользователи' onclick='users()'>
					 <input type = 'submit' value='База данных' onclick='db()'>";
			echo json_encode(array('response' => $html, 'sess' => $row['session'], 'name' => $row['name'], 'error' => 'no'));
			exit;
		}
		else table($row['session'], 'name', 'no');
	}
	else echo json_encode(array('error' => 'Неверные данные'));
	exit;
}

function sess(){
	session_regenerate_id();
	return session_id();
}

function users_edit($alert){
	$sess = $_POST['sess'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$query = mysqli_query($db, "SELECT * FROM users");
		$html = '';
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$html.="<tr id='$id'><td>".$row['login']."</td>";
			$html.="<td>".$row['pswd']."</td>";
			$html.="<td>".$row['name']."</td>";
			$html.='<td><input type="submit" value="Удалить" onclick="del_user('.$id.')"></td></tr>';
		}
		echo json_encode(array('response' => "<input type='submit' value='Выйти' onclick='exit()'>
									<input type = 'submit' value='Пользователи' onclick='users()'>
					 				<input type = 'submit' value='База данных' onclick='db()'><br>
					 				<input type = 'submit' value='Добавить' onclick='show_add_user()'><br>
					 				<table border=1 cellspacing='0'>	
									<tr>
										<th align=center>Логин</th>
										<th align=center>Пароль</th>
										<th align=center>Имя</th></tr>
									$html", 'error' => 'no', 'alert' => $alert));
		exit;
	}
	else{
		echo json_encode(array('response' => "<div>
									<p id='error' align = center></p>
									<p align = center><input id = 'login' type = 'text' name = 'login'>
									<p align = center><input id = 'pswd' type = 'password' name = 'pswd'>
									<p align = center><input type = 'submit' onclick='login()' value = 'Войти'>
									</div>"));
		exit;
	}
}

function db_edit($alert){
	$sess = $_POST['sess'];
	$data_yn = '';
	$data_don = '';
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$query = mysqli_query($db, "SELECT * FROM polycheno");
		$html = '';
		while($row = mysqli_fetch_array($query)){
					if($row['data_yn'] == '0000-00-00'){
						$data_yn = '';
					}
					if($row['data_don'] == '0000-00-00'){
						$data_don = '';
					}
					$id = $row['id'];
					$html.="<tr><td>".$row['data_i_nomer1']."</td>";
					$html.="<td>".$row['ot_kogo']."</td>";
					$html.="<td>".$row['name_bloknota']."</td>";
					$html.="<td>".$row['nomer_bloknota']."</td>";
					$html.="<td>".$row['nomer_ekzemp']."</td>";
					$html.="<td>".$row['kod']."</td>";
					$html.="<td>".$row['data_i_nomer_polychenii']."</td>";
					$html.="<td>".$row['data_i_nomer2']."</td>";
					$html.="<td>".$row['komy']."</td>";
					$html.="<td>".$row['data_i_nomer_podtv']."</td>";
					$html.="<td>".$data_yn."</td>";
					$html.="<td>".$row['nomer_akta']."</td>";
					$html.="<td>".$data_don."</td>";
					$html.="<td>".$row['nomer_don']."</td>";
					$html.="<td></td>";
					$html.="<td><input type='submit' value='Изменить' onclick='show_mod_db($id)'><br>
					<input type='submit' value='Удалить' onclick='del_db($id)'></td></tr>";
			}
		echo json_encode(array('response' => "<p align=center><input type='submit' value='Выйти' onclick='exit()'>
									<input type = 'submit' value='Пользователи' onclick='users()'>
					 				<input type = 'submit' value='База данных' onclick='db()'><br></p>
					 				<input type = 'submit' value='Добавить' onclick='show_add_db()'><br>
					 				<table border=1 cellspacing='0'>	
									<tr>
										<th colspan=7 align=center>Получено</th>
										<th colspan=3 align=center>Отправлено</th>
										<th colspan=2 align=center>Уничтожено</th>
										<th colspan=2 align=center>Донесение об уничтожении в высший орган</th>
										</tr>
									<tr>
										<td>Дата и исходный номер сопроводительного письма</td>
										<td>От кого</td>
										<td>Наименование блокнота</td>
										<td>Номер блокнота</td>
										<td>Номер экземпляра</td>
										<td>Код</td>
										<td>Дата и исходный номер подтверждения о получении</td>
										<td>Дата и исходный номер сопроводительного письма</td>
										<td>Кому</td>
										<td>Дата и исходный номер подтверждения</td>
										<td>Дата</td>
										<td>Входящий или исходящий номер акта</td>
										<td>Дата</td>
										<td>Номер донесения</td>
										</tr>
									$html", 'error' => 'no', 'alert' => $alert));
		exit;
	}
	else{
		echo json_encode(array('response' => "<div>
									<p id='error' align = center></p>
									<p align = center><input id = 'login' type = 'text' name = 'login'>
									<p align = center><input id = 'pswd' type = 'password' name = 'pswd'>
									<p align = center><input type = 'submit' onclick='login()' value = 'Войти'>
									</div>"));
		exit;
	}
}

function del_db(){
	$sess = $_POST['sess'];
	$id = $_POST['id'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$query = mysqli_query($db, "DELETE FROM polycheno WHERE id = '$id'");
		db_edit('Запись успешно удалена');
	}
}

function show_add_db(){
	$sess = $_POST['sess'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$form = '<div id="form">
				  <fieldset>
				    <legend>Добавление новой информации</legend>
				    <p>Дата и исходный номер сопроводительного письма<input type="text" id="1"></p>
					<p><label for="2">От кого </label><input type="text" id="2"></p>
					<p><label for="3">Наименование блокнота </label><input type="text" id="3"></p>
					<p><label for="4">Номер блокнота </label><input type="text" id="4"></p>
					<p><label for="5">Номер экземпляра </label><input type="text" id="5"></p>
					<p><label for="6">Код </label><input type="text" id="6"></p>
				   </fieldset>
				<p><input type="submit" value="Добавить" onclick="add_db()"></p>
				</div>';
		echo json_encode(array('response' => $form, 'error' => 'no'));
		exit;
	}
	else{
		echo json_encode(array('error' => 'yes'));
		exit;
	}
}

function show_add_user(){
	$sess = $_POST['sess'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$form = '<div id="form">
				  <fieldset>
				    <legend>Создание пользователя</legend>
				    <p><label for="login">Логин </label><input type="text" id="login"></p>
					<p><label for="pswd">Пароль </label><input type="text" id="pswd"></p>
					<p><label for="name">Имя </label><input type="text" id="name"></p>
				  </fieldset>
				<p><input type="submit" value="Добавить" onclick="add_user()"></p>
				</div>';
		echo json_encode(array('response' => $form, 'error' => 'no'));
		exit;
	}
}

function my_split($str){
	$mass = array();
	$len = strlen($str);
	$index = 0;
	$char = '';
	$i = 0;
	$char = $str[0];
	$a = '';
	while($i < $len){
		while($char != ','){
			$a.=$char;
			$i++;
			$char = $str[$i];
		}
		$i++;
		$char = $str[$i];
		$mass[$index] = $a;
		$index++;
		$a = '';
	}
	return $mass;
}

function add_db(){
	$sess = $_POST['sess'];
	$str = $_POST['mass'];
	$mass = array();
	$mass = my_split($str);
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		mysqli_query($db, "INSERT INTO polycheno(data_i_nomer1, ot_kogo,name_bloknota, nomer_bloknota,nomer_ekzemp,kod) VALUES ('$mass[0]', '$mass[1]', '$mass[2]', '$mass[3]', '$mass[4]', '$mass[5]')");
		db_edit('Запись успешно добавлена');
	}
}

function show_mod_db(){
	$sess = $_POST['sess'];
	$id = $_POST['id'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$form = "<div id='form'>
				  <fieldset>
				    <legend>Изменение информации</legend>
					<p><label for='7'>Дата и исходный номер подтверждения о получении</label><input type='text' id='7'></p>
				    <p><label for='8'>Дата и исходный номер сопроводительного письма </label><input type='text' id='8'></p>
					<p><label for='9'>Кому </label><input type='text' id='9'></p>
					<p><label for='10'>Дата и исходный номер подтверждения</label><input type='text' id='10'></p>
					<p><label for='11'>Дата </label><input type='text' id='11'></p>
					<p><label for='12'>Входящий или исходящий номер акта </label><input type='text' id='12'></p>
					<p><label for='13'>Дата </label><input type='text' id='13'></p>
					<p><label for='14'>Номер донесения </label><input type='text' id='14'></p>
				  </fieldset>
				<p><input type='submit' value='Изменить' onclick='mod_db($id)'></p>
				</div>";
		echo json_encode(array('response' => $form, 'error' => 'no'));
		exit;
	}
}

function mod_db(){
	$sess = $_POST['sess'];
	$id = $_POST['id'];
	$mass = array();
	$mass = my_split($_POST['mass']);
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$query = mysqli_query($db, "UPDATE polycheno SET data_i_nomer_polychenii = $mass [0], data_i_nomer2 = $mass[1], komy = $mass[2], data_i_nomer_podtv = $mass[3], data_yn = $mass[4], nomer_akta = $mass[5], data_don = $mass[6], nomer_don = $mass[7] WHERE id = $id");
		db_edit('Запись успешно обновлена');
	}
}

function add_user(){
	$sess = $_POST['sess'];
	$login = $_POST['login'];
	$pswd = $_POST['pswd'];
	$name = $_POST['name'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$sess_user = hash('sha256', $pswd);
		$query = mysqli_query($db, "INSERT INTO `users`(`login`, `name`, `pswd`, `session`) VALUES ('$login','$name','$pswd','$sess_user')");
		users_edit('Пользователь добавлен');
	}
}

function del_user(){
	$sess = $_POST['sess'];
	$id = $_POST['id'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login, id FROM users WHERE session = '$sess'");
	$row = mysqli_fetch_array($query);
	if($row['login'] == 'admin'){
		$query = mysqli_query($db, "DELETE FROM users WHERE id = '$id'");
		if($row['id'] == $id){
			$html = '<div id="form">
					  <fieldset>
					    <legend>Создание администратора</legend>
					    <p><label for="login">Логин </label><input type="text" id="login"></p>
						<p><label for="pswd">Пароль </label><input type="text" id="pswd"></p>
						<p><label for="name">Имя </label><input type="text" id="name"></p>
					  </fieldset>
					<p><input type="submit" value="Добавить" onclick="add_admin()"></p>
					</div>';
			echo json_encode(array('response' => $html, 'error' => 'no', 'alert' => 'Администратор удалён'));
			exit;
		}
		users_edit('Пользователь удалён');
	}
}

function add_admin(){
	$login = $_POST['login'];
	$pswd = $_POST['pswd'];
	$name = $_POST['name'];
	$db = mysqli_connect("127.0.0.1", "root", "", "kd");
	$query = mysqli_query($db, "SELECT login, session FROM users WHERE login = 'admin'");
	if($row['login'] == 'admin'){
		echo json_encode(array('response' => "<div>
								<p id='error' align = center></p>
								<p align = center><input id = 'login' type = 'text' name = 'login'>
								<p align = center><input id = 'pswd' type = 'password' name = 'pswd'>
								<p align = center><input type = 'submit' onclick='login()' value = 'Войти'>
								</div>", 'alert' => 'Администратор существует', 'error' => 'no'));
		exit;
	}
	else{
		$sess_user = hash('sha256', $pswd);
		$query = mysqli_query($db, "INSERT INTO `users`(`login`, `name`, `pswd`, `session`) VALUES ('$login','$name','$pswd','$sess_user')");
		$query = mysqli_query($db, "SELECT * FROM users");
		$html = '';
		$alert = 'Администратор создан';
		$query = mysqli_query($db, "SELECT * FROM users");
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$html.="<tr id='$id'><td>".$row['login']."</td>";
			$html.="<td>".$row['pswd']."</td>";
			$html.="<td>".$row['name']."</td>";
			$html.='<td><input type="submit" value="Удалить" onclick="del_user('.$id.')"></td></tr>';
		}
		echo json_encode(array('response' => "<input type='submit' value='Выйти' onclick='exit()'>
									<input type = 'submit' value='Пользователи' onclick='users()'>
					 				<input type = 'submit' value='База данных' onclick='db()'><br>
					 				<input type = 'submit' value='Добавить' onclick='show_add_user()'><br>
					 				<table border=1 cellspacing='0'>	
									<tr>
										<th align=center>Логин</th>
										<th align=center>Пароль</th>
										<th align=center>Имя</th></tr>
									$html", 'error' => 'no', 'alert' => $alert, 'sess' => $sess_user));
		exit;
	}
}

if($_POST['type'] == "auth")auth();
if($_POST['type'] == "login")login();
if($_POST['type'] == "users")users_edit('');
if($_POST['type'] == "db")db_edit('');
if($_POST['type'] == "add_db")add_db();
if($_POST['type'] == "add_user")add_user();
if($_POST['type'] == "show_add_db")show_add_db();
if($_POST['type'] == "del_db")del_db();
if($_POST['type'] == "show_mod_db")show_mod_db();
if($_POST['type'] == "mod_db")mod_db();
if($_POST['type'] == "show_add_user")show_add_user();
if($_POST['type'] == "del_user")del_user();
if($_POST['type'] == "add_admin")add_admin();

