<?php 
define('WP_USE_THEMES', false);
require_once('/srv/data/web/vhosts/siphonophore.org/htdocs/blog/wp-blog-header.php');

$args=array(
	'category'		=>2,
	'numberposts'	=>10,
	'order'			=>'DESC',
	'orderby'		=>'post_date');

$posts = get_posts($args);
foreach($posts as $post)
{
	setup_postdata($post);
	//$post=apply_filters('the_content' ,$post);
?>
<div style="width:600px;margin-left:auto;margin-right:auto;margin-top:2rem">
	<large><strong><?php the_date(); ?></strong></large>
    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<div style="background-color:#f8f8f8;padding:1rem">
		<?php the_content(); ?> 
	</div>
</div>
<?php
}
?>