
<?php
get_header(); ?>

<main>

  <p>category.php</p>
  <h2>Kategorier: <?php single_term_title(); ?></h2>

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



                <div class="button-wrapper">
                <a href="<?php the_permalink(); ?>" class="btn-view-tavla" id="button">TILL TAVLAN</a></div>




    </article>

    <?php endwhile; ?>
    </div>

</section>
<?php else : ?>
  <p>Inga tavlor hittades under denna kategori.</p>
<?php endif; ?>

<?php
  wp_reset_postdata();
  ?>
</main>



<?php get_footer(); ?>
