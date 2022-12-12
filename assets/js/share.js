( function() {
  entry_share = document.getElementById('entry-share');

  if ( ! entry_share ) {
    return false;
  }

  entry_share.onclick = function share() {
    if (navigator.share) {
      navigator.share(
        {
          title: document.querySelector('title').textContent,
          url: document.querySelector('link[rel="canonical"]').getAttribute('href')
        }
      );
    } else {
      var citation_options = document.getElementById('share-options');
      if (citation_options.style.display === 'none') {
        citation_options.style.display = 'block';
      } else {
        citation_options.style.display = 'none';
      }
    }
    return false;
  }
})();
