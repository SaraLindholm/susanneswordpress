<?php
/*
Template Name: Kontaktsida
*/

get_header(); ?>

<main>
  <section class="intro">
    <h1>Välkommen till Susannes Tavlor</h1>
    <p>Undrar om detta syns?
  </section>

  <section class="page-content">
    <div class="contact-form">
      <h2>Kontakta oss</h2>
      <p>Fyll i formuläret nedan så hör vi av oss så snart som möjligt.</p>
      <?php echo do_shortcode('[wpforms id="68" title="false" description="false"]'); ?>
    </div>
  </section>
</main>



<!-- https://wpforms.com/docs/how-to-properly-test-your-wordpress-forms-before-launching-checklist/
 jag måste test validering osv av formuläret. samt kolla varför form Email inte är rätt ? -->

<?php get_footer(); ?>
