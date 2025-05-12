<?php
get_header(); ?>

<main>
  <p>taxonomy-tagg.php</p>
<!--   <p>kan det vara en lösning att göra en ytterligare meny här som gör att det känns som att man är kar op arkivsidan men att man istället går in opå sidan för text en viss kategori?=</p> -->
  <h2>Tagg: <?php single_term_title(); ?></h2>


<?php if ( have_posts() ) : ?>
  <section class="tavlor-tagg-container">
  <div class="tavlor-wrapper">
    <?php while ( have_posts() ) : the_post(); ?>
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

              <?php if ($pris): ?>
                <p><strong>Pris: </strong><?= esc_html($pris); ?> SEK
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
<?php else : ?>
  <p>Inga tavlor hittades under denna tagg.</p>
<?php endif; ?>

<?php
  wp_reset_postdata();
  ?>
</main>



<?php get_footer(); ?>
