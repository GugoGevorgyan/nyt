<style>
  /* Set height to 100% for body and html to enable the background image to cover the whole page: */
  body, html {
    height: 100%;
    margin: 0 !important;
    padding: 0 !important;
  }

  .bgimg {
    /* Background image */
    background: linear-gradient(-45deg, #9e8923, #c19f1d, #ffd501);

    /* Full-screen */
    height: 100%;
    /* Center the background image */
    background-position: center;
    /* Scale and zoom in the image */
    background-size: cover;
    /* Add position: relative to enable absolutely positioned elements inside the image (place text) */
    position: relative;
    /* Add a white text color to all elements inside the .bgimg container */
    color: white;
    /* Add a font */
    font-family: "Courier New", Courier, monospace;
    /* Set the font-size to 25 pixels */
    font-size: 25px;
  }

  /* Position text in the top-left corner */
  .topleft {
    position: absolute;
    top: 0;
    left: 16px;
  }

  /* Position text in the bottom-left corner */
  .bottomleft {
    position: absolute;
    bottom: 0;
    left: 16px;
  }

  /* Position text in the middle */
  .middle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
  }

  /* Style the <hr> element */
  hr {
    margin: auto;
    width: 40%;
  }
</style>


<div class="bgimg">
  <div class="topleft">
    <a href="/"><img src="{{ asset('storage/img/logos/logo.png') }}" alt=""></a>
  </div>
  <div class="middle">
    <h1>COMING SOON</h1>
    <hr>
    <p id="demo">35 days</p>
  </div>
  <div class="bottomleft">
    <p>Some text</p>
  </div>
</div>


<script>
  let countDownDate = new Date('Jan 5, 2022 15:37:25').getTime();

  let x = setInterval(function() {

    let now = new Date().getTime();
    let distance = countDownDate - now;

    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('demo').innerHTML = days + ' days'/* + hours + 'h ' + minutes + 'm ' + seconds + 's '*/;

    if (0 > distance) {
      clearInterval(x);
      document.getElementById('demo').innerHTML = 'EXPIRED';
    }
  }, 1000);
</script>
