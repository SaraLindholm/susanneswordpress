<?php


get_header(); ?>

<main>
  <h2>Välkommen till Susannes Tavlor</h2>
  <p>home.php</p>
  <p> lista över inlägg tänker jag? bloggsidan?</p>
</main>
<section>
  <div class="row">
    <div id="primary" class="col-xs-12 col-md-9">
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
              <li>
                <i class="fa-solid fa-user"></i>
                <?php echo get_the_author_posts_link(); ?>
              </li>
              <li>
                <i class="fa-solid fa-list"></i>
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                  foreach ($categories as $category) {
                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" rel="category tag">' . esc_html($category->name) . '</a> ';
                  }
                }
                ?>
              </li>
            </ul>
            <p>
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

          </article> <?php
                    endwhile; ?>


        <!-- TODO lägg till klasser för knapparna -->
        <nav class="pagination pagination" aria-label="Sidonumrering för inlägg">
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

<!-- excerpt($row['description'],10) för att komma av inläggen som visas -->
<?php get_footer(); ?>
