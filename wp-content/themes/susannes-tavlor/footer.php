<footer id="footer">
  <section id="content">
    <div>
      <p>ArtBySusanne25 &copy; <?php echo date('Y'); ?></p>
    </div>
  </section>

  <section>
    <h4>Senaste inläggen</h4>
    <?php
    $reccent_posts = new WP_Query([
      'posts_per_page' => 3,
      'post_status' => 'publish',
      'orderby' => 'date',
    ]);
    if ($reccent_posts->have_posts()) :
      while ($reccent_posts->have_posts()) : $reccent_posts->the_post();
    ?>

        <div class="card" style="width: 18rem;">
        <?php
              if (has_post_thumbnail()) {
                the_post_thumbnail('thumbnail', ['alt' => get_the_title()]);
              } else {
                // Hämta första bilden från innehållet
                $content = get_the_content();
                preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
                if (!empty($image['src'])) {
                  echo '<img class="wp-thumbnail" src="' . esc_url(get_template_directory_uri() . '/images/no-image.png') . '" alt="Standardbild">';
                }
              }
              ?>

          <div class="card-body">
            <a href="<?php the_permalink() ?>">
              <h5 class="card-title"><?php the_title() ?></h5>
              <p class="card-text"><?php the_date() ?></p>
            </a>
          </div>
        </div>
      <?php
      endwhile;
      wp_reset_postdata();
    else :
      ?>
      <p>Inga inlägg att visa</p>
    <?php endif; ?>
  </section>

</footer>
<?php wp_footer(); ?>
</body>

</html>
