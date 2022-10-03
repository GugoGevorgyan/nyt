<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Document</title>
  <script type="text/php">
    if (isset($pdf)) {
        $x = 570;
        $y = 750;
        $text = "{PAGE_NUM}";
        $font = null;
        $size = 14;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }


  </script>
</head>
<body>
@foreach($images as $key => $image)
  <img src="{{ $path.$image }}" alt="images">
@endforeach
</body>
</html>
