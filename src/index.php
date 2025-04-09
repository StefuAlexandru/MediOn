<?php
include "connection.php";
date_default_timezone_set('Europe/Bucharest');
session_start();
$likes = 0;
$shares =0;
$color = "#fff";
$canClick ="all";

$sql = "SELECT likes,shares FROM like_share WHERE id = '1'"; 
$result = mysqli_query($con, $sql);
if($result) {
  $row = mysqli_fetch_assoc($result);
  $likes = $row['likes'];
  $shares = $row['shares'];
}

if(isset($_COOKIE['remember_me'])){
  $id = $_COOKIE['remember_me'];
  $_SESSION['user_id'] = $id;
  $_SESSION['logged_in'] = true;
  $sql = "SELECT admin FROM clients WHERE id = '$id'"; 
  $result =  mysqli_query($con,$sql);
  if($result){
    $row = mysqli_fetch_assoc($result);
    $_SESSION['isAdmin'] = $row['admin'];
  }
}


$endtime ="";
if(isset($_SESSION['user_id'])){
  $id = $_SESSION['user_id'];
  $sql = "SELECT gaveLike,subscription FROM clients WHERE id = '$id'"; 
  $result = mysqli_query($con, $sql);
  if($result) {
    $row = mysqli_fetch_assoc($result);
    if($row['gaveLike']){
      setcookie('isLike', 'set', time() + (86400 * 30)); 
      $color = '#beef2b';
      $canClick ="none";
    }
    else{
      
      setcookie('isLike', 'false', time() + (86400 * 30));
    }

    if ($row['subscription'] != NULL) {
      $subscriptionDatetime = new Datetime($row['subscription']);
      $endtime = $row['subscription'];
      $currentDatetime = new DateTime();

      if ($currentDatetime >= $subscriptionDatetime) {
        $sql ="UPDATE clients SET subscription = NULL WHERE id='$id'";
        mysqli_query($con, $sql);
        $_SESSION['isSubscribed']= false;
      } else {
        $_SESSION['isSubscribed']= true;
          $timeDiff = $subscriptionDatetime->diff($currentDatetime);
          $days = $timeDiff->days;
          $hours = $timeDiff->h;
          $minutes = $timeDiff->i;
          $seconds = $timeDiff->s;
      }
    }
  }
}else{
  header("location:logout.php");
  exit();
}

if (isset($_POST['share-button'])) {
  if(isset($_SESSION['user_id'])){
    $sql = "SELECT shares FROM like_share WHERE id ='1'";
    $result = mysqli_query($con, $sql);
    if($result) {
      $row = mysqli_fetch_assoc($result);
      $shares = $row['shares']+1;
      $sql = "UPDATE like_share SET shares = '$shares' WHERE id = '1'";
      mysqli_query($con, $sql);
    }
    Header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }
}

if(isset($_COOKIE['isLike']))
{
  if ($_COOKIE['isLike'] == 'true') {
    $sql = "SELECT likes FROM like_share WHERE id = '1'"; 
    $result = mysqli_query($con, $sql);
    if ($result) {
      $row = mysqli_fetch_assoc($result);
      $likes = $row['likes'] + 1;
      $updateSql = "UPDATE like_share SET likes = $likes WHERE id = '1'";
      mysqli_query($con, $updateSql);
      $updateSql = "UPDATE clients SET gaveLIke = '1' WHERE id = '$id'";
      mysqli_query($con, $updateSql);
      setcookie('isLike', 'set', time() + (86400 * 30)); 
      $color = '#beef2b';
      $canClick ="none";
      

    } else {
      echo "Error: " . mysqli_error($con);
    }
  }

}

?>



<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>MediOn</title>
    
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="templatemo-grad-school.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
    
  </head>

