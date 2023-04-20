{{--{{ dd($modelTopSocialBar) }}--}}
<div id="top-bar" class="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <ul class="top-info text-center text-md-left">
                    <li>{!! $modelTopSocialBar->field('eba836b08498') !!} <p
                            class="info-text">{!! $modelTopSocialBar->field('1b382408c032') !!}</p>
                    </li>
                </ul>
            </div>
            <!--/ Top info end -->

            <div class="col-lg-4 col-md-4 top-social text-center text-md-right">
                <ul class="list-unstyled">
                    <li>
                        <a title="Facebook" href="{{ $modelTopSocialBar->field('a23020916cf2') }}">
                            <span class="social-icon">{!! $modelTopSocialBar->field('3505b59e1276') !!}</span>
                        </a>
                        <a title="Twitter" href="{{ $modelTopSocialBar->field('9c338396df60') }}">
                            <span class="social-icon">{!! $modelTopSocialBar->field('c3ffe38ab30f') !!}</span>
                        </a>
                        <a title="Instagram" href="{{ $modelTopSocialBar->field('c23d6fd0b2b2') }}">
                            <span class="social-icon">{!! $modelTopSocialBar->field('1cbbec89ce41') !!}</span>
                        </a>
                        <a title="Linkdin" href="{{ $modelTopSocialBar->field('11107c775e23') }}">
                            <span class="social-icon">{!! $modelTopSocialBar->field('a1ce52843216') !!}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!--/ Top social end -->
        </div>
        <!--/ Content row end -->
    </div>
    <!--/ Container end -->
</div>
<!--/ Topbar end -->
<!-- Header start -->

<header id="header" class="header-one">
    <div class="bg-white">
        <div class="container">
            <div class="logo-area">
                <div class="row align-items-center">
                    <div class="logo col-lg-3 text-center text-lg-left mb-3 mb-md-5 mb-lg-0">
                        <a class="d-block" href="{{ route('index') }}">
                            <img loading="lazy" src="{{ $modelSiteInformation->getFirstMediaUrl('gallery') }}" alt="">
                        </a>
                    </div><!-- logo end -->

                    <div class="col-lg-9 header-right">
                        <ul class="top-info-box">
                            <li>
                                <div class="info-box">
                                    <div class="info-box-content">
                                        <p class="info-box-title">Call Us</p>
                                        <p class="info-box-subtitle">{{ $modelSiteInformation->field('b6be5c73d159') }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="info-box">
                                    <div class="info-box-content">
                                        <p class="info-box-title">Email Us</p>
                                        <p class="info-box-subtitle">{{ $modelSiteInformation->field('d6a4e576db00') }}</p>
                                    </div>
                                </div>
                            </li>
                            <li class="last">
                                <div class="info-box last">
                                    <div class="info-box-content">
                                        <p class="info-box-title">Global Certificate</p>
                                        <p class="info-box-subtitle">{{ $modelSiteInformation->field('44c3e2a73190') }}</p>
                                    </div>
                                </div>
                            </li>
                            <li class="header-get-a-quote">
                                <a class="btn btn-primary"
                                   href="{{ $modelSiteInformation->field('56203d72333b') }}">{{ $modelSiteInformation->field('6694d9049969') }}</a>
                            </li>
                        </ul><!-- Ul end -->
                    </div><!-- header right end -->
                </div><!-- logo area end -->

            </div><!-- Row end -->
        </div><!-- Container end -->
    </div>

    <div class="site-navigation">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-dark p-0">
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target=".navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div id="navbar-collapse" class="collapse navbar-collapse">
                            <ul class="nav navbar-nav mr-auto">
                                <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">Home</a></li>
                                @include($templatePath.'includes.menu')
                            </ul>
                        </div>
                    </nav>
                </div>
                <!--/ Col end -->
            </div>
            <!--/ Row end -->

            <div class="nav-search">
                <span id="search"><i class="fa fa-search"></i></span>
            </div><!-- Search end -->

            <div class="search-block" style="display: none;">
                <label for="search-field" class="w-100 mb-0">
                    <input type="text" class="form-control" id="search-field"
                           placeholder="Type what you want and enter">
                </label>
                <span class="search-close">&times;</span>
            </div><!-- Site search end -->
        </div>
        <!--/ Container end -->

    </div>
    <!--/ Navigation end -->
</header>
<!--/ Header end -->
