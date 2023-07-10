<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>لمعة غسيل السيارات بالبخار</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.svg" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('website/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('website/assets/css/animate.css')}}" />
    <link rel="stylesheet" href="{{asset('website/assets/css/tiny-slider.css')}}" />
    <link rel="stylesheet" href="{{asset('website/assets/css/glightbox.min.css')}}" />
    <link rel="stylesheet" href="{{asset('website/assets/css/main.css')}}" />

 <style>

    #btn-alt:hover {

        color: #fff;
        background-color: #25D366;
        -webkit-box-shadow: 0px 4px 4px #0000000f;
        box-shadow: 0px 4px 4px #0000000f;

     }

     #btn-alt {

        color: #fff !important;
        background:#25D366 !important;
        border: 2px solid #fff;
        padding: 11px 30px;
        width:250px;
        height:55px;

     }

    .icons .social {

        margin-top: 50px;
        text-align:right;
    }

    .icons .social li {

        display: inline-block;
        margin-right:50px;
    }

    .icons .social li:last-child {

        margin: 0;
    }

    .icons .social li a {

        color: #D2D6DC;
        font-size:30px;
    }

    .icons .social li a:hover {

        color: #D2D6DC;
    }

    .icons .social li:last-child {

        margin: 0;
    }
    
  @media screen and (max-width: 667px) {

       body {
           
         overflow-x: hidden !important;
         width:100%;

       }
      .container{
           
           overflow-x: hidden !important;
        }
      
       .lirights{
         
            padding-left:50px;
        }

       #btn-alt:hover {

        color: #fff;
        background-color: #25D366;
        -webkit-box-shadow: 0px 4px 4px #0000000f;
        box-shadow: 0px 4px 4px #0000000f;
  
      }

      #btn-alt {

         color: #fff !important;
         background:#25D366 !important;
         border: 2px solid #fff;
         padding: 11px 30px;
         width:250px;
         height:55px;

     }
}

 </style>