<body>



  <!--header-->
  <header class="main-header clearfix" role="header">
    <div class="logo">
      <a href="#"><em>Medi</em>ON</a>
    </div>
    <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>
    <nav id="menu" class="main-nav" role="navigation">
      <ul class="main-menu">
        <li><a href="#section1">Home</a></li>
        <li class="has-submenu"><a href="#section2">About Us</a>
          <ul class="sub-menu">
            <li><a href="#section2">Who we are?</a></li>
            <li><a href="#section3">What we do?</a></li>
            <li><a href="#section3">How it works?</a></li>
          
          </ul>
        </li>
        <li><a href="#section4">Courses</a></li>
        <li><a href="account.php" class="external">My account</a></li>
        <?php 
          if($_SESSION['isAdmin']){
            echo "<li><a href='admin.php' class='external'>Admin</a></li>";
          }
        ?>
        <li><a href="logout.php"style="border:3px solid #A5C9A7; border-radius:20px;" class="external">Log out</a></li>
      </ul>
    </nav>
  </header>

  <!-- ***** Main Banner Area Start ***** -->
  <section class="section main-banner" id="top" data-section="section1">
      <video autoplay muted loop id="bg-video">
          <source src="assets/images/course-video.mp4" type="video/mp4" />
      </video>

      <div class="video-overlay header-text">
          <div class="caption">
              <h6>Welcome to</h6>
              <h2><em>Medi</em>ON</h2>
              <div class="main-button">
                  <div class="scroll-to-section"><a href="#section2">Discover more</a></div>
              </div>
          </div>
      </div>
  </section>
  <!-- ***** Main Banner Area End ***** -->


  <section class="features">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-12">
          <div class="features-post">
            <div class="features-content">
              <div class="content-show">
                <h4><i class="fa fa-pencil"></i>All Courses</h4>
              </div>
              <div class="content-hide">
                <p>Oferim cursuri personalizate și interactive pentru elevi de toate vârstele și nivelurile, de la începători la avansați.</p>
               
                <div class="scroll-to-section"><a href="#section2">More Info.</a></div>
            </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-12">
          <div class="features-post second-features">
            <div class="features-content">
              <div class="content-show">
                <h4><i class="fa fa-graduation-cap"></i>Virtual Class</h4>
              </div>
              <div class="content-hide">
                <p>Participă la cursuri de oriunde te-ai afla si
                  invață din confortul propriei tale case sau de oriunde te simți cel mai bine conectat.</p>
                 
                <div class="scroll-to-section"><a href="#section3">Details</a></div>
            </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-12">
          <div class="features-post third-features">
            <div class="features-content">
              <div class="content-show">
                <h4><i class="fa fa-book"></i>Real Meeting</h4>
              </div>
              <div class="content-hide">
                <p>În timp ce oferim cursuri virtuale interactive, înțelegem că uneori este posibil să aveți nevoie de asistență suplimentară individuală. Sesiunile noastre personalizate de asistență vă oferă un spațiu dedicat pentru a lucra direct cu instructorul dumneavoastră.</p>
               
                <div class="scroll-to-section"><a href="#section4">Read More</a></div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section why-us" data-section="section2">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="section-heading">
            <h2>De ce sa alegi MediON?</h2>
          </div>
        </div>
        <div class="col-md-12">
          <div id='tabs'>
            <ul>
              <li><a href='#tabs-1'>Best Education</a></li>
              <li><a href='#tabs-2'>Top Teachers</a></li>
              <li><a href='#tabs-3'>Quality Meeting</a></li>
            </ul>
            <section class='tabs-content'>
              <article id='tabs-1'>
                <div class="row">
                  <div class="col-md-6">
                    <img src="assets/images/choose-us-image-01.png" alt="">
                  </div>
                  <div class="col-md-6">
                    <h4>Best Education</h4>
                    <p>Vrei să excelezi în matematică, dar ai nevoie de un sprijin suplimentar? Școala noastră online de meditații la matematică este soluția perfectă pentru tine! Oferim cursuri personalizate și interactive pentru elevi de toate vârstele și nivelurile, de la începători la avansați.</p>
                  </div>
                </div>
              </article>
              <article id='tabs-2'>
                <div class="row">
                  <div class="col-md-6">
                    <img src="assets/images/choose-us-image-02.png" alt="">
                  </div>
                  <div class="col-md-6">
                    <h4>Top Teachers</h4>
                    <p>Platforma are profesori cu o pregătire solidă în matematică și o experiență demonstrată în predarea online.Profesori care sunt răbdători, prietenoși și pasionați de a ajuta elevii să învețe.</p> 
                  
                  </div>
                </div>
              </article>
              <article id='tabs-3'>
                <div class="row">
                  <div class="col-md-6">
                    <img src="assets/images/choose-us-image-03.png" alt="">
                  </div>
                  <div class="col-md-6">
                    <h4>Quality Meeting</h4>
                    <p>Platforma ofera o varietate de formate de lecții, cum ar fi lecții live unu-la-unu, sesiuni de grup, resurse video preînregistrate și materiale interactive. Acest lucru le va permite elevilor să învețe în modul care li se potrivește cel mai bine.</p>
                  </div>
                </div>
              </article>
            </section>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section coming-soon" data-section="section3">
    <div class="container">
      <div class="row">
        <div class="col-md-7 col-xs-12">
          <div class="continer centerIt">
            <div>
              <h4>Pentru a beneficia de <em>cursurile </em>noastre, va trebui sa va faceti un abonament.<br/><br/><p style="font-size:16px;">Abonamentul dumneavostra mai este valabil:</p></h4>
              
              <div class="counter" data-endtime="<?php echo $endtime; ?>" >

                <div class="days">
                  <div class="value" id="days">00</div>
                  <span>Days</span>
                  
                </div>

                <div class="hours">
                  <div class="value" id="hours">00</div>
                  <span>Hours</span>
                </div>

                <div class="minutes">
                  <div class="value" id="minutes">00</div>
                  <span>Minutes</span>
                </div>

                <div class="seconds">
                  <div class="value" id="seconds">00</div>
                  <span>Seconds</span>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="col-md-5" >
            <a href="subscription-page.php" class="button">Cumpara abonamentul</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section courses" data-section="section4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="section-heading">
            <h2>Alege cursul care ti se potriveste</h2>
          </div>
        </div>
        <div class="owl-carousel owl-theme">
          <div class="item">
            <img src="assets/images/algebra.png" alt="Course #1">
            <div class="down-content">
              <h4>Algebra</h4>
              <p>Ești în clasa a VII-a și deja te dor capul de la "X-ul necunoscut"?  Relaxează-te, nu ești singur!<br>
