<!doctype html>
<html lang="en">
<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="author" content="dts">
     <meta name="keywords" content="landing page, DepEd-DTS">
     <meta name="description" content="Landing Page Template for DepEd-DTS">
     <title>{{ config('app.name') }}</title>
     <!-- Loading Bootstrap -->
     <link href="/landing-page-theme/css/bootstrap.min.css" rel="stylesheet">
     <!-- Loading Template CSS -->
     <link href="/landing-page-theme/css/style.css" rel="stylesheet">
     {{-- <link href="/landing-page-theme/css/bootstrap-icons.css" rel="stylesheet"> --}}
     <link href="/landing-page-theme/css/animate.css" rel="stylesheet">
     <link href="/landing-page-theme/css/style-magnific-popup.css" rel="stylesheet">
     <!-- Fonts -->
     <link rel="dns-prefetch" href="//fonts.gstatic.com">
     <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
     <!-- Font Favicon -->
     <link rel="shortcut icon" href="images/favicon.ico">
     <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
     <link rel="stylesheet" href="{{ asset('/assets/css/timeline.css') }}">
     <style>
          * {
               font-family: 'Inter', sans-serif;
          }

     </style>
</head>
<body>
     <!-- begin header -->
     <header>
          <nav class="navbar navbar-expand-lg navbar-fixed-top">
               <div class="container">
                    <!-- begin logo -->
                    {{-- <i class="bi bi-intersect"></i>  --}}
                    <a class="navbar-brand fw-medium" href="#">{{ config('app.name') }}</a>
                    <!-- end logo -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="navbar-toggler-icon"><i class="bi bi-list"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                         <!-- begin navbar-nav -->
                         <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll justify-content-center">
                              {{-- <li class="nav-item"><a class="nav-link" href="#home">Home</a></li> --}}
                              {{-- <li class="nav-item"><a class="nav-link" href="#about">About</a></li> --}}
                              {{-- <li class="nav-item"><a class="nav-link" href="#team">Our Team</a></li> --}}
                              {{-- <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li> --}}
                              {{-- <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li> --}}
                              {{-- <li class="nav-item"><a class="nav-link" href="#blog">Blog</a></li> --}}
                              {{-- <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li> --}}
                         </ul>
                         <div class="col-md-3 text-end">
                              @auth
                              <a href="{{ url('/home') }}"><button type="button" class="btn btn-link">Home</button></a>
                              @else
                              <a href="{{ route('login') }}"><button type="button" class="btn btn-link"><i class="bi bi-person"></i> Login</button></a>

                              @if (Route::has('register'))
                              <a href="{{ route('register') }}"><button type="button" class="btn btn-primary">Sign-up</button></a>
                              @endif
                              @endauth
                         </div>
                    </div>
               </div>
          </nav>
     </header>
     <!-- end header -->

     <main>
          <!--begin home section -->
          <section class="home-section" id="home">
               <!--begin container -->
               <div class="container">
                    <!--begin row -->
                    <div class="row align-items-center">
                         <!--begin col-md-6-->
                         <div class="col-md-6">
                              <h2>Track your document</h1>
                                   <!--begin newsletter_form_box -->
                                   <div class="newsletter_form_box">
                                        <!--begin success_box -->
                                        {{-- <p class="newsletter_success_box" style="display:none;">We received your message and you'll hear from us soon. Thank You!</p> --}}
                                        <!--end success_box -->
                                        <!--begin newsletter-form -->
                                        <form id="newsletter-form" class="newsletter-form" action="/#result" method="GET">
                                             <input id="email_newsletter" class="is-invalid" type="text" name="tracking_id" placeholder="Enter your Tracking ID">
                                             <input type="submit" value="Track document" id="submit-button-newsletter">
                                        </form>
                                        <!--end newsletter-form -->
                                        {{-- <p class="newsletter-form-terms">
                            <span><i class="bi bi-check2"></i> Free 30-Day Trial</span>
                            <span><i class="bi bi-check2"></i> Money Back Guarantee</span>
                        </p> --}}
                                   </div>
                                   <!--end newsletter_form_box -->
                         </div>
                         <!--end col-md-6-->
                         <!--begin col-md-5-->
                         <div class="col-md-6">
                              <img src="/landing-page-theme/images/ui-design.png" class="hero-image width-100 margin-top-20" alt="pic">
                         </div>
                         <!--end col-md-5-->
                    </div>
                    <!--end row -->
               </div>
               <!--end container -->
          </section>
          <!--end home section -->
          @isset($service)
          <!--begin section-white -->
          <section class="section-white" id="result">
               <!--begin container -->
               <div class="container">
                    <!--begin row -->
                    <div class="row justify-content-center">
                         @if ($service !== "no-result")
                         <!--begin col-md-12 -->
                         <div class="col-md-12 text-center">
                              <h2>{{ $service->name }} Document</h2>
                         </div>
                         <!--end col-md-12 -->
                         <div class="col-md-10">
                              <div class="timeline timeline-left mx-lg-10">
                                   <div class="timeline-breaker">Faculty</div>
                                   <!--Timeline item 1-->
                                   @foreach($service->process as $process)
                                   <div class="timeline-breaker text-center timeline-breaker-middle">
                                        {{ $process->responsible }}</div>
                                   <div class="timeline-item mt-3 row">
                                        <div class=" font-weight-bold text-md-right">

                                             @foreach($logs as $document_logs)
                                             @if($document_logs->service_index === $process->index)
                                             <li class='h6  p-2 {{ $document_logs->stage === 'current' ? 'bg-primary text-white' : 'text-dark' }}'>
                                                  <span class='fw-bold'>
                                                       {{ $document_logs->stage === 'current' ? '(CURRENT)' : '' }}
                                                  </span>
                                                  <i class="fas fa-arrow-right"></i>
                                                  {{ $process->description }} - <span class='text-uppercase font-size-15 fw-bold'>{{ $document_logs->status == 'last' ? 'release' : $document_logs->status }}</span>
                                                  @if(Str::upper($document_logs->status) === 'RECEIVED')
                                                  <span class=''>by {{ Str::upper($document_logs->receiver?->user?->fullname) }}</span>
                                                  @elseif(Str::upper($document_logs->status) === 'FORWARDED')
                                                  <span class=''>by {{ Str::upper($document_logs?->forwarded_by_user?->fullname) }} to {{ $document_logs->forwarded_to_user->fullname }}</span>
                                                  {{--@elseif(Str::upper($logs->status) === 'RETURNED')
                                   <span class='fw-bold'>by {{ Str::upper($logs->returnee->fullname) }} <span class='fw-normal'>to</span> {{ Str::upper($logs->return_to->fullname) }}</span>
                                                  because of <span class='fw-bold'>{{ Str::upper($logs->reasons) }}</span> --}}
                                                  @endif
                                                  on
                                                  {{ $document_logs->updated_at->format('F d, Y h:i A') }}
                                             </li>
                                             @endif
                                             @endforeach
                                        </div>
                                   </div>
                                   @endforeach
                              </div>
                         </div>
                         @else
                         <div class="col-md-12 text-center">
                              <img src="/landing-page-theme/images/undraw_No_data_re_kwbl.png" style="width: 200px;">
                              <p class="text-danger">The tracking ID is invalid or might have expired.</p>
                         </div>
                         @endif
                    </div>
                    <!--end row -->
               </div>
               <!--end container -->
          </section>
          <!--end section-white -->
          @endisset

          <!--begin footer -->

            <div class="">
                <div class="footer">
                        <!--begin container -->
                        <div class="container">
                            <!--begin row -->
                            <div class="row">
                                <!--begin col-md-7 -->
                                <div class="col-md-7">
                                    <p>© 2022 <span class="template-name">DepEd Tandag-DTS</span></p>
                                </div>
                                <!--end col-md-5 -->
                            </div>
                            <!--end row -->
                        </div>
                        <!--end container -->
                </div>

            </div>
            <!--end footer -->
        </main>



        <!-- Load JS here for greater good =============================-->

        <script src="/landing-page-theme/js/jquery-3.6.0.min.js"></script>
        <script src="/landing-page-theme/js/bootstrap.min.js"></script>
        <script src="/landing-page-theme/js/jquery.scrollTo-min.js"></script>
        <script src="/landing-page-theme/js/jquery.magnific-popup.min.js"></script>
        <script src="/landing-page-theme/js/jquery.nav.js"></script>
        <script src="/landing-page-theme/js/wow.js"></script>
        <script src="/landing-page-theme/js/plugins.js"></script>
        <script src="/landing-page-theme/js/custom.js"></script>
</body>
</html>
