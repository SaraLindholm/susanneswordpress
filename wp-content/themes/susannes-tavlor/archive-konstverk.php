<?php
/*
Template Name: Gallerisida
*/

get_header(); ?>

<main>

  <p>Detta är arkive-kontverk (galleri).</p>
  <p>archive-konstverk.php</p>
  <!-- Exempel på hur du kan loopa ut några tavlor om du har en CPT -->
  <?php
  $args = array(
    'post_type' => 'tavla', // ändra till rätt CPT-slug
    'posts_per_page' => 3,
  );
  $query = new WP_Query($args);
  if ($query->have_posts()) : ?>
    <section class="tavlor-preview">
      <h2>Senaste tavlor</h2>
      <div class="tavlor-wrapper">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <article class="tavla">
            <h3><?php the_title(); ?></h3>
            <?php if (has_post_thumbnail()) {
              the_post_thumbnail('medium');
            } ?>
            <p><a href="<?php the_permalink(); ?>">Läs mer</a></p>
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
    </main>
  <?php endif; wp_reset_postdata(); ?>
</main>

<?php get_footer(); ?>
