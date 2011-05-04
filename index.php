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
  <ol class="buttons">
<? foreach (glob('sounds/*.wav') as $sound) : ?>
    <li>
      <button>
        <audio src="<?=htmlspecialchars($sound)?>" autobuffer></audio>
        <?=htmlspecialchars(trim(preg_replace('/^[0-9]+|\.wav$|_+/i', ' ', basename($sound))))?>

      </button>
    </li>
<? endforeach; ?>
  </ol>
</body>
</html>
