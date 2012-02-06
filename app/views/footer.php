</div><!-- end #right -->
</div><!-- end #content -->

<footer>
	<div class="block">
		<!-- <p><img src="<?php echo BASE_PATH.DS.'img/me.jpg'?>" /></p> -->
		<p style="text-align:center"><img src="<?php echo BASE_PATH.DS.'img/me_blurry.jpg'?>" height="240px" /></p>
	</div>
	<div class="block">
		<h2>About</h2>
		<p>I'm Archana. This is my blog about Learn HTML5, JavaScript (jQuery included), CSS3, and PHP-based MVC frameworks. As I update my knowledge of new web technology I will share my discoveries and get excited about things.</p>
	</div>
	<div class="block hidden">
		<h2>Recent</h2>
		
	</div>
	<div class="block">
		<h2>Feeds</h2>
		<p>meditations: <a href="<?php echo BASE_PATH.DS.'rss.xml'?>">RSS</a> | <a href="<?php echo BASE_PATH.DS.'atom.xml'?>">Atom</a></p>
		<p>last.fm: <a href="http://ws.audioscrobbler.com/1.0/user/solostyle/recenttracks.rss">RSS</a></p>
	</div>
	<div class="clear"><br></div>
</footer><!-- end #footer -->

</div><!-- end #page -->

<?php echo $html->includeJs('yui28yahoo');?>
<?php echo $html->includeJs('yui28event');?>
<?php echo $html->includeJs('yui28connection');?>
<?php echo $html->includeJs('yui28dom');?>
<?php echo $html->includeJs('learn');?>
<?php echo $html->includeJs('learn.blog');?>
<?php echo $html->includeJs('learn.nav');?>
<?php echo $html->includeJs('learn.admin');?>
<?php echo $html->includeJs('learn.objects');?>

<script type="text/javascript">
Learn.Admin.Load();
Learn.Nav.Load();
Learn.Objects.Categories = <?php echo json_encode($cats); ?>;
Learn.Objects.Session = <?php echo json_encode($_SESSION); ?>;
Learn.Objects.Blog = <?php echo json_encode($blog); ?>;
</script>

</body>
</html>
