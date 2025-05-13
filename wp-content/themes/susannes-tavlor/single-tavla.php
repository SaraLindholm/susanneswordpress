<?php get_header(); ?>

<main>
  <p>single-tavla.php</p>
<!--   <p>kan det vara en lösning att göra en ytterligare meny här som gör att det känns som att man är kar op arkivsidan men att man istället går in opå sidan för text en viss kategori?=</p> -->


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
              $matt_med_ram = get_field('matt_med_ram');

              $pris = get_field('pris');
              $rea = get_field('rea');
              $beskrivning = get_field('beskrivning');
              $tagg = get_field('tagg');
              $teknik = get_field('teknik');
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
                <p>Mått: <?= esc_html($matt_med_ram); ?>
                <?php endif; ?> med ram</p>
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
                  <p class="red"><del>SEK <?= esc_html($pris); ?></del></p>
                  <p>REA: SEK <?= esc_html($rea); ?></p>
                <?php else: ?>
                  <!-- Om det INTE finns ett REA-pris -->
                  <p><b>SEK <?= esc_html($pris); ?></b></p>
                <?php endif; ?>
              </div>






                <?php if ($tagg): ?>
                  <p><b>Tagg: <?= esc_html($tagg); ?></b></p>
                <?php endif; ?></p>

                <?php
echo get_the_term_list( get_the_ID(), 'tagg', '<p class="tavla-taggar">Taggar: ', ', ', '</p>' );
?>

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


                <?php if (strcasecmp($tillganglighet, 'såld') === 0) : ?>


                 <div class="disabled-button-wrapper">
                    <a href="<?php echo esc_url('/kontaktsida') ?>" class="btn-view-tavla" id="button">EJ TILLGÄNGLIG FÖR KÖP</a>
                  </div>


                <?php endif ?>

                <?php if (strcasecmp($tillganglighet, 'reserverad') === 0 || strcasecmp($tillganglighet, 'tillgänglig') === 0) : ?>

                  <div class="button-wrapper">
                    <div id="button"><a href="<?php echo esc_url(add_query_arg(array(
                                                'id' => get_the_ID(),  // Fyller i tavlans ID
                                                'namn' => get_the_title() // Fyller i tavlans namn
                                              ), '/kopsida')); ?>" class="btn-buy-tavla">
                        KONTAKTA MIG FÖR INKÖP
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
