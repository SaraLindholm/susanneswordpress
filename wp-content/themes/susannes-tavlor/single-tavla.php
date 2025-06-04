<?php get_header(); ?>

<main>

  <section>

    <div class="tavla-wrapper">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div id="section-left">
            <div id="image">
              <?php if (has_post_thumbnail()) { ?>
                <img class="rounded" src="<?php the_post_thumbnail_url('large'); ?>" alt="Bild med titel: <?php the_title(); ?>"><?php

                                                                                                                                } ?>

            </div>
          </div>
          <div id="section-right">
            <article class="tavla">

              <?php
              $tillganglighet = get_field('tillganglighet');
              $matt = get_field('matt');
              $matt_med_ram = get_field('matt_ram');

              $pris = get_field('pris');
              $rea = get_field('rea');
              $beskrivning = get_field('beskrivning');
              $tagg = get_field('tagg');
              $teknik = get_field('teknik');
              ?>
              <p id="link_category">
                <a href="<?php echo home_url('/galleri'); ?>">
                  <i class="fa fa-arrow-left"></i>Tillbaka
                </a>
                <?php
    // Hämta kategorier för inlägget
    $categories = get_the_terms(get_the_ID(), 'tavla_kategori');
    if (!empty($categories) && !is_wp_error($categories)) {
      foreach ($categories as $category) {
        echo '<i>Kategori: <a href="' . esc_url(get_term_link($category)) . '" rel="category tag">' . esc_html($category->name) . '</a> </i>';
      }
    }
    ?>
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
              <p>Målades den <?php the_date(); ?></p>


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
