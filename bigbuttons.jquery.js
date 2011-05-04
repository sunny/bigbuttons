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
