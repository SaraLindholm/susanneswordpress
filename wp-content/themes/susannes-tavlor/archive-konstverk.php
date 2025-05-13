<?php


get_header(); ?>

<main>


  <p>  <h2>Tavlor</h2></p>
  <p>archive-konstverk.php</p>
<!--   <p>kan det vara en lösning att göra en ytterligare meny här som gör att det känns som att man är kvar på arkivsidan men att man istället går in på sidan för tex en viss kategori?</p> -->

  <?php
  $args = array(
    'post_type' => 'tavla',
    'posts_per_page' => 22,
  );
  $query = new WP_Query($args);

  if ($query->have_posts()) : ?>


    <section class="tavlor-container">
      <div class="tavlor-wrapper">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <article class="tavla-archive">

            <?php
            $tillganglighet = get_field('tillganglighet');
            $matt = get_field('matt');
            $pris = get_field('pris');
            ?>

            <div id="imageTODO">
              <!-- fixa korrekt styling för bild här me bla skugga -->
              <?php if (has_post_thumbnail()) {?>
                <img class="rounded" src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>"><?php

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
    <p><strong>REA: SEK <?= esc_html($rea); ?></strong></p>
  <?php else: ?>
    <!-- Om det INTE finns ett REA-pris -->
    <p><b>SEK <?= esc_html($pris); ?></b></p>
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



                <div class="button-wrapper">
                <a href="<?php the_permalink(); ?>" class="btn-view-tavla" id="button">TILL TAVLAN</a></div>

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
  ?>
</main>



<?php get_footer(); ?>
