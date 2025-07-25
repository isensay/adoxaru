@extends('layouts.lite')

{{-- Page Content --}}
@section('page_content')

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="card">

                <div class="card-body p-4">
                    
                    <div class="text-center w-75 m-auto">
                        <div class="auth-logo">
                            <a href="index.html" class="logo logo-dark text-center">
                                <span class="logo-lg">
                                    <img src="/source/base/images/adoxa_logo_dark.svg" alt="" height="22">
                                </span>
                            </a>
        
                            <a href="index.html" class="logo logo-light text-center">
                                <span class="logo-lg">
                                    <img src="/source/base/images/adoxa_logo_light.svg" alt="" height="22">
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="mt-4">
                            <div class="logout-checkmark">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 161.2 161.2" enable-background="new 0 0 161.2 161.2" xml:space="preserve">
                                <path class="logout-path" fill="none" stroke="#d1dee4" stroke-miterlimit="10" d="M425.9,52.1L425.9,52.1c-2.2-2.6-6-2.6-8.3-0.1l-42.7,46.2l-14.3-16.4
                                    c-2.3-2.7-6.2-2.7-8.6-0.1c-1.9,2.1-2,5.6-0.1,7.7l17.6,20.3c0.2,0.3,0.4,0.6,0.6,0.9c1.8,2,4.4,2.5,6.6,1.4c0.7-0.3,1.4-0.8,2-1.5
                                    c0.3-0.3,0.5-0.6,0.7-0.9l46.3-50.1C427.7,57.5,427.7,54.2,425.9,52.1z"></path>
                                <circle class="logout-path" fill="none" stroke="#1abc9c" stroke-width="4" stroke-miterlimit="10" cx="80.6" cy="80.6" r="62.1"></circle>
                                <polyline class="logout-path" fill="none" stroke="#1abc9c" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="113,52.8
                                    74.1,108.4 48.2,86.4 "></polyline>

                                <circle class="logout-spin" fill="none" stroke="#d1dee4" stroke-width="4" stroke-miterlimit="10" stroke-dasharray="12.2175,12.2175" cx="80.6" cy="80.6" r="73.9"></circle>

                            </svg>

                        </div>
                        </div>

                        <h3>{{ __('auth.logout.page_title') }}</h3>

                        <p class="text-muted"> {{ __('auth.logout.page_text') }} </p>
                    </div>


                </div> <!-- end card-body -->
            </div>
            <!-- end card -->

            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="text-muted">
                        {{ __('auth.logout.back_to') }} <a href="{{ route('login') }}" class="text-primary fw-medium ms-1">{{ __('auth.logout.sign_in') }}</a>
                    </p>
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- end col -->
    </div>
    <!-- end row -->

@endsection
