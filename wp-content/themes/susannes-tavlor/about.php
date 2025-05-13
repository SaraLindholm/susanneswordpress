<?php
/*
Template Name: Om-sidan
*/

get_header(); ?>

<main>
<section class="page-content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>


    <section class="page-inner">
      <section class="page-text">
      <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
      </section>
      <section class="page-image">
        <?php the_post_thumbnail('super-large'); ?>
      </section>
    </section>

  <?php endwhile; endif; ?>
</section>

<!-- kolla up varför inte .page-image tar upp full höjd  -->


</main>

<?php get_footer(); ?>
