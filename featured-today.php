<?php 
/**
 * Plugin Name: Featured Today
 * Description: Shows featured posts like linkedin today from selected category. This plugin was brought to you by WPoid team.
 * Author: 		WPoid( Nilam patel )
 * Plugin URI: 	http://wpoid.com
 * Version: 	1.0.1
 * =======================================================================
 */

add_action('widgets_init', create_function('', 'return register_widget("Linkedin_Today");'));

/* ------------------------------------------------------------------------*
 * Stylesheets
 * ------------------------------------------------------------------------*/	
	wp_enqueue_style('sample css', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/css/featured.css');
	wp_enqueue_script('jquery', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/js/jquery-1.6.2.min.js');



class Linkedin_Today extends WP_Widget 
{
    function Linkedin_Today() 
	{
        parent::WP_Widget(false, $name = 'Featured Today Widget');
    }
	
	function form($instance) 
	{
        // outputs the options form on admin
		if(isset($instance['ftodayw_category']))
			$category = esc_attr($instance['ftodayw_category']);
		else
			$category = '';
			
		if(isset($instance['ftodayw_post_limit']))
			$post_limit = esc_attr($instance['ftodayw_post_limit']);
		else	
			$post_limit = '0';
		
		if(isset($instance['ftodayw_widget_title']))		
			$widget_title = esc_attr($instance['ftodayw_widget_title']);
		else	
			$widget_title = '';
		
		$categories = get_categories();
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('ftodayw_widget_title'); ?>"> 
				<?php _e('Dispaly Title:'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('ftodayw_widget_title'); ?>" name="<?php echo $this->get_field_name('ftodayw_widget_title'); ?>" type="text" value="<?php echo $widget_title; ?>" />
				</label>	
			<label for="<?php echo $this->get_field_id('ftodayw_category'); ?>">
				<?php _e('Select Category:'); ?> 					
				</label>
				<select name="<?php echo $this->get_field_name('ftodayw_category'); ?>" id="<?php echo $this->get_field_id('ftodayw_category'); ?>">					
					<option value=''></option>
					<?php
						foreach($categories as $cats)
						{
							if($category == $cats)
								$select = "selected=selected";
							else
								$select = "";
							?>
							<option value='<?php echo $cats->cat_ID; ?>' <?php echo $select; ?>><?php echo $cats->cat_name; ?></option>
							<?php
						}
					?>
				</select>
				<br>
			<label for="<?php echo $this->get_field_id('ftodayw_post_limit'); ?>"> 
				<?php _e('Number Of Posts to Show:'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('ftodayw_post_limit'); ?>" name="<?php echo $this->get_field_name('ftodayw_post_limit'); ?>" type="text" value="<?php echo $post_limit; ?>" />
				</label>				
				
		</p>
		<?php
    }
	function update($new_instance, $old_instance) 
	{
        // processes widget options to be saved
        return $new_instance;
    }
	function widget($args, $instance) 
	{
		?>
		<script type="text/javascript">
			jQuery(document).ready( function() {
				jQuery('#linkdin-today li').mouseover(function() {
					jQuery(this).children('a.article-link').children('div.text').children('div.image').children('div.image-offset').css('top','40px');
				}).mouseout(function() {
					jQuery(this).children('a.article-link').children('div.text').children('div.image').children('div.image-offset').css('top','0px');
				});
			});
		</script>
		<?php
		extract( $args );
		$ftodayw_category = apply_filters('ftodayw_category', $instance['ftodayw_category']);
		$post_limit = apply_filters('ftodayw_post_limit', $instance['ftodayw_post_limit']);		
		$widget_title = apply_filters('ftodayw_widget_title', $instance['ftodayw_widget_title']);		

		
		$posts = query_posts('cat='.$ftodayw_category.'&posts_per_page='.$post_limit);
		$html = "";
		$count = 1;
		$class=sanitize_title($widget_title);
			
		$html .='<div id="linkedin-today"><div class="module-content"><div class="header"><h2><a title="Go to LinkedIn Today" href="">'.$widget_title.'</a></h2></div>';
		$html .='<div class="content"><ul id="linkdin-today">';
		foreach($posts as $post)
		{
			$comments = $post->comment_count;
			$html .= '<li><a href="'.get_permalink($post->ID).'" class="article-link"><div class="text">'.$post->post_title;
			$html .= '<div class="image"><div class="image-offset">'.get_the_post_thumbnail($post->ID,array(176,109)).'</div></div></div></a>';
			if($comments==0) 
			{
				continue; 
			} 
			else { 
				$html .= '<div class="share-ribbon"><a href="" title="" id="yui-gen9"><em>'.$comments.' &#187;</em></a><span class="arrow"></span>';
			}
			$html .= '</div></li>';
		}		
		$html .='</ul></div>';
		$html .='</div></div>';
		echo $html;		
    }
}