</head>

        <body style="direction:rtl">
            <div class="preloader">
                <div class="preloader-inner">
                    <div class="preloader-icon">
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>

                <header class="header navbar-area">
                   <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                               <div class="nav-inner">
                                    <nav class="navbar navbar-expand-lg">
                                         <a  class="navbar-brand" href="">
                                            <img src="{{asset('website/images/lamalogo.png')}}" style="height:100px;">
                                        </a>
                                      <button class="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse"
                                          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                           aria-expanded="false" aria-label="Toggle navigation">
                                              <span class="toggler-icon"></span>
                                              <span class="toggler-icon"></span>
                                            <span class="toggler-icon"></span>
                                      </button>
                                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                       <ul id="nav" class="navbar-nav ms-auto">
                                           <li class="nav-item">
                                              <a href="#home" class="page-scroll active"
                                                   aria-label="Toggle navigation">الرئيسية</a>
                                          </li>
                                          <li class="nav-item">
                                               <a href="#features" class="page-scroll"
                                                  aria-label="Toggle navigation">المميزات</a>
                                          </li>
                                           <li class="nav-item">
                                              <a href="#overview" class="page-scroll"
                                                 aria-label="Toggle navigation">الخدمات</a>
                                           </li>
                                           <li class="nav-item" style="padding-right:30px;">
                                              <a href="#contactus" class="page-scroll"
                                                  aria-label="Toggle navigation">تواصل معنا</a>
                                          </li>
                                      </ul>
                                 </div>
                              </nav>
                          </div>
                      </div>
                 </div> 
            </div> 
    </header>
    
      <section id="home" class="hero-area">
          <div class="container">
               <div class="row" >
                     <div class="col-lg-6 col-md-12 col-12">
                         <br>
                         <br>
                         <div class="hero-content">
                              <h1 class="wow fadeInLeft" style="text-align:right" data-wow-delay=".4s">عن لمعة</h1>
                              <h4 class="wow fadeInLeft" style="text-align:right" data-wow-delay=".4s">{{$gs->aboutus_title}}</h4>
                              <p class="wow fadeInLeft" style="text-align:right" data-wow-delay=".6s">{{$gs->aboutus_description}}</p>
                        
                                <div class="icons">
                                     <ul class="social">
                                           <li><a href="https://twitter.com/lamaa_carwash?s=11&t=tpdLgHqkskZo6wDAaa99jg" target="__blank"><i class="lni lni-twitter-original"></i></a></li>
                                           <li><a href="https://www.instagram.com/lamaa.carwash/" target="__blank"><i class="lni lni-instagram"></i></a></li>
                                          <li style="padding-right:40px"><a href="https://www.tiktok.com/@lamaa.carwash" target="__blank"><i class="lni lni-tiktok"></i></a></li>
                                     </ul>
                                 </div>
 
                                  <div class="button wow fadeInLeft" data-wow-delay=".8s">
                                        <a href="https://wa.me/message/ZGO6L4JSJ3AAL1"  id="btn-alt"   target="__blank" style="float:right" class="btn btn-alt"><i class="lni lni-whatsapp"></i>&nbsp;  احجز عبر الواتس
                                       </a>
                                  </div>
                             </div>
                        </div>
                         <div class="col-lg-6 col-md-12 col-12">
                             <div class="hero-image wow fadeInRight" data-wow-delay=".4s">
                                   <img src="{{asset('website/images/aboutus.png')}}"  alt="#">
                             </div>
                         </div>
                     </div>
               </div>
            </section>

             <section id="features" class="features section">
                 <div class="container">
                     <div class="row">
                           <div class="col-12">
                              <div class="section-title">
                                  <h2 class="wow zoomIn" data-wow-delay=".2s">الرؤية</h2>
                                  <h3 class="wow fadeInUp" data-wow-delay=".4s">{{$gs->vision_title}}
                                  </h3>
                                   <h3>المدير العام مهندس /  ناصر عبد الرحمن النفيسة</h3>
                                  <p class="wow fadeInUp" data-wow-delay=".6s">{{$gs->vision_description}}</p>
                               </div>
                           </div>
                      </div>
                  </div>
            </section>
    
              <section id="features" class="features section">
                  <div class="container">
                     <div class="row">
                         <div class="col-12">
                            <div class="section-title">
                                <h1 class="wow zoomIn" data-wow-delay=".2s">المميزات<h1>
                            </div>
                      </div>
                  </div>

                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-12">
                         <div  class="single-feature wow fadeInUp" data-wow-delay=".2s">
                              <i class="lni lni-investment"></i>                    
                              <h3 style="text-align:right">{{__('admin.management')}}</h3>
                              <p style="text-align:right">{{$gs->management}} </p>
                          </div>
                      </div>

                    <div class="col-lg-6 col-md-6 col-12">
                         <div class="single-feature wow fadeInUp" data-wow-delay=".4s">
                              <i class="lni lni-grow"></i>     
                              <h3 style="text-align:right" >{{__('admin.development')}}</h3>
                              <p style="text-align:right" >{{$gs->develop}}</p>
                        </div>
                   </div>

                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="single-feature wow fadeInUp" data-wow-delay=".6s">
                            <i class="lni lni-share-alt"></i>     
                            <h3 style="text-align:right">{{__('admin.evaluation')}}</h3>
                            <p style="text-align:right" > {{$gs->evaluation}}</p>
                         </div>
                    </div>

                      <div class="col-lg-6 col-md-6 col-12">
                         <div class="single-feature wow fadeInUp" data-wow-delay=".2s">
                              <i class="lni lni-invention"></i>  
                              <h3 style="text-align:right" >{{__('admin.goal')}}</h3>
                              <p style="text-align:right" >{{$gs->goal}}</p>
                          </div>
                      </div>
                  </div>
              </div>
          </section>

             <section id="overview" class="app-info section">
                <div class="container">
                   <div class="row">
                       <div class="col-12">
                           <div class="section-title">
                               <h1 class="wow zoomIn" data-wow-delay=".2s">{{__('admin.services')}}</h1>
                           </div>
                      </div>
                 </div>
                 @foreach($services as $service)
                 @if($service->type == 1)
                <div class="info-one">
                   <div class="row align-items-center">
                       <div class="col-lg-6 col-md-12 col-12">
                         <div class="info-text wow fadeInLeft" data-wow-delay=".3s">
                            {{--<div class="main-icon">
                               <i class="lni lni-inbox"></i>
                            </div>--}}
                             <h2>{{$service->title}}</h2>
                              <p>{{$service->description}}</p>
                          </div>
                       </div>
                      <div class="col-lg-6 col-md-12 col-12">
                         <div class="info-image wow fadeInRight" data-wow-delay=".5s">
                            <img class="ss1" src="{{$service->img}}" alt="#">
                        </div>
                     </div>
                 </div>
             </div>
             @else
             <div class="info-one style2">
                 <div class="row align-items-center">
                     <div class="col-lg-6 col-md-12 col-12">
                          <div class="info-image wow fadeInLeft" data-wow-delay=".3s">
                             <img class="ss1" src="{{$service->img}}" alt="#">
                          </div>
                     </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="info-text wow fadeInRight" data-wow-delay=".5s">
                              {{--<div class="main-icon">
                                   <i class="lni lni-layout"></i>
                              </div>--}}
                                  <h2>{{$service->title}}</h2>
                                  <p>{{$service->description}}</p>
                             </div>
                         </div>
                     </div>
                </div>
                 @endif
                 @endforeach
             </div>
        </section>
  
          <section class="section call-action">
                <div class="container">
                   <div class="row">
                       <div class="col-lg-8 offset-lg-2 col-md-12 col-12">
                           <div class="cta-content">
                              <h2 class="wow fadeInUp" data-wow-delay=".2s"></h2>
                                <div class="button wow fadeInUp" data-wow-delay=".6s">
                                   <a href="https://wa.me/message/ZGO6L4JSJ3AAL1"   target="__blank" class="btn">احجز عبر الواتس</a>
                               </div>
                           </div>
                       </div>
                   </div>
              </div>
        </section>

    {{--<section class="our-achievement section">
          <div class="container">
              <div class="row">
                 <div class="col-lg-10 offset-lg-1 col-md-12 col-12">
                    <div class="title">
                        <h2>Trusted by developers from over 80 planets</h2>
                        <p>There are many variations of passages of Lorem Ipsum available, but the majority.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12 col-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="single-achievement wow fadeInUp" data-wow-delay=".2s">
                                <h3 class="counter"><span id="secondo1" class="countup" cup-end="100">100</span>%</h3>
                                <p>satisfaction</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="single-achievement wow fadeInUp" data-wow-delay=".4s">
                                <h3 class="counter"><span id="secondo2" class="countup" cup-end="120">120</span>K</h3>
                                <p>Happy Users</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="single-achievement wow fadeInUp" data-wow-delay=".6s">
                                <h3 class="counter"><span id="secondo3" class="countup" cup-end="125">125</span>k+</h3>
                                <p>Downloads</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
    

     {{--<section id="team" class="team section">
          <div class="container">
             <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">Team</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">Meat our team</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">There are many variations of passages of Lorem
                            Ipsum available, but the majority have suffered alteration in some form.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Single Team -->
                    <div class="single-team wow fadeInUp" data-wow-delay=".2s">
                        <div class="image">
                            <img src="https://via.placeholder.com/350x230" alt="#">
                        </div>
                        <div class="content">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-12">
                                    <div class="text">
                                        <h3><a href="javascript:void(0)">Leonard Krasner</a></h3>
                                        <h5>Senior Designer</h5>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <ul class="social">
                                        <li><a href="javascript:void(0)"><i class="lni lni-twitter-original"></i></a>
                                        </li>
                                        <li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-team wow fadeInUp" data-wow-delay=".4s">
                        <div class="image">
                            <img src="https://via.placeholder.com/350x230" alt="#">
                        </div>
                        <div class="content">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-12">
                                    <div class="text">
                                        <h3><a href="javascript:void(0)">Leonard Krasner</a></h3>
                                        <h5>Senior Designer</h5>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <ul class="social">
                                        <li><a href="javascript:void(0)"><i class="lni lni-twitter-original"></i></a>
                                        </li>
                                        <li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-team wow fadeInUp" data-wow-delay=".6s">
                        <div class="image">
                            <img src="https://via.placeholder.com/350x230" alt="#">
                        </div>
                        <div class="content">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-12">
                                    <div class="text">
                                        <h3><a href="javascript:void(0)">Leonard Krasner</a></h3>
                                        <h5>Senior Designer</h5>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <ul class="social">
                                        <li><a href="javascript:void(0)"><i class="lni lni-twitter-original"></i></a>
                                        </li>
                                        <li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
    
   {{--<section class="testimonials section">
           <img class="testi-patern1" src="assets/images/testimonial/testi-patern.svg" alt="#">
           <img class="testi-patern2" src="assets/images/testimonial/testi-patern.svg" alt="#">
           <div class="container">
              <div class="row">
                   <div class="col-lg-8 offset-lg-2 col-md-12 col-12">
                       <div class="testimonial-slider">
                         <div class="single-testimonial">
                            <div class="text">
                                <div class="brand-icon">
                                    <img src="https://via.placeholder.com/140x60" alt="#">
                                </div>
                                <p>"It is a long established fact that a reader will be distracted by the readable
                                    content of a page when looking at its layout. The point of using Lorem Ipsum is
                                    that it has"</p>
                            </div>
                            <div class="author">
                                <img src="https://via.placeholder.com/160x160" alt="#">
                                <h4 class="name">
                                    Musharof Chowdhury
                                    <span class="deg">CEO - Ayro UI</span>
                                </h4>
                            </div>
                        </div>
                       
                        <div class="single-testimonial">
                            <div class="text">
                                <div class="brand-icon">
                                    <img src="https://via.placeholder.com/140x60" alt="#">
                                </div>
                                <p>"It is a long established fact that a reader will be distracted by the readable
                                    content of a page when looking at its layout. The point of using Lorem Ipsum is
                                    that it has"</p>
                            </div>
                             <div class="author">
                                 <img src="https://via.placeholder.com/160x160" alt="#">
                                   <h4 class="name">
                                        Musharof Chowdhury
                                      <span class="deg">CEO - GrayGrids</span>
                                </h4>
                            </div>
                        </div>
                      
                        <div class="single-testimonial">
                            <div class="text">
                                <div class="brand-icon">
                                    <img src="https://via.placeholder.com/140x60" alt="#">
                                </div>
                                <p>"It is a long established fact that a reader will be distracted by the readable
                                    content of a page when looking at its layout. The point of using Lorem Ipsum is
                                    that it has"</p>
                              </div>
                              <div class="author">
                                 <img src="https://via.placeholder.com/160x160" alt="#">
                                 <h4 class="name">
                                      Naimur Rahman
                                     <span class="deg">CEO - WpthemesGrid</span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
   
      {{--<section class="section call-action">
           <div class="container">
              <div class="row">
                 <div class="col-lg-8 offset-lg-2 col-md-12 col-12">
                     <div class="cta-content">
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Install Appvilla and Start Using</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">There are many variations of passages of Lorem
                            Ipsum available, but the majority have
                            suffered alteration in some form, by injected humour, or randomised words which don't look
                            even slightly believable.</p>
                            <div class="button wow fadeInUp" data-wow-delay=".6s">
                                <a href="javascript:void(0)" class="btn"><i class="lni lni-apple"></i> App Store</a>
                                <a href="javascript:void(0)" class="btn btn-alt"><i class="lni lni-play-store"></i> Google
                                 Play</a>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
      </section>--}}
   

  
    <footer class="footer" id="contactus">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="single-footer f-about">
                            <div class="logo">
                                <a href="">
                                    <img src="{{asset('website/images/lamaa-logo.png')}}">
                                </a>
                            </div>
                            <p></p>
                        </div>
            
                    </div>
                      <div class="col-lg-8 col-md-8 col-12">
                          <div class="row">
                             {{--<div class="col-lg-3 col-md-6 col-12">
                                  <div class="single-footer f-link">
                                    <h3>Solutions</h3>
                                    <ul>
                                        <li><a href="javascript:void(0)">Marketing</a></li>
                                        <li><a href="javascript:void(0)">Analytics</a></li>
                                        <li><a href="javascript:void(0)">Commerce</a></li>
                                        <li><a href="javascript:void(0)">Insights</a></li>
                                        <li><a href="javascript:void(0)">Promotion</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="single-footer f-link">
                                    <h3>Support</h3>
                                    <ul>
                                        <li><a href="javascript:void(0)">Pricing</a></li>
                                        <li><a href="javascript:void(0)">Documentation</a></li>
                                        <li><a href="javascript:void(0)">Guides</a></li>
                                        <li><a href="javascript:void(0)">API Status</a></li>
                                        <li><a href="javascript:void(0)">Live Support</a></li>
                                    </ul>
                                </div>
                            </div>--}}
                            <div class="col-lg-6 col-md-8 col-12">
                                <div class="single-footer f-link">
                                 <ul style="padding-right:40px;">
                                     <li class="lirights" style="direction:ltr; font-size:20px"><a href="javascript:void(0)"></a>&nbsp; +966 56 027 1012   &nbsp;اتصل بنا</li>
                                 </ul>
                              </div>

                                <div class="icons">
                                   <ul class="social">
                                     <!--<li><a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a></li>!-->
                                     <li><a href="{{$gs->twitter}}"   target="__blank"><i class="lni lni-twitter-original"></i></a></li>
                                     <li><a href="{{$gs->instagram}}" target="__blank"><i class="lni lni-instagram"></i></a></li>
                                     <li style="padding-right:40px;"><a href="{{$gs->tiktok}}" target="__blank"><i class="lni lni-tiktok"></i></a></li>
                                    </ul>
                                 </div>
                                   <br>
                                   <br>
                                   <br>
                                    <p  class="copyrights" style="font-size:15px; padding-right:40px;">تم تطويره بواسطة شركة  <a href="https://www.badee.com.sa/"
                                        rel="nofollow" target="_blank">بديع الحلول</a>
                                   </p>
                               </div>
                           </div>
                      </div>
                  </div>
              </div>
         </div>

        {{--<div class="footer-newsletter">
              <div class="container">
                <div class="inner-content">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-5 col-12">
                            <div class="title">
                                <h3>Subscribe to our newsletter</h3>
                                <p>The latest news, articles, and resources, sent to your inbox weekly.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-7 col-12">
                            <div class="form">
                                <form action="#" method="get" target="_blank" class="newsletter-form">
                                    <input name="EMAIL" placeholder="Your email address" type="email">
                                    <div class="button">
                                        <button class="btn">Subscribe<span class="dir-part"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
        <!-- End Footer Newsletter -->
    </footer>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    <script src="{{asset('website/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('website/assets/js/wow.min.js')}}"></script>
    <script src="{{asset('website/assets/js/tiny-slider.js')}}"></script>
    <script src="{{asset('website/assets/js/glightbox.min.js')}}"></script>
    <script src="{{asset('website/assets/js/count-up.min.js')}}"></script>
    <script src="{{asset('website/assets/js/main.js')}}"></script>
    <script type="text/javascript">
        //======== tiny slider
        tns({
            container: '.client-logo-carousel',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 15,
            nav: false,
            controls: false,
            responsive: {
                0: {
                    items: 1,
                },
                540: {
                    items: 2,
                },
                768: {
                    items: 3,
                },
                992: {
                    items: 4,
                }
            }
        });

        //========= testimonial 
        tns({
            container: '.testimonial-slider',
            items: 3,
            slideBy: 'page',
            autoplay: false,
            mouseDrag: true,
            gutter: 0,
            nav: true,
            controls: false,
            controlsText: ['<i class="lni lni-arrow-left"></i>', '<i class="lni lni-arrow-right"></i>'],
            responsive: {
                0: {
                    items: 1,
                },
                540: {
                    items: 1,
                },
                768: {
                    items: 1,
                },
                992: {
                    items: 1,
                },
                1170: {
                    items: 1,
                }
            }
        });

        //====== counter up 
        var cu = new counterUp({
            start: 0,
            duration: 2000,
            intvalues: true,
            interval: 100,
            append: " ",
        });
        cu.start();
    
 </script>
</body>
</html>