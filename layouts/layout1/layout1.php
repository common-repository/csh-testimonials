
<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i&amp;subset=cyrillic,latin-ext,vietnamese" rel="stylesheet">

<div class="cshtm-owl-carousel-l1" id="<?php echo 'l1-'.$l1_index ?>">

	<div class="owl-carousel">
		<?php
		foreach ($all_posts as $key => $value) {
			$content    = get_post_field('post_content', $value);
			$meta_name  = get_post_meta($value, '_cshtm_name', true);
			$meta_desc  = get_post_meta($value, '_cshtm_desc', true);
			$meta_web   = get_post_meta($value, '_cshtm_web', true);
			$avatar_url = get_the_post_thumbnail_url( $value);

			?>
			<div class="cshtm-carousel-content">
				<span class="cshtm-quote">“</span>
				<div class="cshtm-text">
					<p><?php echo esc_html($content); ?></p>
					<span></span>
				</div>

				<div class="cshtm-avatar">
					<img src="<?php echo esc_attr($avatar_url)?>">
				</div>

				<div class="cshtm-desc">
					<h4><?php echo esc_html($meta_name) ?></h4>
					<p><?php echo esc_html($meta_desc) ?></p>
					<p><a href="<?php echo esc_attr($meta_web) ?>"><?php echo esc_html($meta_web) ?></a></p>
				</div>
			</div>
			
			<?php
		}
		?>
		
	</div>

</div>