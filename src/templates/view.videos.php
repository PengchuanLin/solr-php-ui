<?php
// Standard view
//
// Show results as list
require_once(__DIR__ . '/helpers.php');
?>

<div id="results" class="row">

  <ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-2">

    <?php
    foreach ($results->response->docs as $doc) {

      // URI
      if (isset($doc->container_s)) {
        $id = $doc->container_s;
      }
      else {
        $id = $doc->id;
      }


      $uri_label = htmlspecialchars($id);
      $uri_tip = FALSE;

      // if file:// then only filename
      if (strpos($id, "file://") == 0) {
        $uri_label = htmlspecialchars(basename($id));
        // for tooptip remove file:// from beginning
        $uri_tip = htmlspecialchars(substr($id, 7));
      }

      // Author
      $author = htmlspecialchars($doc->author_ss);

      // Title
      $title = format_title($doc->title_txt);

      // Type
      $type = $doc->content_type_ss;

      // Modified date
      if (isset($doc->file_modified_dt)) {
        $datetime = $doc->file_modified_dt;
      }
      elseif (isset($doc->last_modified_dt)) {
        $datetime = $doc->last_modified_dt;
      }
      else {
        $datetime = FALSE;
      }


      // Snippet
      if (isset($results->highlighting->$id->content_txt)) {
        $snippet = $results->highlighting->$id->content_txt[0];
      }
      else {
        $snippet = $doc->content_txt;
        if (strlen($snippet) > $snippetsize) {
          $snippet = substr($snippet, 0, $snippetsize) . "...";
          $snippet = htmlspecialchars($snippet);
        }
      }

      ?>
      <li>

        <div class="title">
          <a class="title" target="_blank" href="<?= $id ?>">
            <?php if ($title) { ?>
              <?= $title ?>
            <?php } ?>

          </a>
        </div>

        <?php if ($uri_tip) { ?><span class="uri">
					<span data-tooltip class="has-tip" title="<?= $uri_tip ?>">
				<?php } ?>
        <?= $uri_label ?>
        <?php if ($uri_tip) { ?>
					</span></span>
      <?php } ?>


        <div class="video">

          <video controls src="<?= $id ?>"></video>

        </div>


        <div class="row">
          <div class="date small-8 columns"><?= $datetime ?></div>
          <div class="size small-4 columns"><?= $file_size_txt ?></div>
        </div>

        <div class="snippet">
          <?php if ($author) {
            print '<div class="author">' . $author . '</div>:';
          } ?>
          <?= $snippet ?>
        </div>

        <?php
          include 'templates/view.commands.php';
        ?>

      </li>

      <?php
    } // foreach doc
    ?>

  </ul>
</div>
