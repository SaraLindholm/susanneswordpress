<?php


get_header(); ?>

<main>


  <h2>Galleri-archive tavla.php</h2>

  <?php
  $args = array(
    'post_type' => 'tavla',
    'posts_per_page' => 20,
    'paged' => $paged, //
  );
  $query = new WP_Query($args);

  if ($query->have_posts()) : ?>

    <ul class="category-meny"><!-- Meny för tavlornas kategorier -->
    <li><a href="<?php echo home_url('/galleri'); ?>">
                  Visa alla tavlor
                </a></li>
      <?php
      $args = array(
        'orderby'    => 'name',       // sortera alfabetiskt
        'order'      => 'ASC',       // stigande ordning, dvs A-Ö
        'hide_empty' => true,        // visa endast kategorier som har tavlor
        'show_count' => true,         // visa antal tavlor i varje kategori
        'title_li'   => '',           // ta bort "Kategorier" rubrik
        'taxonomy'   => 'tavla_kategori',   // den anpassade kategori-taxonomin

      );
      wp_list_categories($args);
      ?>
    </ul>


    <section class="tavlor-container">
      <div class="tavlor-wrapper">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <article class="tavla-archive">

            <?php
            $tillganglighet = get_field('tillganglighet');
            $matt = get_field('matt');
            $pris = get_field('pris');
            ?>

            <div id="image">

              <?php if (has_post_thumbnail()) { ?>
                <img class="rounded" src="<?php the_post_thumbnail_url('medium'); ?>"
                  alt="<?php the_title(); ?>"><?php

                                            } ?>
            </div>
            <h3><?php the_title(); ?></h3>

            <?php if ($matt): ?>
              <p><strong>Mått: </strong><?= esc_html($matt); ?>
              <?php endif; ?></p>
              <div class="rea-wrapper">
                <?php if ($rea): ?>
                  <!-- Om det finns ett REA-pris -->
                  <p class="red"><del>SEK <?= esc_html($pris); ?></del></p>
                  <p>Nytt Pris: <?= esc_html($rea); ?> SEK</p>
                <?php else: ?>
                  <!-- Om det INTE finns ett REA-pris -->
                  <p><b>Pris: <?= esc_html($pris); ?></b> SEK</p>
                <?php endif; ?>
              </div>
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
                      $label = esc_html($tillganglighet); // fallback
                      break;
                  }
                  ?>
                  <p><strong>Status: </strong><span class="status-dot" style="background-color: <?= $color; ?>;"></span>
                    <span><?= $label; ?></span>
                  </p>
                </div>
              <?php endif; ?>
              <h6><?php the_time('F j, Y'); ?></h6> <!-- // Skriver ut datumet när tavlan målades  -->



              <div class="button-wrapper">
                <a href="<?php the_permalink(); ?>" class="btn-view-tavla" id="button">TILL TAVLAN</a>
              </div>

          </article>
        <?php endwhile; ?>

      </div>
    </section>

  <?php
  else :
    echo '<p>Inga konstverk hittades.</p>';
  endif;

  // Återställ postdata
  wp_reset_postdata();

  // Pagination för custom query
  $randomNumber = 999999999; // ett högt nummer som ersätts nedan

  echo '<nav class="pagination" aria-label="Sidonumrering för inlägg">';
  echo paginate_links(array(
    'base' => str_replace($randomNumber, '%#%', esc_url(get_pagenum_link($randomNumber))), //bygger strukturen för sidnumrering,
    //där '%#%', är placeholder för sidnumret
    'format' => '?paged=%#%',
    'current' => max(1, get_query_var('paged')), //hämtar nuvarande sida via 'paged', max1 säkerställer att det är minst 1
    'total' => $query->max_num_pages, //hämtar och skriver ut det totala antalet sidor
    'prev_text' => '<span class="page-numbers">Föregående</span>', //Definerar vilken text som ska visas innan sidorna
    'next_text' => '<span class="page-numbers">Nästa</span>', //Definerar vilken text som ska visas efter sidorna
  ));
  echo '</nav>';
  ?>
</main>



<?php get_footer(); ?>
