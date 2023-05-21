<?php
/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/
// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
include_once('Addition.php');
// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  header('Location: ./');
}
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
<form action="login.php" method="post">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />
</form>
<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  $stmt = $db->prepare("SELECT * FROM main WHERE `username` = :username");
  $stmt->execute(['username' => $_POST['login']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$stmt->rowCount()) {
    printf('Пользователь с такими данными не зарегистрирован');
  }
  else if(!password_verify($_POST['pass'], $user['password'])) {
    printf('Пароль неверен');
  }
  else {
    $_SESSION['login'] = $_POST['login'];
    header('Location: ./');
  }
}

