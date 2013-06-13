jQuery(function($) {
  // Keyboard keys that press buttons
  // For qwerty use '1234567890QWERTYIOPASDFGHJKL;ZXCVBN'
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
        e.keyIndex = keyboard.indexOf(e.keyString)
        return callback.call(this, e)
      }
    })
  }


  // Clicking a button starts audio
  $(document).on('click', 'button', function() {
    $(this).temporaryClass('active')
           .children('audio').trigger('playtoggle')
  })

  $(document).singleKeyDown(function(e) {
    // Escape pauses
    if (e.keyString === 'escape')
      $('audio').each(function() { this.pause() })

    // Keyboard presses buttons
    else if (e.keyIndex != -1)
      $('button').eq(e.keyIndex).click()
  })

  $(document).ready(function() {
    var audios = $('audio'),
        buttons = $('button')

    audios.on('dataunavailable', function() { $(this).parent().addClass('dataunavailable') })
    audios.on('play',            function() { $(this).parent().addClass('playing') })
    audios.on('error',           function() { $(this).parent().addClass('error') })
    audios.on('ended pause',     function() { $(this).parent().removeClass('playing') })

    // custom audio event to simplify restarting
    audios.on('playtoggle', function() {
      if (this.paused) {
        this.play()
      } else {
        this.currentTime = 0
        this.pause()
      }
    })

    // Show the keyboard keys to press
    $('button:lt('+keyboard.length+')').each(function(i) {
      $(this).append('<span class="key">' + keyboard[i] + '</span>')
    })
  })

})
