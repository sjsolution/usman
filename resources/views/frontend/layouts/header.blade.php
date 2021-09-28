    <!-- header ar -->
    @if(session()->has('ar'))
        <header class="header bg-dark">
            <div class="container">
                <div class="header__row">
                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        <div class="container-fluid">
                            <!-- <a class="navbar-brand" href="#"></a> -->
                            <a href="{{ route('homePage') }}" class="header__link">
                                <img src="{{ asset('assets/img/logos/main-logo.png') }}" alt="logo" />
                            </a>
                            <button
                                class="navbar-toggler"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarText"
                                aria-controls="navbarText"
                                aria-expanded="false"
                                aria-label="Toggle navigation"
                            >
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarText">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                                    <li class="nav-item">
                                        <a class="nav-link text-white" aria-current="page" href="{{ route('homePage') }}"
                                            >الرئيسية</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('siteDesign') }}">تصميم المواقع</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('storeDesign') }}">تصميم متجر الكتروني</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('appDesign') }}">تصميم تطبيقات الجوال</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('eMarketing') }}">التسويق الالكتروني</a>
                                    </li>
                                    <!--<li class="nav-item">
                                        <a class="nav-link text-white" href="#">أعمالنا</a>
                                    </li>-->
                                    <li class="nav-item ms-lg-auto">
                                        <a class="nav-link text-white" href="{{ route('changeLanguage', 'en') }}">ENG</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
    @endif

    <!-- header en -->
    @if(session()->has('en'))
        <header class="header bg-dark">
            <div class="container">
                <div class="header__row">
                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        <div class="container-fluid">
                            <!-- <a class="navbar-brand" href="#"></a> -->
                            <a href="i{{ route('homePage') }}" class="header__link">
                                <img src="{{ asset('assets/img/logos/main-logo.png') }}" alt="logo" />
                            </a>
                            <button
                                class="navbar-toggler"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarText"
                                aria-controls="navbarText"
                                aria-expanded="false"
                                aria-label="Toggle navigation"
                            >
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarText">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                                    <li class="nav-item">
                                        <a class="nav-link text-white" aria-current="page" href="{{ route('homePage') }}">Main</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('siteDesign') }}">Sites Design</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('storeDesign') }}">Online Store Design</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('appDesign') }}">Mobile App Design</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('eMarketing') }}">E-Marketing</a>
                                    </li>
                                    <!--<li class="nav-item">
                                        <a class="nav-link text-white" href="#">Our Business</a>
                                    </li>-->
                                    <li class="nav-item ms-lg-auto">
                                        <a class="nav-link text-white" href="{{ route('changeLanguage', 'ar') }}">عربى</a>
                                    </li>
                                                                        
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
    @endif