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
                <article class="blogg-post">
                <h2 class="title"><?php the_title(); ?></h2>
                <ul class="meta-data">
                  <li>
                    <i class="fa fa-calendar"></i><?php the_date(); ?>
                  </li>
                </ul>

                <p><?php the_content(); ?></p>
                <?php if (has_post_thumbnail()) { ?>
                  <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>"><?php

                                                                                                                                        } ?>
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


              </article>
              <?php comments_template(); ?>
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
