<?php get_header(); ?>

<main>
  <h2>Hej från Susannes Tavlor!</h2>
  <p>Detta är startsidan.</p>
  <p>index.php</p>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article>
      <h2><?php the_title(); ?></h2>
      <div><?php the_content(); ?></div>
    </article>
  <?php endwhile;
  else : ?>
    <p>Inga inlägg hittades.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
