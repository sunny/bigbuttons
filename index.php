<?php
$sounds = glob('*.wav');
?><!doctype html>
<html lang="en">
<head>
  <title>Big Buttons</title>
  <meta charset=utf-8>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $('audio').each(function(i) {
        var button = $(this).parent('button')
        $(this).bind('dataunavailable', function() { button.addClass('dataunavailable') })
               .bind('ended', function() { button.removeClass('playing') })
               .bind('pause', function() { button.removeClass('playing') })
               .bind('error', function() { button.addClass('error') })
      })

      $('button').live('click', function() {
        var button = $(this),
            audio = $(this).children('audio')[0]
        button.addClass('playing').addClass('active')
        setTimeout(function() { button.removeClass('active') }, 200)
        if (audio.currentTime)
          audio.currentTime = 0
        audio.play()
      })

      $(window).keypress(function(e) {
        // ESC pauses
        if (e.charCode === 0) {
          $('audio').each(function() { this.pause() })
          return
        }
        var number = parseInt(String.fromCharCode(e.charCode), 10)
        if (number)
          $('button').eq(number-1).click()
      })
    })
  </script>
  <style>
    button { font: 2em Helvetica, sans-serif; margin:.5ex 0; border:0;
      background:url(button.png) top left no-repeat; min-height:60px; padding-left:70px }
    .active { background-position: bottom left }
    .dataunavailable,
    .error { opacity: .3 }
    .playing { color: darkred }
    ul { list-style:none; }
  </style>
</head>
<body>
  <ul>
<? foreach ($sounds as $sound) : ?>
    <li>
      <button>
        <audio src="<?=htmlspecialchars(urlencode($sound))?>" autobuffer></audio>
        <?=htmlspecialchars(str_replace('_', ' ', basename($sound, '.wav')))?>

      </button>
    </li>
<? endforeach; ?>
  </ul>
</body>
</html>
