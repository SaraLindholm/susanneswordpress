<?php
/*
Template Name: Om-sidan
*/

get_header(); ?>

<main>
  <section class="intro">
    <h1>Välkommen till Susannes Tavlor</h1>
    <p>Om mig sidan
  </section>

  <section class="page-content">
    <?php
    if (have_posts()) :
      while (have_posts()) : the_post();
        the_content();
      endwhile;
    endif;
    ?>
  </section>
</main>



</main>

<?php get_footer(); ?>
