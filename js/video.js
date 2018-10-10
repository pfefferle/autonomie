'use strict';

var SimpleVideo = function (el) {
  	this.$video    = $(el);
  	this.$wrapper  = $(el).parent().addClass('paused');
  	this.$controls = this.$wrapper.find('.video-controls');

  	// remove native controls
  	this.$video.removeAttr('controls');

  	// check if video should autoplay
    if(!!this.$video.attr('autoplay')) {
    		this.$wrapper.removeClass('paused').addClass('playing');
    }

  	// check if video is muted
    if(this.$video.attr('muted') === 'true' || this.$video[0].volume === 0) {
        this.$video[0].muted = true;
        this.$wrapper.addClass('muted');
    }

  	// attach event handlers
  	this.attachEvents();
};

SimpleVideo.prototype.attachEvents = function () {

  	var self = this,
        _t; // keep track of timeout for controls

  	// attach handlers to data attributes
    this.$wrapper.on('click', '[data-media]', function () {

        var data = $(this).data('media');

        if(data === 'play-pause') {
            self.playPause();
        }
        if(data === 'mute-unmute') {
            self.muteUnmute();
        }
    });

  	this.$video.on('click', function () {
    		self.playPause();
    });

    this.$video.on('play', function () {
    		self.$wrapper.removeClass('paused').addClass('playing');
    });

    this.$video.on('pause', function () {
    		self.$wrapper.removeClass('playing').addClass('paused');
    });

    this.$video.on('volumechange', function () {
        if($(this)[0].muted) {
        		self.$wrapper.addClass('muted');
        }
        else {
        		self.$wrapper.removeClass('muted');
        }
    });

    this.$wrapper.on('mousemove', function () {

        // show controls
        self.$controls.addClass('video-controls--show');

        // clear original timeout
        clearTimeout(_t);

        // start a new one to hide controls after specified time
        _t = setTimeout(function () {
            self.$controls.removeClass('video-controls--show');
        }, 2250);

    }).on('mouseleave', function () {
        self.$controls.removeClass('video-controls--show');
    });
};

SimpleVideo.prototype.playPause = function () {
    if (this.$video[0].paused) {
    		this.$video[0].play();
    } else {
		    this.$video[0].pause();
    }
};

SimpleVideo.prototype.muteUnmute = function () {
    if(this.$video[0].muted === false) {
    		this.$video[0].muted = true;
    } else {
		    this.$video[0].muted = false;
    }
};

jQuery(document).ready(function() {
  jQuery("video").each(function () {
      new SimpleVideo(this);
  });
});
