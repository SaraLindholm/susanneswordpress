<?php get_header(); ?>

<main>

  <p>Detta är singelsida för konstverk.</p>
 <p>single-konstverk.php</p>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <h2><?php the_title(); ?></h2>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  <?php endwhile; else : ?>
    <p>Inga konstverk hittades.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
