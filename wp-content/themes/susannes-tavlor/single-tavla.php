<?php get_header(); ?>

<main>

  <p>Detta är singelsida för konstverk.</p>
 <p>single-konstverk.php</p>

 <?php
  $args = array(
    'post_type' => 'tavla',
    'posts_per_page' => 1,
  );
  $query = new WP_Query($args);

  if ($query->have_posts()) : ?>
    <section class="tavlor-preview">
      <h2>En Tavla:</h2>
      <div class="tavlor-wrapper">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <article class="tavla">

          <?php
          $tillganglighet = get_field('tillganglighet');
          $matt = get_field('matt');
          $pris = get_field('pris');
          ?>


            <?php if (has_post_thumbnail()) {
              the_post_thumbnail('medium');
            } ?>
             <h3><?php the_title(); ?></h3>

             <?php if($matt): ?>
              <p><strong>Mått: </strong><?= esc_html($matt);?>
              <?php endif; ?></p>

              <?php if($pris): ?>
                <p><strong>Pris: </strong><?= esc_html($pris);?> SEK
                <?php endif; ?></p>

              <?php if($tillganglighet): ?>
                <p><strong>Tillgänglighet: </strong><?= esc_html($tillganglighet);?>
                <?php endif; ?></p>

              <p><a href="<?php the_permalink(); ?>">Läs mer</a></p>
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



  <?php get_footer();?>
