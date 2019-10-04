<?php
// Standard view
//
// Show results as images
require_once(__DIR__ . '/helpers.php');
?>

<div id="results" class="row">
  <ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-3 no-bullet">

    <?php
    $result_nr = 0;
    foreach ($results->response->docs as $doc):
      $result_nr++;
      $id = $doc->id;

      // Type
      $type = $doc->content_type_ss;

      // URI

      // if part of container like zip, link to container file
      // if PDF page URI to Deeplink
      // since PDF Reader can open deep links
      if (isset($doc->container_s) and $type != 'PDF page') {
        $uri = $doc->container_s;
        $deepid = $id;

      }
      else {
        $uri = $id;
        $deepid = FALSE;
      }

      $uri_label = $uri;
      $uri_tip = FALSE;

      // if file:// then only filename
      if (strpos($uri, "file://") == 0) {
        $uri_label = basename($uri);

        // for tooptip remove file:// from beginning
        $uri_tip = substr($uri, 7);
        $uri_tip = htmlspecialchars($uri_tip);

      }

      if ($deepid) {
        $deep_uri_label = $deepid;
        $deep_uri_label = htmlspecialchars($deep_uri_label);

        $deep_uri_tip = FALSE;
        // if file:// then only filename
        if (strpos($deepid, "file://") == 0) {
          $deep_uri_label = basename($deepid);
          $deep_uri_label = htmlspecialchars($deep_uri_label);

          // for tooltip remove file:// from beginning
          $deep_uri_tip = substr($deepid, 7);
          $deep_uri_tip = htmlspecialchars($deep_uri_tip);

        }
      }

      $uri_unmasked = $uri;
      $uri = htmlspecialchars($uri);
      $uri_label = htmlspecialchars($uri_label);


		// Authors
		if (is_array($doc->author_ss)) {
			$authors = $doc->author_ss;
		} else {
			$authors = array($doc->author_ss);
		}
		
		
      // Title
      $title = format_title($doc->title_txt, $uri_label);

      // Modified date
      $datetime = FALSE;
      if (isset($doc->file_modified_dt)) {
        $datetime = $doc->file_modified_dt;
      }
      elseif (isset($doc->last_modified)) {
        $datetime = $doc->last_modified;
      }

      $file_size = 0;
      $file_size_txt = '';
      // File size
      $file_size_field = 'Content-Length_i';
      if (isset($doc->$file_size_field)) {
        $file_size = $doc->$file_size_field;
        $file_size_txt = filesize_formatted($file_size);
      }

      ?>

      <li>
        <div class="image">
          <a target="_blank" href="<?= $id ?>">
            <img width="200" src="<?= $id ?>" <?= $title ? 'title="' . $title . '"' : '' ?> />
          </a>
        </div>

        <div class="row">
          <div class="date small-8 columns"><?= $datetime ?></div>
          <div class="size small-4 columns"><?= $file_size_txt ?></div>
        </div>

          <?php if ($authors): ?>
            <div class="author"><?= htmlspecialchars(implode(", ", $authors)) ?></div>
          <?php endif; ?>

        <div class="title imagelist">
          <a href="<?= $id ?>"><h2><?= $title ? $title : $uri_label ?></h2></a>
        </div>

        <div class="snippet">
          <?= $snippets ?>
        </div>

        <?php
          include 'templates/view.commands.php';
        ?>

      </li>
    <?php endforeach; ?>
  </ul>
</div>
