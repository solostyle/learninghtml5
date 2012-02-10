<?php
ob_start(); // keeping in case something is outputted before header() is called
session_start();


if (($_SERVER['REQUEST_URI']) == '/members/log_out') {

    session_unset();
    session_destroy();
    $_SESSION = array();


    // If its desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
    }

    $back = (isset($_SERVER['HTTP_REFERER']))? htmlspecialchars($_SERVER['HTTP_REFERER']) : make_url('');
    header("Location: ".$back);  // won't work after <html>
}

if(isset($_POST['login_submit'])) {

    // check to see if username and password have been entered
    if (!$_POST['username']) echo "enter a username. \n";
    else $u_login = mysql_real_escape_string($_POST['username']);
    if (!$_POST['password']) echo "enter a password. \n";
    else $p_login = mysql_real_escape_string($_POST['password']);
    
    if ($u_login && $p_login) {
        select_db();
        $q = "SELECT `user_id`, `username`, `last` FROM `users` WHERE `username`='$u_login' AND `password`= MD5('$p_login')";
        $result = mysql_query($q);

        if (mysql_num_rows($result)>0) { // a match was made
            // start session
            session_regenerate_id();
            $user=mysql_fetch_assoc($result);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']=$user['user_id'];
            $_SESSION['username']=$user['username'];
            $_SESSION['last_last']= $last_last = $user['last'];
            session_write_close();
            // save the time logged in as LAST, and previous last as LAST LAST
            $now  = my_time();
            $now_f = strftime('%G.%m.%d %H:%M',$now);
            $update_lasts = "UPDATE `users` SET `last` = '$now_f', `last_last` = '$last_last' WHERE `username` = '$u_login'";
            mysql_query($update_lasts);

            header("Location: http://" . HOST . $_SERVER['REQUEST_URI']);  // won't work after <html>

        } else {
        // no match was made
        echo 'user does not exist, or bad password';
        }

    }
    else // one of the data tests failed
        echo 'technical problem. try again.';
}
?>
<!DOCTYPE html>
<html class="no-js"> <!-- for modernizr to replace if there is js -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Learn html5 : html5.solostyle.net</title>
	<meta name="description" content="Learn html5, Learn, html5, web development, front-end, mvc, model-view-controller, javascript, jquery, css3, css, html">
	<meta name="author" content="solostyle">
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<?php $html = new HTML();
	echo $html->includeCss('style');
	echo $html->includeCss('layout');
	echo $html->includeCss('format');
	echo $html->includeJs('modernizr-2.0.6.min');?>
</head>
<body>
	<div id="header-container">
		<header class="wrapper clearfix">
			<h1 id="title"><a href="/">Learn HTML5</a></h1>
			<h2 id="subtitle">including JavaScript, CSS3, and PHP-based MVC frameworks</h2>
		
			<nav id="categories">
				<ul><?php 
				select_db();
				$cats = rtrv_categories();
				foreach ($cats as $c) {
					$link = str_replace(" ", "_", $c);
					echo make_list_item(make_link($c, make_url('category/'.$link)));
				}
				mysql_close();
					?>
				</ul>
			</nav>

			<div id="login-toggle" onmouseup="Ydom.get('login').style.display = (Ydom.get('login').style.display=='none')? 'block' : 'none';"><?php if (isset($_SESSION['logged_in'])):?>Funcs<?php else:?>Login<?php endif;?></div>

			<div id="login" style="display:none">
				<?php if (isset($_SESSION['logged_in']) AND substr($_SERVER['REQUEST_URI'],-8) != 'log_out'): ?>
					<ul><?php 
						$adminFuncs = array('publish_feeds' => 'publish feeds',
										'tag_entries' => 'tag entries',
										'categorize_entries' => 'categorize entries');
						foreach ($adminFuncs as $link => $name) {
							echo make_list_item(make_link($name, make_url('admin/'.$link)));
						}
						?>
					</ul>
					<ul>
					<?php
						$loginFuncs = array('change_pw' => 'change password',
										'login_woe' => 'login woe?',
										'log_out' => 'log out');
						foreach ($loginFuncs as $link => $name) {
							echo make_list_item(make_link($name, make_url('members/'.$link)));
						}
					?>
					</ul>

				<?php else: ?>

					<ul>
						<form action="<?php echo make_url(substr($_SERVER['REQUEST_URI'], 1))?>" method="post">
						<li>Name: <input type="text" size="8" name="username" tabindex="1" /> </li>
						<li>Pass: <input type="password" size="7" name="password" tabindex="2" /> </li>
						<li><input type="submit" name="login_submit" value="Log in" tabindex="3" /> </li>
						</form>
					</ul>

				<?php endif; ?>

			</div><!-- end div#login -->
		</header>
	</div><!-- end #header-container -->
	
	
    <div id="main" class="wrapper clearfix"> 
        <aside>
            <?php
				echo performAction('nav','index',true);
            ?>

        </aside><!-- end aside -->

	<?php if (isset($_SESSION['logged_in'])): ?>
    <section id="admin">
		<button type="button" id="new-article-button">New Article</button>
        <form id="add-article-form">
			<fieldset>
				<legend>Write the Article</legend>
				<ol>
					<li>
						<input type="text" value="article title" id="add-article-form-title"/>
					</li>
					<li>
						<textarea id="add-article-form-entry" rows="10">article text</textarea>
					</li>
				</ol>
			</fieldset>
			<fieldset>
				<legend>Choose a Category</legend>
				<ol>
					<?php 
							select_db();
							$cats = rtrv_categories();
							mysql_close();
							foreach ($cats as $cat):?>
					<li>
						<input type="radio" name="category" id="add-article-form-category_<?php echo $cat?>" value="<?php echo $cat?>" />
						<label for="add-article-form-category_<?php echo $cat?>"><?php echo $cat?></label>
					</li>
					<?php endforeach;?>
				</ol>
			</fieldset>
			<fieldset>
				<legend>Verify Time and Date</legend>
			<!-- the date and time, empty, filled in with javascript -->
                <ol>
					<li>
                        <input type="text" id="add-article-form-year" name="add-article-form-year" size="3" maxlength="4" value="" />
                        <input type="text" id="add-article-form-month" name="add-article-form-month" size="1" maxlength="2" value="" />
                        <input type="text" id="add-article-form-date" name="add-article-form-date" size="1" maxlength="2" value="" />
                        <input type="text" id="add-article-form-hour" name="add-article-form-hour" size="1" maxlength="2" value="" />
                        <input type="text" id="add-article-form-minute" name="add-article-form-minute" size="1" maxlength="2" value="" />
                    </li>
                    <li>
                        <button type="button" id="add-article-form-change-time">Set Time</button>
                    </li>
                    <li>
                        <label id="add-article-form-time"><label>
                    </li>
				</ol>
			</fieldset>
			<fieldset>
				<button type="submit" id="add-article-form-submit">Add it!</button>
            </fieldset>
        </form><!-- end #blog-add-form -->
	</section>
	<?php endif; ?>