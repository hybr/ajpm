<base href="/">
       <div id="toTop"><img src="layout/web/ji.owebp.com/images/back-to-top.png"/></div>
        <div class="header">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-sm-3 logo">
                    	<img src="layout/web/ji.owebp.com/images/logo.png"> 
                    </div>
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
                            <a href="mailto:info@ssdrgroup.com">
                            	<i class="fa fa-envelope"></i> info@ssdrgroup.com
                            </a>
                        </div>
                        <div class="phone">
                            <i class="fa fa-phone-square"></i> +91-78777-77888
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
                            <li><a ui-sref-active="active" ui-sref="home">Home</a></li>
                            <li><a ui-sref-active="active" ui-sref="about_us">About Us</a></li>
                            <li><a ui-sref-active="active" ui-sref="item_catalog">Services</a></li>
                            <li><a ui-sref-active="active" ui-sref="contact_us" >Contact Us</a></li>
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
                                <li class="col-lg-4"><a ui-sref-active="active" ui-sref="home""><i class="fa fa-chevron-right"></i> Home</a> </li>
                                <li class="col-lg-4"><a ui-sref-active="active" ui-sref="about_us"><i class="fa fa-chevron-right"></i> About Us</a> </li>
                                <li class="col-lg-4"><a ui-sref-active="active" ui-sref="item_catalog"><i class="fa fa-chevron-right"></i> Services</a> </li>
                                <li class="col-lg-4"><a ui-sref-active="active" ui-sref="contact_us"><i class="fa fa-chevron-right"></i>  Contact Us</a> </li>
                                <li class="col-lg-4"><a ui-sref-active="active" ui-sref="search"><i class="fa fa-chevron-right"></i> Search</li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-sm-3 social">
                            <h4>Social <span>Connect</span></h4>
                            <p>Reach us on our social network pages. 
                            </p>
                            <a href=""><i class="fa fa-facebook-square"></i></a>
                            <a href=""><i class="fa fa-twitter-square"></i></a>
                            <a href=""><i class="fa fa-google-plus-square"></i></a>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <h4>Contact <span>Us</span></h4>
                            <div class="clearfix"></div>
                            <strong>JAIPUR INVESTOR</strong> <br/>
                            56, Santosh Vihar, Haldighati Marg, Jagatpura, 
                            Jaipur, 302017, Rajasthan, India<br/>
                            <strong>Email:</strong>
                            <a href="mailto:info@ssdrgroup.com">info@ssdrgroup.com</a><br/>
                            <strong>Phone:</strong> +91-78777-77888
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