Cursurile noastre online de algebră sunt ca un profesor super cool: explică pe înțelesul tuturor, dă exemple trăsnite și te ajută să rezolvi orice problemă, chiar dacă e mai încâlcită decât nodurile la căști.</p>
              <div class="author-image">
                <img src="assets/images/author-01.png" alt="Author 1">
              </div>
              <div class="text-button-pay">
                <a href="#" >Incearca acum! <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/geomtrie.jpg" alt="Course #2">
            <div class="down-content">
              <h4>Geometrie</h4>
              <p>Dai ochii peste cercuri și triunghiuri și te apucă bâlbâiala?  Nu te strecura sub pat, geometria nu e monstrul din dulap!<br>
Cursurile noastre online te transformă din "elev speriat de compas" în "mic artist geometric".  Învață să calculezi arii, perimetre și unghiuri ca un profesionist, dar fără formule care te lasă perplex.</p>
              <div class="author-image">
                <img src="assets/images/author-02.png" alt="Author 2">
              </div>
              <div class="text-button-pay">
                <a href="#">Incearca acum! <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/analiza.jpg" alt="Course #3">
            <div class="down-content">
              <h4>Analiza</h4>
              <p>Simți că matematica e plină de secrete ascunse pe care doar câțiva aleși le pot descifra? Noi te ajutăm să fii unul dintre ei (fără poțiuni magice)!<br>

Cu cursurile noastre online de Analiză Matematică, te vei transforma într-un ninja al derivatelor, un samurai al integralele și un maestru al limitelor (chiar și pe cele care par să dispară ca iepurii din joben).</p>
              <div class="author-image">
                <img src="assets/images/author-03.png" alt="Author 3">
              </div>
              <div class="text-button-pay">
                <a href="#">Incearca acum! <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/aritmetica.jpeg" alt="Course #4">
            <div class="down-content">
              <h4>Aritmetica</h4>
              <p>Ești sătul de calculele lungi și plictisitoare?  Lasă pe noi treaba asta!<br>
              Cursurile noastre online de Aritmetică te vor transforma din "elev speriat de cifre" în "maestru al calculelor".  Învață trucuri rapide și memorabile să aduni, să scazi, să înmulțești și să împarți ca un ninja al numerelor!

Nu-ți mai trebuie calculator, tu ești calculatorul acum!  Hai să facem matematica distractivă și super cool. 
            </p>
              <div class="author-image">
                <img src="assets/images/author-04.png" alt="Author 4">
              </div>
              <div class="text-button-pay">
                <a href="#">Incearca acum! <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/logica.jpg" alt="">
            <div class="down-content">
              <h4>Logica</h4>
              <p>Ești gata să îți transformi mintea într-o mașinărie logică de ultimă generație?<br>

