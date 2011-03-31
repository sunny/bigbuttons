<!doctype html>
<html lang="en">
<head>
  <title>Big Buttons</title>
  <meta charset=utf-8>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script>
    // Keyboard keys that press buttons
    // For qwerty, use:
    //   var keyboard = '1234567890QWERTYIOPASDFGHJKL;ZXCVBN'
    var keyboard = '1234567890AZERTYUIOPQSDFGHJKLMWXCVBN'

    // Assign a class temporarily to an item (defaults to 200ms)
    $.fn.temporaryClass = function(className, time) {
      var that = $(this)
      that.addClass(className)
      setTimeout(function() { that.removeClass(className) }, time || 200)
      return that
    }

    // Keydown that disregards keys pressed with control or command
    // and adds the keyString to the event
    $.fn.singleKeyDown = function(callback) {
      function keyString(code) {
        if (code >= 96 && code <= 105)
          return parseInt(code - 96)
        switch (code) {
          case 27: return 'escape'
          default: return String.fromCharCode(code)
        }
      }
      return $(this).keydown(function(e) {
        if (!e.ctrlKey && !e.metaKey) {
          e.keyString = keyString(e.keyCode)
          return callback.call(this, e)
        }
      })
    }

    $(document).ready(function() {
      // Actions happen when audio plays
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

      // Show the keyboard keys to press
      $('button').each(function(i) {
        if (i < keyboard.length)
          $(this).append('<span>' + keyboard[i] + '</span>')
      })

      // Clicking a button starts audio
      .live('click', function() {
        $(this).temporaryClass('active')
               .children('audio').trigger('restart')
      })

      $(document).singleKeyDown(function(e) {
        // Escape pauses
        if (e.keyString === 'escape') {
          $('audio').each(function() { this.pause() })
          return
        }

        // Keyboard presses buttons
        var number = keyboard.indexOf(e.keyString)
        if (number != -1)
          $('button').eq(number).click()

      })
    })
  </script>
  <style>
    button {
      cursor: pointer;
      width: 80px;
      height: 84px;
      vertical-align:bottom;
      color: white;
      font: bold 13px/1 Helvetica, sans-serif;
      margin: 10px;

      border: 2px solid #333;
      -webkit-border-radius: 40px;
      -moz-border-radius: 40px;
      -o-border-radius: 40px;
      border-radius: 40px;

      background: black;
      background: -webkit-gradient(linear, left top, left bottom, from(#666), to(#000));
      background: -moz-linear-gradient(-90deg, #666, #000);

      -webkit-box-shadow: 2px 2px 20px rgba(0,0,0,.9);
      -moz-box-shadow: 2px 2px 20px rgba(0,0,0,.9);
      -o-box-shadow: 2px 2px 20px rgba(0,0,0,.9);
      box-shadow: 2px 2px 20px rgba(0,0,0,.9)}

    button:focus {
      outline: 1px solid rgba(0,0,0,.1);
      -webkit-outline-radius: 60px;
      -moz-outline-radius: 60px;
      -o-outline-radius: 60px;
      outline-radius: 60px}

    button::-moz-focus-inner {
      border: none}

    button span {
      color: grey;
      font-size: .9em;
      position: absolute;
      top: 0;
      left: 48%}

    .active {
      background: -webkit-gradient(linear, left top, left bottom, from(#555), to(#000));
      background: -moz-linear-gradient(-90deg, #555, #000)}
    .active span {
      color: black}

    .dataunavailable,
    .error {
      opacity: .3 }

    .playing {
      -webkit-box-shadow: 2px 2px 20px rgba(150,0,0,.9);
      -moz-box-shadow: 2px 2px 20px rgba(150,0,0,.9);
      -o-box-shadow: 2px 2px 20px rgba(150,0,0,.9);
      box-shadow: 2px 2px 20px rgba(150,0,0,.9)}

    ol {
      width: 1000px;
      margin:1em auto}
    li {
      float:left;
      position: relative;
      color: #aaa;
      font: 13px/1 Helvetica, sans-serif;
      list-style: none;
      padding-top: 5px}
  </style>
</head>
<body>
  <ol>
<? foreach (glob('*.wav') as $sound) : ?>
    <li>
      <button>
        <audio src="<?=htmlspecialchars(urlencode($sound))?>" autobuffer></audio>
        <?=htmlspecialchars(trim(preg_replace('/^[0-9]+|\.wav$|_+/i', ' ', $sound)))?>

      </button>
    </li>
<? endforeach; ?>
  </ol>
</body>
</html>
