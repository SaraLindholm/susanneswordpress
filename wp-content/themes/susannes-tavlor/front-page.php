<?php get_header(); ?>

<main>
<section class="page-content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>


    <section class="page-inner">

      <section class="page-image">
        <?php the_post_thumbnail('large'); ?>
      </section>
      <section class="page-text">
      <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
      </section>
    </section>

  <?php endwhile; endif; ?>
</section>


</main>

<?php get_footer(); ?>
