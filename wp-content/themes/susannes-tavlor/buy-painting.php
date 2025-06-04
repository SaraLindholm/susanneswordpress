<?php
/*
Template Name: Köpsida
*/

get_header(); ?>

<main>

  <section class="page-content">
    <div class="contact-form">
          <p>Kul att du vill köpa en tavla!</p>
      <?php echo do_shortcode('[wpforms id="132" title="false" description="true"]'); ?>
    </div>
  </section>
</main>



<?php get_footer(); ?>
