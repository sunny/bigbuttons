<?php require 'lib/bigbuttons.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <title>Big Buttons</title>
  <meta charset=utf-8>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
  <script src="bigbuttons.jquery.js"></script>
  <link rel="stylesheet" href="bigbuttons.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
  <ol class="bigbuttons">
<?php foreach (Sound::all() as $sound) : ?>
    <li>
      <button>
        <audio src="<?php echo h($sound->path) ?>" preload="none">
          <a href="<?php echo h($sound->path) ?>"><?php echo h($sound->path) ?></a>
        </audio>
        <?php echo ($sound->name()) ?>

      </button>
    </li>
<?php endforeach; ?>
  </ol>
</body>
</html>
