<!DOCTYPE html>
<html>
<head>
<title>Palmer</title>
</head>
<body>
<header>
<h1>One Image</h1>
</header>
<main>
<h1>Login</h1>

<form action="." method="post" id="login_form" class="aligned">
<input type="hidden" name="action" value="login">

<label>Username:</label>
<input type="text" class="text" name="username">
<br>

<label>Password:</label>
<input type="password" class="text" name="password">
<br>

<label>&nbsp;</label>
<input type="submit" value="Login">
</form>

<p><?php echo $login_message; ?></p>
        </main>
    </body>
</html>
