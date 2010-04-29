<?php
$sounds = glob('*.wav');
?><!doctype html>
<html lang="en">
<head>
  <title>Big Buttons</title>
  <meta charset=utf-8>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script>
    // Assign a class temporarily to an item (defaults to 200ms)
    $.fn.temporaryClass = function(className, time) {
      var that = $(this)
      that.addClass(className)
      setTimeout(function() { that.removeClass(className) }, time || 200)
      return that
    }

    $(document).ready(function() {
      // Actions happen when audio moves
      $('audio').live('dataunavailable', function() { $(this).parent().addClass('dataunavailable') })
                .live('ended', function() { $(this).parent().removeClass('playing') })
                .live('pause', function() { $(this).parent().removeClass('playing') })
                .live('timeupdate', function() { $(this).parent().addClass('playing') })
                .live('play', function() { $(this).parent().addClass('playing') })
                .live('error', function() { $(this).parent().addClass('error') })

                // new audio event to simplify restarting
                .live('restart', function() {
                  if (this.currentTime)
                    this.currentTime = 0
                  this.play()
                })

      $('button').live('click', function() {
        $(this).temporaryClass('active')
               .children('audio').trigger('restart')
      })

      $(window).keypress(function(e) {
        // ESC pauses
        if (e.charCode === 0) {
          $('audio').each(function() { this.pause() })
          return
        }

        // 1->9 presses a button
        var number = parseInt(String.fromCharCode(e.charCode), 10)
        if (number)
          $('button').eq(number-1).click()
      })
    })
  </script>
  <style>
    button {
      width: 80px;
      height: 84px;
      padding-top:63px;
      vertical-align:bottom;
      border: none;
      font: 13px/1 Impact, Haettenschweiler, "Arial Narrow Bold", sans-serif;
      margin:0 0 3em 0;
      background:url(button.png) center 0 no-repeat }
    .active { background-position: center -84px }
    .dataunavailable { opacity: .3 }
    .error { opacity: .3 }
    .playing { color: darkred }
    ol { list-style:none; width: 750px; margin:1em auto }
    li { float:left }
  </style>
</head>
<body>
  <ol>
<? foreach ($sounds as $sound) : ?>
    <li>
      <button>
        <audio src="<?=htmlspecialchars(urlencode($sound))?>" autobuffer></audio>
        <?=htmlspecialchars(str_replace('_', ' ', basename($sound, '.wav')))?>

      </button>
    </li>
<? endforeach; ?>
  </ol>
</body>
</html>
