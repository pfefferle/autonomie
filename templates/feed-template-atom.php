<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<xsl:stylesheet version="1.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:atom="http://www.w3.org/2005/Atom"
				exclude-result-prefixes="atom"
				>
	<xsl:output method="html" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title><xsl:value-of select="/atom:feed/atom:title"/> Feed</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<meta name="viewport" content="width=device-width" />
				<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() . '/assets/css/feed.css'; ?>" />
			</head>
			<body>
				<div id="page">
				<header id="site-header" class="site-header">
						<div class="site-meta">Atom Feed of <xsl:value-of select="/atom:feed/atom:title"/>. Feed readers can use the URL in the address bar.</div>
						<div class="site-subscribe">
							Subscribe:
							<ul class="info">
								<li><input type="button" onclick="subtome()" value="SubToMe" /></li>
								<li>
									<a>
										<xsl:attribute name="href">
											<xsl:value-of select="concat('feed:', /atom:feed/atom:link[@rel='self']/@href)"/>
										</xsl:attribute>
										<abbr title="Universal Subscription Mechanism">USM</abbr>
									</a>
								</li>
							</ul>
						</div>

						<xsl:if test="/atom:feed/atom:icon">
							<a class="site-logo">
								<xsl:attribute name="href">
									<xsl:value-of select="/atom:feed/atom:link[@rel='alternate']/@href"/>
								</xsl:attribute>
								<img>
									<xsl:attribute name="src">
										<xsl:value-of select="/atom:feed/atom:icon"/>
									</xsl:attribute>
									<xsl:attribute name="title">
										<xsl:value-of select="/atom:feed/atom:title"/>
									</xsl:attribute>
								</img>
							</a>
						</xsl:if>
						<div>
							<h1 class="site-title"><xsl:value-of select="/atom:feed/atom:title"/></h1>
							<p class="site-description"><xsl:value-of select="/atom:feed/atom:subtitle"/></p>
							<a class="site-link">
								<xsl:attribute name="href">
									<xsl:value-of select="/atom:feed/atom:link[@rel='alternate']/@href"/>
								</xsl:attribute>
								Visit Website
							</a>
						</div>
					</header>
					<xsl:for-each select="/atom:feed/atom:entry">
						<article class="entry">
							<h2 class="entry-title">
								<a target="_blank">
									<xsl:attribute name="href">
										<xsl:value-of select="atom:link[@rel='alternate']/@href"/>
									</xsl:attribute>
									<xsl:value-of select="atom:title"/>
								</a>
							</h2>
							<div class="entry-meta">
								<span><xsl:value-of select="atom:published" /></span>
							</div>
						</article>
					</xsl:for-each>
					<footer class="site-footer">
						<div id="site-generator">
							<?php do_action( 'autonomie_credits' ); ?>
							<?php printf( __( 'This feed is powered by %1$s and styled with the %2$s theme', 'autonomie' ), '<a href="https://wordpress.org/" rel="generator">WordPress</a>', '<a href="https://notiz.blog/projects/autonomie/">Autonomie</a>' ); ?>
						</div>
					</footer>
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
