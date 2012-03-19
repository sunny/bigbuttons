<!doctype html>
<html lang="en">
<head>
  <title>Big Buttons</title>
  <meta charset=utf-8>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script src="bigbuttons.jquery.js"></script>
  <link rel="stylesheet" href="bigbuttons.css" />
</head>
<body>
  <ol class="bigbuttons">
<?php foreach (glob('sounds/*.wav') as $sound) : ?>
    <li>
      <button>
        <audio src="<?php echo htmlspecialchars($sound)?>" autobuffer>
          <a href="<?php echo htmlspecialchars($sound)?>"><?php echo htmlspecialchars($sound)?></a>
        </audio>
        <?php echo htmlspecialchars(trim(preg_replace('/^[0-9]+|\.wav$|_+/i', ' ', basename($sound))))?>

      </button>
    </li>
<?php endforeach; ?>
  </ol>
</body>
</html>
