<?php get_header(); ?>

<main>

  <section>
    <?php
    $tillganglighet = get_field('tillganglighet');
    $matt = get_field('matt');
    $matt_med_ram = get_field('matt_ram');
    $datum = get_field('malades_den');
    $pris = get_field('pris');
    $rea = get_field('rea');
    $beskrivning = get_field('beskrivning');
    $tagg = get_field('tagg');
    $teknik = get_field('teknik');
    $extrabild = get_field('extrabild');
    ?>

    <div class="tavla-wrapper">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div id="section-left">
            <div>
              <div id="image">
                <?php if (has_post_thumbnail()) { ?>
                  <img class="rounded" src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="Bild med titel: <?php the_title(); ?>"><?php

                                                                                                                                        } ?>
              </div>
              <?php if ($extrabild): ?>
                <img class="rounded" src="<?php echo esc_url($extrabild['url']); ?>" alt="<?php echo esc_attr($extrabild['alt']); ?>">
              <?php endif; ?>
            </div>
          </div>
          <div id="section-right">

            <article class="tavla">


              <div>
                <p>
                  <!-- Länk tillbaka till galleriet -->
                  <a href="<?php echo home_url('/galleri'); ?>">
                    <i class="fa fa-arrow-left"></i>Tillbaka
                  </a>
                </p>

              </div>
              <h3><?php the_title(); ?></h3>
              <p id="category-list"><?php
                                    // Hämta kategorier för inlägget
                                    $categories = get_the_terms(get_the_ID(), 'tavla_kategori');
                                    if (!empty($categories) && !is_wp_error($categories)) {
                                      foreach ($categories as $category) {
                                        echo '<i> <a href="' . esc_url(get_term_link($category)) . '" rel="category tag">' . esc_html($category->name) . ', ' . '</a> </i>';
                                      }
                                    }
                                    ?></p>
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
                    case 'Tillgänglig':
                      $color = 'green';
                      $label = 'Tillgänglig';
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
              <?php if ($datum): ?>
              <p>Målades den <?= esc_html($datum) ?></p>
              <?php endif; ?>

              <?php if ($matt): ?>
                <p>Mått: <?= esc_html($matt); ?>
                <?php endif; ?></p>

                <?php if ($matt_med_ram): ?>
                  <p>Mått: <?= esc_html($matt_med_ram); ?> med ram
                  <?php endif; ?></p>

                  <?php if ($teknik): ?>
                    <p>Teknik: <?= esc_html($teknik); ?>
                    <?php endif; ?></p>


                    <div id="content"><?php if ($beskrivning): ?>
                        <?= esc_html($beskrivning); ?>
                      <?php endif; ?>
                    </div>

                    <div class="rea-wrapper">
                      <?php if ($rea): ?>
                        <!-- Om det finns ett REA-pris -->
                        <del class="red">Pris: <?= esc_html($pris); ?>SEK </del> Nytt Pris: <?= esc_html($rea); ?> SEK
                      <?php else: ?>
                        <!-- Om det INTE finns ett REA-pris -->
                        <p><b>Pris: <?= esc_html($pris); ?></b> SEK</p>
                      <?php endif; ?>
                    </div>


                    <?php if (strcasecmp($tillganglighet, 'reserverad') === 0 || strcasecmp($tillganglighet, 'såld') === 0) : ?>


                      <div class="disabled-button-wrapper">
                        <a href="<?php echo esc_url('/kontaktsida') ?>" class="btn-view-tavla" id="button">EJ TILLGÄNGLIG</a>
                      </div>


                    <?php endif ?>

                    <?php if (strcasecmp($tillganglighet, 'tillgänglig') === 0) : ?>

                      <div class="button-wrapper">
                        <div id="button"><a href="<?php echo esc_url(add_query_arg(array(
                                                    'id' => get_the_ID(),  // Fyller i tavlans ID
                                                    'title' => get_the_title() // Fyller i tavlans title
                                                  ), '/kopsida')); ?>" class="btn-buy-tavla">
                            KONTAKT FÖR KÖP
                          </a>
                        </div>
                      </div>
                    <?php endif; ?>




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
