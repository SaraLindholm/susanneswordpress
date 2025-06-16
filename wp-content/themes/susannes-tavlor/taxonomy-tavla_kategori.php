
<?php
get_header(); ?>

<main>

  <h3>Kategorier: <?php single_term_title(); ?></h3>



  <?php if ( have_posts() ) : ?>

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
        <?php while (have_posts()) : the_post(); ?>
          <article class="tavla-archive">

            <?php
            $tillganglighet = get_field('tillganglighet');
            $matt = get_field('matt');
            $pris = get_field('pris');
            ?>

            <div id="image">
            <div>
                <a href="<?php the_permalink(); ?>" class="btn-view-tavla" id="button">


              <?php if (has_post_thumbnail()) { ?>
                <img class="rounded" src="<?php the_post_thumbnail_url('medium'); ?>"
                  alt="<?php the_title(); ?>"><?php

                                            } ?>
                                            </a>
          </div>
          </div>
            <h4><?php the_title(); ?></h4>

            <?php if ($matt): ?>
              <p><strong>Mått: </strong><?= esc_html($matt); ?>
              <?php endif; ?></p>

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
                  <p><span class="status-dot" style="background-color: <?= $color; ?>;"></span>
                    <span><?= $label; ?></span>
                  </p>
                </div>
              <?php endif; ?>



<!--
              <div class="button-wrapper">
                <a href="<?php the_permalink(); ?>" class="btn-view-tavla" id="button">TILL TAVLAN</a>
              </div>
 -->
          </article>
        <?php endwhile; ?>

      </div>
    </section>


  <?php
  else :
    echo '<p>Inga konstverk hittades.</p>';
  endif;

  // Återställ postdata
  wp_reset_postdata();?>
 <!-- Pagination -->
 <nav class="pagination" aria-label="Sidonumrering för inlägg">
      <?php
      the_posts_pagination(array(
        'mid_size'  => 2,
        'prev_text' => '<span class="page-numbers">Föregående</span>',
        'next_text' => '<span class="page-numbers">Nästa</span>',
      ));
      ?>
    </nav>

</main>



<?php get_footer(); ?>