Cursurile noastre online de Logică Matematică te vor transforma într-un detectiv, capabil să rezolve probleme mai complicate decât cele din "Sherlock Holmes".
Noi îți vom dezvălui secretele logicii matematice într-un mod distractiv și ușor de înțeles.</p>
              <div class="author-image">
                <img src="assets/images/author-05.png" alt="">
              </div>
              <div class="text-button-pay">
                <a href="#">Incearca acum! <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/trigonometrie.jpg" alt="">
            <div class="down-content">
              <h4>Trigonometrie</h4>
              <p>Vino să descoperi lumea fascinantă a trigonometriei alături de noi! <br>Cursul nostru online transformă matematica într-o aventură captivantă. Vom explora unghiuri și triunghiuri într-un mod interactiv și distractiv. Pregătește-te să-ți pui abilitățile la încercare și să devii un maestru al trigonometriei într-un mod plin de energie și entuziasm!</p>
              <div class="author-image">
                <img src="assets/images/author-01.png" alt="">
              </div>
              <div class="text-button-pay">
                <a href="#">Incearca acum! <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
        
        </div>
      </div>
    </div>
  </section>
  

  <section class="section video" data-section="section5">
    <div class="container">
      <div class="row">
        <div class="col-md-6 align-self-center">
          <div class="left-content">
            <span>Acest video este pentru tine!</span>
            <h4>Vizualizeaza acest videoclip sa afli mai multe<em> despre MediON</em></h4>
            
          </div>
        </div>
        <div class="col-md-6">
          <article class="video-item">
            <div class="video-caption">
              <h4>MediON</h4>
            </div>
            <figure>
              <a href="https://www.youtube.com/watch?v=B1J6Ou4q8vE" class="play"><img src="assets/images/stickMan-thumb.jpg"></a>
            </figure>
          </article>
        </div>
      </div>
    </div>
  </section>

  <section class="section contact" data-section="section6">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="section-heading">
            <h2>Incantati de cunostiinta!</h2>
          </div>
        </div>
        <div class="col-md-6">
            <div class="sidemap" style="display:flex; flex-direction:column; align-items:center;">
                <h4>Ne poti gasi aici!</h4>
                <p style="font-size:20px; color:#beef8b; margin:10px 0px 10px 0px;">Enjoy some music!</p>
                <audio id="myAudio" controls loop>
                  <source src="assets/Forest.mp3" type="audio/mpeg">
                </audio>
                  </div>
        </div>
        <div class="col-md-6">
          <div id="map">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5424.511841953988!2d27.56794389935186!3d47.17242568461309!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40cafb61af5ef507%3A0x95f1e37c73c23e74!2sUniversitatea%20%E2%80%9EAlexandru%20Ioan%20Cuza%E2%80%9D!5e0!3m2!1sro!2sro!4v1715512379187!5m2!1sro!2sro" width="600" height="450" style="border:2px solid #beef8b; border-radius:50px;" allowfullscreen="yes" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="social-sharing">
          <h4>Share us!</h4>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button type="submit" name="share-button" data-url="https://www.facebook.com/">
              <i class="bi bi-facebook"></i>
            </button>
            <button type="submit" name="share-button" data-url="https://www.instagram.com/">
              <i class="bi bi-instagram"></i>
            </button>
            <button type="submit" name="share-button" data-url="https://www.twitter.com/">
              <i class="bi bi-twitter"></i>
            </button>
            <button type="submit" name="share-button" data-url="https://www.tiktok.com/">
              <i class="bi bi-tiktok"></i>
            </button>
          </form>

          <script>
          const shareButtons = document.querySelectorAll('button[name="share-button"]');

          shareButtons.forEach(button => {
            button.addEventListener('click', (event) => {
              
              const shareUrl = button.dataset.url;
              window.open(shareUrl, '_blank'); 
                
            });
          });
          </script>
        </div>
        <div class="count">
          <i class="fa fa-thumbs-up" id ='like-button'  style="color:<?php echo($color);?>; pointer-events:<?php echo $canClick;?>;" onclick="handleClick('likes-count')"></i>
          <span id="likes-count"><?php echo $likes;?></span>Likes
          <span ids="shares-count"><?php echo $shares;?></span>Shares
        </div>
      </div>
    </div>
  </div>
</footer>

  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/lightbox.js"></script>
    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/video.js"></script>
    <script src="assets/js/slick-slider.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="LikeAndShare.js"></script>
    <script>
      
        $('.nav li:first').addClass('active');
       
        var showSection = function showSection(section, isAnimate) {
          var
          direction = section.replace(/#/, ''),
          reqSection = $('.section').filter('[data-section="' + direction + '"]'),
          reqSectionPos = reqSection.offset().top - 0;

          if (isAnimate) {
            $('body, html').animate({
              scrollTop: reqSectionPos },
            800);
          } else {
            $('body, html').scrollTop(reqSectionPos);
          }

        };

        var checkSection = function checkSection() {
          $('.section').each(function () {
            var
            $this = $(this),
            topEdge = $this.offset().top - 80,
            bottomEdge = topEdge + $this.height(),
            wScroll = $(window).scrollTop();
            if (topEdge < wScroll && bottomEdge > wScroll) {
              var
              currentId = $this.data('section'),
              reqLink = $('a').filter('[href*=\\#' + currentId + ']');
              reqLink.closest('li').addClass('active').
              siblings().removeClass('active');
            }
          });
        };

        $('.main-menu, .scroll-to-section').on('click', 'a', function (e) {
          if($(e.target).hasClass('external')) {
            return;
          }
          e.preventDefault();
          $('#menu').removeClass('active');
          showSection($(this).attr('href'), true);
        });

        $(window).scroll(function () {
          checkSection();
        });
    </script>
</body>
</html>