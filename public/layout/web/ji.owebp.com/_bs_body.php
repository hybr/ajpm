<base href="/">
       <div id="toTop"><img src="layout/web/ji.owebp.com/images/back-to-top.png"/></div>
        <div class="header">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-sm-3 logo"><img src="layout/web/ji.owebp.com/images/logo.jpg"> </div>
                    <div class="col-lg-7 col-xs-12 col-sm-8 col-md-9 pull-right top-connect">
                        <div class="search col-lg-5 col-xs-12 col-sm-6">
                            <input type="text" placeholder="You can search here..." ng-model="searchPattern">
                            <input type="submit" value="" ui-sref="search">
                        </div>
                        <!-- <div class="social social-header">
                            <a href=""><i class="fa fa-facebook-square"></i></a>
                            <a href=""><i class="fa fa-twitter-square"></i></a>
                            <a href=""><i class="fa fa-google-plus-square"></i></a>
                            </div> -->
                        <div class="mail">
                            <a href="mailto:info@jaipurinvestor.com"><i class="fa fa-envelope"></i>   info@jaipurinvestor.com</a>
                        </div>
                        <div class="phone">
                            <i class="fa fa-phone-square"></i> +91-141-2222222
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navigation">
            <nav class="navbar navbar-default navbar-static-top">
                <div class="container pos-rel">
                    <div class="navbar-header">
                        <span>Menu</span> 
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="#" ui-sref-active="active" ui-sref="home">Home</a></li>
                            <li><a href="#" ui-sref-active="active" ui-sref="about_us">About Us</a></li>
                            <li><a href="#"ui-sref-active="active" ui-sref="item_catalog">Services</a></li>
                            <li><a href="#"ui-sref-active="active" ui-sref="contact_us" >Contact Us</a></li>
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                    <div class="rightmenu">
                        <ul>
                            <li class="dropdown myaccount">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-user"></i> My Account <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                </ul>
                            </li>
                            <li class="login"><a href="#">Investor <span>Login</span></a> </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="clearfix"></div>
        <div class="container"><div class="row">
        	<div ui-view>Loading...</div>
        </div></div>
        
        <div class="clearfix"></div>
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-link">
                        <div class="col-lg-6 col-sm-6 quick-link">
                            <h4>Quick <span>Link</span></h4>
                            <ul>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> Home</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> About us</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> Project</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> Finance</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> Business Associate</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> Projects</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i>  Contact Us</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> About us</a> </li>
                                <li class="col-lg-4"><a href=""><i class="fa fa-chevron-right"></i> Privacy Policy</a> </li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-3 social">
                            <h4>Social <span>Connect</span></h4>
                            <p>Lorem Ipsum is simply dummy typesetti
                                iis simply dumly dummy tndustry. 
                            </p>
                            <a href=""><i class="fa fa-facebook-square"></i></a>
                            <a href=""><i class="fa fa-twitter-square"></i></a>
                            <a href=""><i class="fa fa-google-plus-square"></i></a>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <h4>Contact <span>Us</span></h4>
                            <div class="clearfix"></div>
                            <strong>JAIPUR INVESTOR</strong> <br/>
                            Section 1.10.32 of "de Finibus Bonorum 
                            et Malorum"<br/>
                            <strong>Email:</strong> <a href="mailto:info@jaipurinvestor.com">info@jaipurinvestor.com</a><br/>
                            <strong>Phone:</strong> (123) 456-7890
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="clearfix"></div>
        <div class="copyright">
            &copy; Copyright Jaipur Investor 2016
        </div>

        <script>
            $(function() {
                $(window).scroll(function() {
                  if($(this).scrollTop() != 0) {
                    $('#toTop').fadeIn(); 
                  } else {
                    $('#toTop').fadeOut();
                  }
                });
                $('#toTop').click(function() {
                  $('body,html').animate({scrollTop:0},800);
                });
              });
        </script>
	
<?php if(file_exists(dirname(__FILE__) . '/../_body_links.php'))
	include dirname(__FILE__) . '/../_body_links.php';
?>

