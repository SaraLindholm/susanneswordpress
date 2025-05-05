<?php


get_header(); ?>

<main>
<div class="row">
  <div id="primary" class="col-xs-12 col-md-9 offset-md-1">
      <h1>404- Ojdå, sidan du söker finns inte. </h1>
     <p>
      <h6>
        <a href="<?php echo home_url(); ?>"> <i class="fa fa-arrow-left"></i> Ta mig till     Startsidan
        </a>
      </h6>
    </p>
    <p>
      <h6>
        <a href="<?php echo home_url('/galleri'); ?>"><h6> Ta mig till Tavlorna <i class="fa fa-arrow-right"></i>
        </a>
      </h6>
    </p>


    </div>
  </div>
</main>

<?php get_footer(); ?>
