<?php get_header(); ?>

<main>
  <p>single-tavla.php</p>


  <section>

    <div class="tavla-wrapper">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div id="section-left">
            <div id="image">
            <?php if (has_post_thumbnail()) {?>
                <img class="rounded" src="<?php the_post_thumbnail_url('large'); ?>" alt="Bild med titel: <?php the_title(); ?>"><?php

              } ?>

                          </div>
          </div>
          <div id="section-right">
            <article class="tavla">

              <?php
              $tillganglighet = get_field('tillganglighet');
              $matt = get_field('matt');
              $pris = get_field('pris');
              $beskrivning = get_field('beskrivning');
              ?>
              <p>
                <a href="<?php echo home_url('/galleri'); ?>">
                  <i class="fa fa-arrow-left"></i>Tillbaka
                </a>
              </p>
              <h3><?php the_title(); ?></h3>
              <?php if ($tillganglighet): ?>
                <div class="tillganglighet-status">
                  <?php
                  $color = '';
                  $label = '';

                  switch ($tillganglighet) {
                    case 'Såld':
                      $color = 'red';
                      $label = 'Såld';
                      break;
                    case 'Reserverad':
                      $color = 'yellow';
                      $label = 'Reserverad';
                      break;
                    default:
                      $color = 'green';
                      $label = esc_html($tillganglighet);
                      break;
                  }
                  ?>
                  <p><span class="status-dot" style="background-color: <?= $color; ?>;"></span>
                    <span><?= $label; ?></span>
                  </p>
                </div>
              <?php endif; ?>
              <p>Målades den <?php the_date(); ?></p>


              <?php if ($matt): ?>
                <p>Mått: <?= esc_html($matt); ?>
                <?php endif; ?></p>


                <div id="content"><?php if ($beskrivning): ?>
                    <?= esc_html($beskrivning); ?>
                  <?php endif; ?>
                </div>
                <?php if ($pris): ?>
                  <p><b>SEK <?= esc_html($pris); ?></b></p>
                  <?php endif; ?></p>



                  <ul class="meta-data">


                    <li>
                      <i class="fa-solid fa-list"></i><?php
                                                      // Hämta kategorier för inlägget
                                                      $categories = get_the_category();

                                                      if (!empty($categories)) {
                                                        foreach ($categories as $category) {
                                                          echo '<a href="' . esc_url(home_url('/kategorier/?kategori=' . $category->slug)) . '" rel="category tag">' . esc_html($category->name) . '</a> ';
                                                        }
                                                      }
                                                      ?>
                    </li>
                    <li>
                      <i class="fa-solid fa-tag"></i><?php
                                                      $tags = get_the_tags();
                                                      if (!empty($tags)) {
                                                        foreach ($tags as $tag) {
                                                          echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" rel="tag">' . esc_html($tag->name) . '</a> ';
                                                        }
                                                      } ?>
                    </li>
                  </ul>

                  <div class="button-wrapper">
                    <div id="button"><a href="<?php the_permalink(); ?>" class="btn btn-dark btn-lg">KONTAKTA MIG FÖR INKÖP</a>
                    </div>
                  </div>
            </article>
          </div>

        <?php endwhile; ?>

    </div>
  </section>

<?php
      else :
        echo '<p>Inga konstverk hittades.</p>';
      endif;

      // Återställ postdata
      wp_reset_postdata();
?>
</main>



<?php get_footer(); ?>
