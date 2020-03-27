<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package specia
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( has_post_thumbnail() ) { ?>
	<div class="post_date">
		<span class="date"><?php echo esc_html(get_the_date('j')); ?></span>
		<h6><?php echo esc_html(get_the_date('M')); ?>, <?php echo esc_html(get_the_date('Y')); ?></h6>
	</div>
	<?php } ?>
    <a  href="<?php esc_url(the_permalink()); ?>" class="post-thumbnail" ><?php the_post_thumbnail(); ?></a>
	
    <footer class="entry-footer">
        <span class="byline">
            <span class="author vcard">
				<a class="url fn n" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><i class="fa fa-user"></i>  <?php esc_html(the_author()); ?></a>
            </span>
        </span>

        <?php   $cat_list = get_the_category_list();
            if(!empty($cat_list)) { ?>
        <span class="cat-links">
            <a href="<?php esc_url(the_permalink()); ?>"><i class="fa fa-folder-open"></i><?php the_category(','); ?></a>
        </span>
        <?php } ?>

        <?php if( get_the_tags() ) { ?>
        <span class="tags-links">
            <a href="<?php esc_url(the_permalink()); ?>"><i class="fa fa-tags"></i> <?php the_tags('', ', ', ''); ?></a>
        </span>
        <?php } ?>

    </footer><!-- .entry-footer -->

    <header class="entry-header">
        <?php     
            if ( is_single() ) :
            
            the_title('<h2 class="entry-title">', '</h2>' );
            
            else:
            
            the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
            
            endif; 
        ?> 
    </header><!-- .entry-header -->

    <div class="entry-content">
       <?php 
			the_content( 
				sprintf( 
					__( 'Read More', 'specia' ), 
					'<span class="screen-reader-text">  '.esc_html(get_the_title()).'</span>' 
				) 
			);
		?>
    </div><!-- .entry-content -->

</article>
