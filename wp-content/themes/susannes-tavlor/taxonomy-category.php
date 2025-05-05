
<?php


get_header(); ?>

<main>



  <p>taxonomy-category-tavla.php</p>

  <?php
  $args = array(
    'post_type' => 'tavla',
    'posts_per_page' => 12,
  );
  $query = new WP_Query($args);

  if ($query->have_posts()) : ?>
    <section>
      <h2>Senaste tavlor</h2>
      <div>
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <article class="tavla">

          <?php
          $tillganglighet = get_field('tillganglighet');
          $matt = get_field('matt');
          $pris = get_field('pris');
          ?>


            <?php if (has_post_thumbnail()) {
              the_post_thumbnail('medium');
            } ?>
             <h3><?php the_title(); ?></h3>

             <?php if($matt): ?>
              <p><strong>Mått: </strong><?= esc_html($matt);?>
              <?php endif; ?></p>

              <?php if($pris): ?>
                <p><strong>Pris: </strong><?= esc_html($pris);?> SEK
                <?php endif; ?></p>



                <?php if ($tillganglighet): ?>
  <div class="tillganglighet-status">
    <?php
    $color = '';
    $label = '';

    switch ($tillganglighet) {
      case 'Såld':
        $color = 'red';
        $label = 'Såld';
        break;
      case 'Reserverad':
        $color = 'yellow';
        $label = 'Reserverad';
        break;
      default:
        $color = 'green';
        $label = esc_html($tillganglighet); // fallback
        break;
    }
    ?>
    <p><strong>Status:  </strong><span class="status-dot" style="background-color: <?= $color; ?>;"></span>
    <span><?= $label; ?></span></p>
  </div>
<?php endif; ?>

<button type="button"><a href="<?php the_permalink(); ?>">Till tavlan</a></button>
          </article>
          <?php endwhile; ?>

      </div>
    </section>







    <h1>category.HTML</h1>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <div class="col">
                <div class="card">
                    <img src="/img/photo.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Tavlans titel</h5>
                        <p class="card-text">Lite fakta om tavlan..</p>
                        <p class="card-text">Kategori</p>
                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                        <a href="#" class="btn btn-primary">Till tavlan</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="/img/photo.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Tavlans titel</h5>
                        <p class="card-text">Lite fakta om tavlan..</p>
                        <p class="card-text">Kategori</p>
                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                        <a href="#" class="btn btn-primary">Till tavlan</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="/img/photo.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Tavlans titel</h5>
                        <p class="card-text">Lite fakta om tavlan..</p>
                        <p class="card-text">Kategori</p>
                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                        <a href="#" class="btn btn-primary">Till tavlan</a>
                    </div>
                </div>
            </div>
                <div class="col">
                <div class="card">
                    <img src="/img/photo.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Tavlans titel</h5>
                        <p class="card-text">Lite fakta om tavlan..</p>
                        <p class="card-text">Kategori</p>
                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                        <a href="#" class="btn btn-primary">Till tavlan</a>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <?php
  else :
    echo '<p>Inga konstverk hittades.</p>';
  endif;

  // Återställ postdata
  wp_reset_postdata();
  ?>
</main>



  <?php get_footer();?>
