<?php
/**
 * EduForge Header Template
 *
 * @package EduForge360
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="EduForge360 is an industrial student development, learning management, assessment, skill tracking and placement preparation platform.">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="masthead">
	<div class="container header-inner">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
				<div class="logo-mark">
					<span class="logo-icon">⚡</span>
					<span class="logo-text">EduForge<span class="highlight">360</span></span>
				</div>
			</a>
			<span class="site-tagline d-none d-lg-inline">Build Skills. Track Growth. Shape Careers.</span>
		</div>

		<nav class="main-navigation" id="site-navigation" aria-label="Primary Navigation">
			<button class="menu-toggle" id="primary-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="hamburger-icon"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle Navigation', 'eduforge360' ); ?></span>
			</button>

			<ul class="nav-menu" id="primary-menu">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'eduforge360' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>"><?php esc_html_e( 'Courses', 'eduforge360' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>"><?php esc_html_e( 'Skill Assessments', 'eduforge360' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/career/' ) ); ?>"><?php esc_html_e( 'Career Hub', 'eduforge360' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>"><?php esc_html_e( 'Placement Drives', 'eduforge360' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>"><?php esc_html_e( 'Events', 'eduforge360' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'eduforge360' ); ?></a></li>
			</ul>
		</nav>

		<div class="header-actions">
			<?php if ( is_user_logged_in() ) : 
				$current_user = wp_get_current_user();
			?>
				<div class="user-quick-profile">
					<a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>" class="btn btn-primary btn-sm">
						<span class="user-avatar-icon">👤</span>
						<span><?php echo esc_html( $current_user->display_name ); ?></span>
					</a>
					<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="btn btn-outline btn-sm" title="<?php esc_attr_e( 'Log Out', 'eduforge360' ); ?>">
						<?php esc_html_e( 'Logout', 'eduforge360' ); ?>
					</a>
				</div>
			<?php else : ?>
				<a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn-ghost btn-sm"><?php esc_html_e( 'Sign In', 'eduforge360' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" class="btn btn-primary btn-sm"><?php esc_html_e( 'Start Learning', 'eduforge360' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</header>
<main id="primary" class="site-main">
