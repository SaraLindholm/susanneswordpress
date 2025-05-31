<?php get_header(); ?>

<main>
  <h2>Hej från Susannes Tavlor!</h2>
  <p>Detta är startsidan.</p>
  <p>index.php</p>
  <?php if (have_posts()) //Kontrollerar om det finns inlägg/tavlor/osv
        : while (have_posts()) : the_post(); ?> <!-- Om det finns inlägg, kör loopen -->
    <article>
      <h2><?php the_title(); ?></h2> <!-- Skriver ut titlen -->
      <div><?php the_content(); ?></div> <!-- Skriver ut allt innehåll -->
    </article>
  <?php endwhile;
  else : ?>
    <p>Inga inlägg hittades.</p>
  <?php endif; ?> <!-- Avslutar if-satsen -->
</main>

<?php get_footer(); ?>
