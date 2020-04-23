<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Musicfy | Welcome</title>
    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/public/app.css">
    <style>
      .main {
        position: relative;
        width: 100%;
        height: 75vh;
        display: flex;
        justify-content: center;
        overflow: hidden;
      }
      .main::before {
        content: '';
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgb(77,90,234);
        background: -moz-linear-gradient(-45deg,  rgba(77,90,234,1) 0%, rgba(119,134,244,1) 100%);
        background: -webkit-linear-gradient(-45deg,  rgba(77,90,234,1) 0%,rgba(119,134,244,1) 100%);
        background: linear-gradient(135deg,  rgba(77,90,234,1) 0%,rgba(119,134,244,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4d5aea', endColorstr='#7786f4',GradientType=1 );
        border-radius: 0 0 50% 50% / 0 0 100% 100%;
        transform: scaleX(1.5);
      }
      .main .content {
        position: relative;
        z-index: 1;
        margin: 0 auto;
        text-align: center;
        padding-top: 100px;
      }
      .main .content h1 {
        color: white;
        margin-bottom: 2vw;
        font-size: 3vw;
      }
      .main .content p {
        color: white;
        max-width: 30vw;
        font-size: 1vw;
        margin-bottom: 0.8vw;
      }
      .main .content .btn {
        background: white;
        color: #4d5aea;
      }
      .main .content .btn:hover {
        background: white;
      }

      #musicfy-img {
        max-width: 50vw;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0 auto;
        border: 2vw solid white;
        border-radius: 2vw 2vw 0 0;
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
      }
    </style>
  </head>
  <body>

    <main class="main">
      <div class="content">
        <h1>Musicfy</h1>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipisicing elit,
          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
        <a class="btn btn-primary" href="/home">Lets Go</a>
      </div>
    </main>

    <img src="/public/images/musicfy.png" alt="musicfy" id="musicfy-img">

    <script src="http://localhost:8080/app.js"></script>
  </body>
</html>
