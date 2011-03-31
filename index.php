<!doctype html>
<html lang="en">
<head>
  <title>Big Buttons</title>
  <meta charset=utf-8>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script>
    // Assign a class temporarily to an item (defaults to 200ms)
    $.fn.temporaryClass = function(className, time) {
      var that = $(this)
      that.addClass(className)
      setTimeout(function() { that.removeClass(className) }, time || 200)
      return that
    }

    // Keydown that disregards keys pressed with control or command
    // and adds the keyName to the event
    $.fn.singleKeyDown = function(callback) {
      function keyName(code) {
        if (code >= 96 && code <= 105)
          return parseInt(code - 96)
        switch (code) {
          case 17: return 'control'
          case 27: return 'escape'
          case 224: return 'command'
          default: return String.fromCharCode(code)
        }
      }
      var upKeys = {}
      $(this).keydown(function(e) {
        e.keyName = keyName(e.keyCode)
        if (e.keyName == 'control' || e.keyName == 'command')
          upKeys[e.keyName] = true
        else if (!upKeys.control && !upKeys.command)
          return callback.call(this, e)
      }).keyup(function(e) {
        e.keyName = keyName(e.keyCode)
        if (e.keyName == 'control' || e.keyName == 'command')
          upKeys[e.keyName] = false
      })
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

      $(document).singleKeyDown(function(e) {
        // escape pauses
        if (e.keyName === 'escape') {
          $('audio').each(function() { this.pause() })
          return
        }

        // 1-9 + azerty presses a button
        var azerty = ['A','Z','E','R','T','Y','U','I','O','P','Q','S','D','F','G','H','J','K','L','M','W','X','C','V','B','N'],
            number = azerty.indexOf(e.keyName) != -1 ? azerty.indexOf(e.keyName) + 1 : e.keyName
        $('button').eq(number-1).click()
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
      box-shadow: 2px 2px 20px rgba(0,0,0,.9);}

    button:focus {
      outline: 1px solid rgba(0,0,0,.1);
      -webkit-outline-radius: 60px;
      -moz-outline-radius: 60px;
      -o-outline-radius: 60px;
      outline-radius: 60px;}

    button::-moz-focus-inner {
      border: none; }

    .active {
      background: -webkit-gradient(linear, left top, left bottom, from(#555), to(#000));
      background: -moz-linear-gradient(-90deg, #555, #000); }

    .dataunavailable,
    .error {
      opacity: .3 }

    .playing {
      -webkit-box-shadow: 2px 2px 20px rgba(150,0,0,.9);
      -moz-box-shadow: 2px 2px 20px rgba(150,0,0,.9);
      -o-box-shadow: 2px 2px 20px rgba(150,0,0,.9);
      box-shadow: 2px 2px 20px rgba(150,0,0,.9);}

    ol {
      width: 900px;
      margin:1em auto; }
    li {
      float:left;
      color: #aaa;
      font: 13px/1 Helvetica, sans-serif;
      list-style: none;}
    li:nth-child(1), li:nth-child(2), li:nth-child(3),
    li:nth-child(4), li:nth-child(5), li:nth-child(6),
    li:nth-child(7), li:nth-child(8), li:nth-child(9) {
      list-style-type: decimal; }
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
