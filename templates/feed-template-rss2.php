<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<xsl:stylesheet version="1.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:atom="http://www.w3.org/2005/Atom"
				>
	<xsl:output method="html" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title><xsl:value-of select="/rss/channel/title"/> Feed</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<meta name="viewport" content="width=device-width" />
				<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() . '/assets/css/feed.css'; ?>" />
			</head>
			<body>
				<div id="page">
					<header id="site-header" class="site-header">
						<div class="site-meta">RSS2 Feed of <xsl:value-of select="/rss/channel/title"/>. Feed readers can use the URL in the address bar.</div>
						<div class="site-subscribe">
							Subscribe:
							<ul class="info">
								<li><input type="button" onclick="subtome()" value="SubToMe" /></li>
								<li>
									<a>
										<xsl:attribute name="href">
											<xsl:value-of select="concat('feed:', /rss/channel/atom:link/@href)"/>
										</xsl:attribute>
										<abbr title="Universal Subscription Mechanism">USM</abbr>
									</a>
								</li>
							</ul>
						</div>

						<xsl:if test="/rss/channel/image">
							<a class="site-logo">
								<xsl:attribute name="href">
									<xsl:value-of select="/rss/channel/link"/>
								</xsl:attribute>
								<img>
									<xsl:attribute name="src">
										<xsl:value-of select="/rss/channel/image/url"/>
									</xsl:attribute>
									<xsl:attribute name="title">
										<xsl:value-of select="/rss/channel/title"/>
									</xsl:attribute>
								</img>
							</a>
						</xsl:if>
						<div>
							<h1 class="site-title"><xsl:value-of select="/rss/channel/title"/></h1>
							<p class="site-description"><xsl:value-of select="/rss/channel/description"/></p>
							<a class="site-link">
								<xsl:attribute name="href">
									<xsl:value-of select="/rss/channel/link"/>
								</xsl:attribute>
								Visit Website
							</a>
						</div>
					</header>
					<xsl:for-each select="/rss/channel/item">
						<article class="entry">
							<h2 class="entry-title">
								<a target="_blank">
									<xsl:attribute name="href">
										<xsl:value-of select="link"/>
									</xsl:attribute>
									<xsl:value-of select="title"/>
								</a>
							</h2>
							<div class="entry-meta">
								<span><xsl:value-of select="pubDate" /></span>
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
				<script>
					function subtome() {
						(function(){var z=document.createElement('script');z.src='https://www.subtome.com/load.js';document.body.appendChild(z);})()
					}
				</script>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
