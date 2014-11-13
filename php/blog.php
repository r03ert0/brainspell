<?php
$json=file_get_contents("http://siphonophore.org/blog/wp-json/posts?filter[category_name]=brainspell");
$posts=json_decode($json);
?>

<style>
.wp-caption-text {
	font-family: Sans-Serif;
}
.aligncenter {
	margin-left:auto;
	margin-right:auto;
}
p a {
	color: #ed8000;
}
</style>

<?php
foreach($posts as $post)
{
?>

<div style="width:600px;margin-left:auto;margin-right:auto;margin-top:2rem">
	<large><strong><?php echo $post->date_gmt; ?></strong></large>

    <h2 class="blog"><a href="<?php echo $post->link; ?>" rel="bookmark" title="Permanent Link to <?php echo $post->title; ?>"><?php echo $post->title; ?></a></h2>
	<div style="background-color:#f8f8f8;padding:1rem">
		<?php echo $post->content; ?> 
	</div>
</div>

<?php
}
?>