<?php


get_header(); ?>



<div id="blog-page">

<main>
  <h4>Inlägg med Taggen: <?php
$tag = get_queried_object();
echo  esc_html($tag->name) ;?></h4>

<section id="blog-container">
  <div class="row">
  <div id="primary" class="col-xs-12 col-md-9 offset-md-1">
      <h1><?php single_post_title(); ?></h1>
      <?php
      //starta loopen
      if (have_posts()) :
        while (have_posts()) : the_post(); ?>
          <article class="blogg-post">

            <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <ul class="meta-data">
              <li>
                <i class="fa-solid fa-calendar"></i><?php echo get_the_date(); ?>
              </li>
           </ul>
            <p class="wp-thumbnail">
              <?php
              if (has_post_thumbnail()) {
                the_post_thumbnail('thumbnail', ['alt' => get_the_title()]);
              } else {
                // Hämta första bilden från innehållet
                $content = get_the_content();
                preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
                if (!empty($image['src'])) {
                  echo '<img class="wp-thumbnail" src="' . esc_url($image['src']) . '" alt="' . esc_attr(get_the_title()) . '">';
                }
              }
              ?>
            </p>
            <p><?php the_excerpt(); ?></p>
            <ul class="meta-data">
              <li>
                <i class="fa-solid fa-tag"></i><?php
                $tags = get_the_tags();
                if (!empty ($tags)) {
                  foreach ($tags as $tag) {
                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" rel="tag">' . esc_html($tag->name) . '</a> ';
                  }
                }?>
              </li>
            </ul>

          </article> <?php
                    endwhile; ?>


        <!-- TODO lägg till klasser för knapparna -->
        <nav class="pagination" aria-label="Sidonumrering för inlägg">
          <!-- Lägg till klasser för pagineringen här -->
          <?php the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => '<span class="page-numbers">Föregående</span>',
            'next_text' =>  '<span class="page-numbers">Nästa</span>',
          )); ?>
        </nav>

      <?php else : ?>
        <p><?php _e('Inga inlägg hittades.', 'textdomain'); ?></p>
      <?php endif; ?>
    </div>
  </div>

</section>
<section id="hero-cloud">
<div id="cloud">
<div>
De mest populära taggarna är:
</div>

<?php
wp_tag_cloud()?>
</div>
</section>
</div>


</main>

<!-- excerpt($row['description'],10) för att komma av inläggen som visas -->
<?php get_footer(); ?>
