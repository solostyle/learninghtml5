<section id="blog">

<?php 
	if ($isAjax) {
		session_start();
	}
	if (isset($tag)) {
		echo '<h3>Entries tagged with '.$tag['tag_nm'].':</h3>';
	}
?>

<?php if (isset($totalPages) && $totalPages>1):?>
<nav>
	<ul>
	<li style="display:inline;padding:0 2px 0 3px;">
	<?php if ($currentPageNumber>1) echo $html->link('<< prev',$url.'/page/'.($currentPageNumber-1))?></li>
	<?php for ($i = 1; $i <= $totalPages; $i++):?>
	<li style="display:inline;padding:0 2px 0 3px;">
	<?php if ($i == $currentPageNumber):?>
	<?php echo $currentPageNumber?>
	<?php else: ?>
	<?php echo $html->link($i,$url.'/page/'.$i)?>
	<?php endif?>
	</li>
	<?php endfor?>
	<?php if ($currentPageNumber<$totalPages) echo $html->link('next >>',$url.'/page/'.($currentPageNumber+1))?></li>
	</ul>
</nav>
<?php endif?>

<ol class="articles-list"> <!-- ordered list of articles, right? -->
<?php foreach ($blog as $entry):?>
<li>

<?php 
	# set some php variables used in displaying the article
    require_once (ROOT . DS . '235' . DS . 'presentfunc.php');
    $e = stripslashes($entry['Entry']['entry']);
    $ttl = stripslashes($entry['Entry']['title']);
    $l = make_url($entry['Entry']['id']);
    $date = parse_date($entry['Entry']['time']);
    $time = parse_time($entry['Entry']['time']);
	$tags = array();
	$tagInfo = (count($entry['Tag']) > 0) ? $entry['Tag'] : '';
	if ($tagInfo) {
		foreach ($tagInfo as $tagInfo) {
			$tags[] = make_link($tagInfo['Tag']['tag_nm'], make_url('tag/' . $tagInfo['Tag']['tag_nm']));
		}
	}
	$cat = str_replace(" ", "_", $entry['Entry']['category']); // make it a kosher URL piece
	$c = make_link($entry['Entry']['category'], make_url('category/' . $cat));  // when saving new category, do it in the javascript
?>

    <article class="entry" id="entry_<?php echo $entry['Entry']['id']?>">
        <header>
            <!--allow editing of title only if logged in-->
            <?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="edit-title_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?>
			<h1 id="entry-title_<?php echo $entry['Entry']['id']?>"><?php echo $ttl?></h1>
        </header>
		
        <footer class="info">
			<ul>
            <li><time pubdate="pubdate" datetime="<?php echo $entry['Entry']['time']?>"><?php echo $date?></time> at <?php echo $time?></li>
            <!--<p><a href="#">0 comments</a> so far</p>-->
            <li><a href="<?php echo $l?>">Permalink</a></li>
			<?php if ($tags):?>
                <li>Tags: <?php foreach ($tags as $tag) echo $tag.' '?></li>
            <?php endif; ?>
            
			<li>
			<?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="edit-category_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?> Categorized under <span id="entry-category_<?php echo $entry['Entry']['id']?>"><?php echo $c?></span></li>

            <?php if (isset($_SESSION['logged_in'])):?>
                <li><a id="delete-entry_<?php echo $entry['Entry']['id']?>">Delete</a></li>
            <?php endif; ?>
			</ul>
        </footer><!-- end .info -->
		
        <div class="article-content">
            <!--allow editing of entry only if logged in-->
            <?php if (isset($_SESSION['logged_in'])):?>
                <div class="entryEditButton" id="edit-entry_<?php echo $entry['Entry']['id']?>">Edit</div>
            <?php endif; ?>

            <div id="entry-entry_<?php echo $entry['Entry']['id']?>"><?php echo $e?></div>
            
            <!-- <p>
             <em><a name="bot" href="http://iam.solostyle.net/comment.php">comment</a></em>
            </p> -->
        </div><!-- end .article-content -->
		
    </article><!-- end .entry -->

</li>
<?php endforeach?>
</ol> <!-- end ordered list of articles -->

</section><!-- end #blog -->