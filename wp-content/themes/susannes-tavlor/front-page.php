<?php get_header(); ?>

<main>
  <section class="intro">
    <h1>Välkommen till Susannes Tavlor</h1>
    <p>Här hittar du unika konstverk – klicka gärna dig vidare för att läsa mer om varje tavla.
    </p>
  </section>
  <section class="page-content">
    <?php
    if (have_posts()) :
      while (have_posts()) : the_post();?>
      <h2> <?php the_title();?> </h2>
      <?php the_post_thumbnail('large');
        the_content();

      endwhile;
    endif;
    ?>
  </section>


</main>

<?php get_footer(); ?>
