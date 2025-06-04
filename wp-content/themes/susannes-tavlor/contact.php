<?php
/*
Template Name: Kontaktsida
*/

get_header(); ?>

<main>

  <section class="page-content">
    <div class="contact-form">
      <h2>Vill du komma i kontakt med mig?</h2>
      <p>Fyll i formuläret nedan så hör jag av mig så snart som möjligt.</p>
      <?php echo do_shortcode('[wpforms id="68" title="false" description="false"]'); ?>
    </div>
  </section>
</main>



<?php get_footer(); ?>
