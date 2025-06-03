<?php get_header(); ?>

<main>
  <p>single.php</p>
  <section>
    <div class="container">
      <div class="row">
      <div id="primary" class="col-xs-12 col-md-9 offset-md-1">
          <?php

          // Starta loopen
          if (have_posts()) :
            while (have_posts()) : the_post();
          ?>
              <article>
                <h2 class="title"><?php the_title(); ?></h2>
                <ul class="meta-data">
                  <li>
                    <i class="fa fa-calendar"></i><?php the_date(); ?>
                  </li>
                  <li>
                    <i class="fa fa-user"></i>
                    <?php echo get_the_author_posts_link(); ?>
                  </li>
                  <li>
                    <i class="fa fa-tag"></i><?php
                                              // Hämta kategorier för inlägget
                                              $categories = get_the_category();

                                              if (!empty($categories)) {
                                                foreach ($categories as $category) {
                                                  echo '<a href="' . esc_url(home_url('/kategorier/?kategori=' . $category->slug)) . '" rel="category tag">' . esc_html($category->name) . '</a> ';
                                                }
                                              }
                                              ?>
                  </li>
                </ul>
                <p><?php the_content(); ?></p>
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
            <?php comments_template(); ?>
              </article>
            <?php endwhile; ?>

            <!-- TODO Lägg till klasser för pagineringen här -->
            <?php the_posts_pagination(array(
              'mid_size' => 2,
              'prev_text' => __('Föregående', 'textdomain'),
              'next_text' => __('Nästa', 'textdomain'),
            )); ?>

          <?php else : ?>
            <p><?php _e('Inga inlägg hittades.', 'textdomain'); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
