<footer>
    <!--? Footer Start-->
    <div class="footer-area footer-bg">
        <div class="container">
            <div class="footer-top footer-padding">
                <!-- footer Heading -->
                <div class="footer-heading col-md-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12" style="text-align: center !important;">
                            <div class="footer-tittle2">
                                <h4>{{trans('user.social_networks')}}</h4>
                            </div>
                            <!-- Footer Social -->
                            <div class="footer-social" >
                                <a href="{{ !is_null($setting_info)? $setting_info->facebook:'#' }}"><i class="fab fa-facebook-f"></i></a>
                                <a href="{{ !is_null($setting_info)? $setting_info->twitter:'#' }}"><i class="fab fa-twitter"></i></a>
                                <a href="{{ !is_null($setting_info)? $setting_info->instagram:'#' }}"><i class="fab fa-instagram"></i></a>
                                <a href="{{ !is_null($setting_info)? $setting_info->pinterest:'#' }}"><i class="fab fa-pinterest"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Menu -->
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>{{trans('user.we')}}</h4>
                                <ul>
                                    <li><a href="#">{{trans('user.about_us')}}</a></li>
                                    <li><a href="#">{{trans('user.home')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>{{trans('user.services')}}</h4>
                                <ul>
                                    <li><a href="#">{{trans('user.characteristics')}}</a></li>
                                    <li><a href="#">{{trans('user.our_product')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>{{trans('user.contact')}}</h4>
                                <ul>
                                    <li><a href="#">{{trans('user.contact_us')}}</a></li>
                                    <li><a href="#">{{trans('user.location')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>{{trans('user.networks')}}</h4>
                                <ul>
                                    <li><a href="#">Facebook</a></li>
                                    <li><a href="#">{{trans('user.email')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-12">
                        <div class="footer-copy-right text-center">
                            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>