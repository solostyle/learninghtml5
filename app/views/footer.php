</section><!-- end #right -->
</div><!-- end #main-container -->

<div id="footer-container">
	<footer class="wrapper">
		<div class="footer-block">
			<h2>About</h2>
			<p>I'm Archana. This is my blog about HTML5, JavaScript (jQuery included), CSS3, and PHP-based MVC frameworks. As I update my knowledge of new web technology I share my discoveries and get excited about things.</p>
		</div>
		<div class="footer-block hidden">
			<h2>Recent</h2>
			
		</div>
		<div class="footer-block">
			<h2>Good Sites</h2>
			<ul>
				<li><a href="http://www.uxmatters.com/index.php"><img src="http://www.google.com/s2/u/0/favicons?domain=uxmatters.com"/> UX Matters</a></l>
				<li><a href="http://goodexperience.com/"><img src="http://www.google.com/s2/u/0/favicons?domain=goodexperience.com"/> Good Experience</a></l>
				<li><a href="http://www.quackit.com/"><img src="http://www.google.com/s2/u/0/favicons?domain=quackit.com"/> Web Tutorials</a></l>
				<li><a href="http://www.learningjquery.com/"><img src="http://www.google.com/s2/u/0/favicons?domain=learningjquery.com"/> Learn jQuery</a></l>
				<li><a href="http://visualjquery.com/"><img src="http://www.google.com/s2/u/0/favicons?domain=visualjquery.com"/> Visual jQuery</a></l>
				<li><a href="http://jsfiddle.net/"><img src="http://www.google.com/s2/u/0/favicons?domain=jsfiddle.net"/> JS Fiddle</a></l>
				<li><a href="http://www.fontsquirrel.com/fontface/generator"><img src="http://www.google.com/s2/u/0/favicons?domain=fontsquirrel.com"/> Font Squirrel</a></l>
			</ul>
		</div>
		<div class="footer-block">
			<h2>Feeds</h2>
			<p>meditations: <a href="<?php echo BASE_PATH.DS.'rss.xml'?>">RSS</a> | <a href="<?php echo BASE_PATH.DS.'atom.xml'?>">Atom</a></p>
			<p>last.fm: <a href="http://ws.audioscrobbler.com/1.0/user/solostyle/recenttracks.rss">RSS</a></p>
		</div>
		<div class="clear"><br></div>
	</footer><!-- end .wrapper -->
</div><!-- end #footer-container -->

<?php echo $html->includeJs('yui28yahoo');
 echo $html->includeJs('yui28event');
 echo $html->includeJs('yui28connection');
 echo $html->includeJs('yui28dom');
 echo $html->includeJs('learn');
 echo $html->includeJs('learn.blog');
 echo $html->includeJs('learn.nav');
 echo $html->includeJs('learn.admin');
 echo $html->includeJs('learn.objects');?>

<script type="text/javascript">
Learn.Admin.Load();
Learn.Nav.Load();
Learn.Objects.Categories = <?php echo json_encode($cats); ?>;
Learn.Objects.Session = <?php echo json_encode($_SESSION); ?>;
Learn.Objects.Blog = <?php echo json_encode($blog); ?>;
</script>

</body>
</html>
