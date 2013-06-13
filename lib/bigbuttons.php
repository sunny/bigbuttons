<?php

// Sound class that represents a WAV file

class Sound {
  function __construct($path) {
    $this->path = $path;
  }

  // File basename without extension
  function basename() {
    return preg_replace('/\..+?$/', '', basename($this->path));
  }

  // Name without leading numbers or underscores
  function name() {
    $base = $this->basename();
    $name = trim(preg_replace('/^[0-9]+|_+/i', ' ', $base));
    return $name ? $name : $base;
  }

  // Return all .wav sounds in folder
  static function all() {
    $sounds = array();
    foreach (glob('sounds/*.wav') as $path)
      $sounds[] = new Sound($path);
    return $sounds;
  }
}

// Helpers

function h($s) {
  return htmlspecialchars($s);
}
