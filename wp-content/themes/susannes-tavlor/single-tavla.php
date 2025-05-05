<?php get_header(); ?>

<main>
 <p>single-tavla.php</p>


    <section>

      <div class="tavla-wrapper">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div id="section-left">
        <div id="image"><?php if (has_post_thumbnail()) {
              the_post_thumbnail();
            } ?></div>
        </div>
        <div id="section-right">BOX2
        <article class="tavla">

<?php
$tillganglighet = get_field('tillganglighet');
$matt = get_field('matt');
$pris = get_field('pris');
?>

   <h3><?php the_title(); ?></h3>
  <i class="fa fa-arrow-left"></i>
    <a href="<?php echo home_url('/galleri'); ?>">Tillbaka
    </a>

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

      <ul class="meta-data">
        <li>
          <i class="fa fa-calendar"></i><?php the_date(); ?>
        </li>
        <li>
          <i class="fa fa-user"></i>
          <?php echo get_the_author_posts_link(); ?>
        </li>
        <li>
        <i class="fa-solid fa-list"></i><?php
                                    // Hämta kategorier för inlägget
                                    $categories = get_the_category();

                                    if (!empty($categories)) {
                                      foreach ($categories as $category) {
                                        echo '<a href="' . esc_url(home_url('/kategorier/?kategori=' . $category->slug)) . '" rel="category tag">' . esc_html($category->name) . '</a> ';
                                      }
                                    }
                                    ?>
        </li>
        <li>
      <i class="fa-solid fa-tag"></i><?php
      $tags = get_the_tags();
      if (!empty ($tags)) {
        foreach ($tags as $tag) {
          echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" rel="tag">' . esc_html($tag->name) . '</a> ';
        }
      }?>
    </li>
      </ul>

    <p><a href="<?php the_permalink(); ?>">Jag vill köpa en tavla</a></p>
</article>
        </div>

          <?php endwhile; ?>

      </div>
    </section>

    <?php
  else :
    echo '<p>Inga konstverk hittades.</p>';
  endif;

  // Återställ postdata
  wp_reset_postdata();
  ?>
</main>



  <?php get_footer();?>
