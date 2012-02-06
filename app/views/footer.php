</section><!-- end #right -->
</div><!-- end #main-container -->

<div id="footer-container">
	<footer class="wrapper"><!-- one -->
		<div class="footer-block first one">
			<p style="text-align:center"><img src="<?php echo BASE_PATH.DS.'img/mybrushes.jpg'?>" height="240px" /></p>
		</div><!-- two -->
		<div class="footer-block two">
			<h2>About</h2>
			<p>I'm Archana. I get excited about the technical and the aesthetic when it comes to web craft. I write about HTML5, JavaScript (jQuery included), CSS3, PHP-based MVC frameworks, and the experience of building and using sites.</p>
		</div><!-- three -->
		<div class="footer-block three">
			<h2>Recent</h2>
			
		</div>
		<div class="clear"><br></div><!-- one -->
		<div class="footer-block first four">
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
		</div><!-- two -->
		<div class="footer-block five">
			<h2>Feeds</h2>
			<p>meditations: <a href="<?php echo BASE_PATH.DS.'rss.xml'?>">RSS</a> | <a href="<?php echo BASE_PATH.DS.'atom.xml'?>">Atom</a></p>
			<p>last.fm: <a href="http://ws.audioscrobbler.com/1.0/user/solostyle/recenttracks.rss">RSS</a></p>
		</div>
		<div class="footer-block six">
			<h2>Place holder</h2>
			
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
