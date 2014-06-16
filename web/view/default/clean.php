<?php echo $headers; ?>

<div class="wrap">
	
	<div class="site-header-container container">

	<div class="row">
	
		<div class="col-md-12">
			
			<header class="site-header">
			
				<section class="site-logo">
				
					<a href="/">
						<img src="<?php echo THEMEROOT; ?>assets/images/logo@2x.png" width="200" />
					</a>
					
				</section>
				
				<nav class="site-nav">
					
					<ul class="main-menu hidden-xs" id="main-menu">
						<li class="active">
							<a href="index.html">
								<span>Home</span>
							</a>
						</li>
						<li>
							<a href="about.html">
								<span>Pages</span>
							</a>
							
							<ul>
								<li>
									<a href="about.html">
										<span>About Us</span>
									</a>
								</li>
								<li class="active">
									<a href="#">
										<span>Active Menu Item</span>
									</a>
								</li>
								<li>
									<a href="gallery.html">
										<span>Gallery</span>
									</a>
									
									<ul>
										<li>
											<a href="index.html?2">
												<span>Sub 1</span>
											</a>
										</li>
										<li>
											<a href="about.html">
												<span>Sub 2</span>
											</a>
											
											<ul>
												<li>
													<a href="contact.html">
														<span>Sub of sub 1</span>
													</a>
												</li>
												<li>
													<a href="portfolio.html">
														<span>Sub of sub 2</span>
													</a>
												</li>
											</ul>
										</li>
										<li>
											<a href="#">
												<span>Sub 3</span>
											</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="blog-post.html">
										<span>Blog Post</span>
									</a>
								</li>
								<li>
									<a href="portfolio-single.html">
										<span>Portfolio Item</span>
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="portfolio.html">
								<span>Portfolio</span>
							</a>
						</li>
						<li>
							<a href="blog.html">
								<span>Blog</span>
							</a>
						</li>
						<li>
							<a href="contact.html">
								<span>Contact</span>
							</a>
						</li>
						<li class="search">
							<a href="#">
								<i class="entypo-search"></i>
							</a>
							
							<form method="get" class="search-form" action="" enctype="application/x-www-form-urlencoded">
								<input type="text" class="form-control" name="q" placeholder="Type to search..." />
							</form>
						</li>
					</ul>
					
				
					<div class="visible-xs">
						
						<a href="#" class="menu-trigger">
							<i class="entypo-menu"></i>
						</a>
						
					</div>
				</nav>
				
			</header>
			
		</div>
		
	</div>
	
</div>


<?php echo $content; ?>
<?php echo $footer; ?>