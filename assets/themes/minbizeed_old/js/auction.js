//========================================== ACTUAL BIDDING SCRIPT =============================================
var base_url = window.location.origin;
var AUCTION = function () { // class constructor
    this.hasWon = false;
};

AUCTION.prototype = {
    timers: {},

    /**
     * Initialization Methods
     */
    init: function () {
        this.initSockets();
        this.initDomEvents();
    },
    initSockets: function () {
        var self = this;
        $(document).ready(function () {
            self.initTimers(); // start the timers with the initial data

            /*Live server connection start*/
            self.socket = io.connect('http://test.dev.minbizeed.com:2000', {
                'reconnect': true,
                'secure': true,
                'reconnection delay': 100,
                'query': 'auctionId=' + auction_id
            }); // socket.io connection
            /*Live server connection end*/


            /*Test server connection start*/
            // self.socket = io.connect('http://test.dev.minbizeed.com:7000', {
            //     'reconnect': true,
            //     'reconnection delay': 100
            // }); // socket.io connection
            /*Test server connection end*/

            self.reconnectionCount = 0;
            //io.set('transports',['xhr-polling']);

            self.bindSocketEvents();
            self.socket.on('disconnect', function () {
                //window.location='/disconnected';
            });

            setInterval(function () {
                self.socket.emit('latency', Date.now(), function (startTime) {
                    var latency = Date.now() - startTime;
                    if (latency > 750) {
                        $('.connection_status_notice.error').slideDown('fast');
                        $('.connection_status_notice').addClass('error_flag');
                    } else {
                        if ($('.connection_status_notice').hasClass('error_flag')) {
                            $('.connection_status_notice.error').slideUp('fast');
                            $('.connection_status_notice').removeClass('error_flag');
                            $('.connection_status_notice.success').slideDown('fast');
                            setInterval(function () {
                                $('.connection_status_notice.success').slideUp('fast');
                            }, 2500);
                        } else {
                            $('.connection_status_notice.error').slideUp('fast');
                        }
                    }
                });
            }, 2500);

            self.socket.on("connect", function () {
                $('.bid_loader').fadeOut();
                $('.single-auction .auction-current-bidnow .mm_bid_mm').removeClass('no_link_btn');
            });

            self.socket.on("reconnecting", function () {
                $('.bid_loader').fadeIn();
                $('.single-auction .auction-current-bidnow .mm_bid_mm').addClass('no_link_btn');
                self.reconnectionCount += 1;

                // if (self.reconnectionCount == 20) {
                //     window.location = '/disconnected';
                // }
            });
        });

        window.onbeforeunload = function () {
            self.socket.removeEventListener('disconnect');
        }
    },
    initTimers: function () { // starts the bids timers to 0 as handling this via sockets is overkill
        var self = this;
        jQuery('.auction-current-time').each(function () {
            var auctionId = $(this).attr('data-auction-id');
            var $this = jQuery(this);

            AUCTION.prototype.timers[auctionId] = setInterval(function () {
                var $el = $this;
                var newTime = Number($el.attr('data-time')) - 1;
                var time_difference = newTime;
                $el.attr('data-time', newTime);
                if (newTime > 0) {
                    if (newTime <= 10) {
                        $el.find('time').css({'color': 'red'});
                        $el.find('time').css('visibility', 'hidden');
                        setTimeout(
                            function () {
                                $el.find('time').css('visibility', 'visible');
                            }, 500);
                    }
                    // var h = Math.floor(newTime / 3600);
                    // newTime -= h * 3600;
                    // var m = Math.floor(newTime / 60);
                    // newTime -= m * 60;
                    // var moddedTime = h + ":" + (m < 10 ? '0' + m : m) + ":" + (newTime < 10 ? '0' + newTime : newTime);
                    // $el.find('time').text(moddedTime);

                    $el.find('time').text(self.timeRemaining(time_difference));

                } else {
                    $el.attr('data-time', '0');
                    $el.find('time').text('00:00:00');
                }

            }, 1000);
        });

        jQuery('.not_s .bid').each(function () {
            var auctionId = $(this).attr('data-auction-id');
            var $this = jQuery(this);

            AUCTION.prototype.timers[auctionId] = setInterval(function () {
                var $el = $this.find('.remaining');
                var newTime = Number($el.attr('data-time')) - 1;
                var time_difference = newTime;
                $el.attr('data-time', newTime);
                if (newTime > 0) {
                    if (newTime <= 10) {
                        $el.find('span').css({'color': 'red'});
                        $el.find('span').css('visibility', 'hidden');
                        setTimeout(
                            function () {
                                $el.find('span').css('visibility', 'visible');
                            }, 500);
                    }
                    // var h = Math.floor(newTime / 3600);
                    // newTime -= h * 3600;
                    // var m = Math.floor(newTime / 60);
                    // newTime -= m * 60;
                    // var moddedTime = h + ":" + (m < 10 ? '0' + m : m) + ":" + (newTime < 10 ? '0' + newTime : newTime);
                    // $el.find('span').text(moddedTime);

                    $el.find('span').text(self.timeRemaining(time_difference));
                } else {
                    $el.attr('data-time', '0');
                    $el.find('span').text('00:00:00');
                    $el.attr('data-time', '00:00:00');
                }
            }, 1000);
        });
    },


    timeRemaining: function (sub) {
        seconds = Math.floor(sub);
        minutes = Math.floor(seconds/60);
        hours = Math.floor(minutes/60);
        weeks = Math.floor(hours/(24*7));
        days = Math.floor((hours-weeks*24*7)/24);
        total_days=Math.floor(hours/24);

        hours = hours-(total_days*24);
        minutes = minutes-(total_days*24*60)-(hours*60);
        seconds = seconds-(total_days*24*60*60)-(hours*60*60)-(minutes*60);

        var time_text='';
        if(weeks>0)
            time_text+=weeks+'w:';

        if(days>0)
            time_text+=days+'d:';
        else
        if(weeks>0)
            time_text+='0d:';

        if(hours>0)
            time_text+=(hours < 10 ? '0' + hours : hours)+':';
        else
            time_text+='00:';

        if(minutes>0)
            time_text+=(minutes < 10 ? '0' + minutes : minutes)+':';
        else
            time_text+='00:';

        if(seconds>0)
            time_text+=(seconds < 10 ? '0' + seconds : seconds)+'';
        else
            time_text+='00';


        return time_text;
    },


    initDomEvents: function () {
        var self = this;

        /**
         * Landing Page Bid Button
         */
        // jQuery(document).on('click', '.bid-button', function (e) {
        //     e.preventDefault();
        //     var id = jQuery(this).closest('.bid').data('id');
        //     var ub_b = jQuery(this).closest('.bid').find('.ub_bal').val();
        //     var bidCost = Number(/(\d+)/i.exec(jQuery(this).closest('.bid').find('.inner_text span').text())[0]);
        //     if (Number(ub_b) >= bidCost) {
        //         self.bid(id);
        //         var bid_elt = $(this).closest('.bid').find('#current_price');
        //         bid_elt.animate({
        //             "background-color": "rgb(239, 160, 7)",
        //             "color": "#fff"
        //         }, 100);
        //         setTimeout(function myFunction() {
        //             bid_elt.animate({
        //                 "background-color": "rgb(255, 255, 255)",
        //                 "color": "#EFA007"
        //             }, 100);
        //         }, 100);
        //     } else {
        //         $('.popup_msg').html('Insufficient credits!<br>Click <a target="_blank" href="/my-account/payments/">here</a> to charge');
        //         $.magnificPopup.open({
        //             items: {
        //                 src: '.popup_msg'
        //             },
        //             type: 'inline',
        //             closeOnBgClick: false,
        //             closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
        //         });
        //     }
        // });

        /**
         * Landing Page
         */
        jQuery(document).on('click', '.home .bid_now_button, .live_bids_page.not_s .bid_now_button', function (e) {
             e.preventDefault();
             var id = jQuery(this).closest('.bid').data('id');
             var ub_b = Number(jQuery('#balance2').val());
             var bidCost = Number(/(\d+)/i.exec(jQuery(this).closest('.bid').find('.price_increase').text())[0]);

             if (Number(ub_b) >= bidCost) {
                 self.bid(id);
                 var bid_elt = $(this).closest('.bid').find('.price_reached');

                 // Abhishek changes starts
                 jQuery(this).closest('.bid').parent().addClass('all_green');        
                 jQuery(this).closest('.bid').find('.clock_img').attr('src',base_url+'/assets/themes/minbizeed/images/clock_green.png');
                 jQuery(this).closest('.bid').find('.eye_img').attr('src',base_url+'/assets/themes/minbizeed/images/eye_green.png');
                 //jQuery(this).closest('.bid').find('.image_holder .bids_img').attr('src',base_url+'/assets/themes/minbizeed/images/bids_green_.png');     
                 // Abhishek changes ends 


                 bid_elt.animate({
                     "background-color": "rgb(239, 160, 7)",
                     "color": "#fff"
                 }, 100);
                 setTimeout(function myFunction() {
                     bid_elt.animate({
                         "background-color": "rgb(255, 255, 255)",
                         "color": "#EFA007"
                     }, 100);
                 }, 100);
             } else {
                 
                if ($(this).hasClass('bid_not_logged_in')) {
                    $('.popup_msg').html('<p>Please <a href="/?msg=login_req&redirect_url=/?p=1790">Login</a> or <a href="/register">Register</a> to bid.</p>');
                    $.magnificPopup.open({
                        items: {
                            src: '.popup_msg'
                        },
                        type: 'inline',
                        closeOnBgClick: false,
                        closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                    });
                } else {
                    $('.popup_msg').html('Insufficient credits! <br>Click <a target="_blank" href="/buy-bids/">here</a> to charge');
                    $.magnificPopup.open({
                        items: {
                            src: '.popup_msg'
                        },
                        type: 'inline',
                        closeOnBgClick: false,
                        closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                    });
                }
             }
         });

        /**
         * Single Page Bid Button
         */
        jQuery(document).on('click', '.single-auction .mm_bid_mm', function (e) {
            e.preventDefault();
            var id = jQuery(this).attr('rel');
            var bidCost = Number(/(\d+)/i.exec(jQuery('.price_increase').text())[0]);
            if (Number(jQuery('#balance2').val()) >= bidCost) {
                self.bid(id);
                jQuery('.product_information').addClass('all_green');
                jQuery('.mm_bid_mm .bid_now_label').animate({
                    "background-color": "rgb(144, 233, 223)",
                    "padding": "13px 0",
                    "margin": "0",
                    "color": "#fff",
                }, 100);
                setTimeout(function myFunction() {
                    jQuery('.mm_bid_mm .bid_now_label').animate({
                        "background-color": "rgb(255, 255, 255)",
                        "color": "#90E9DF",
                        "padding": "0",
                        "margin": "11px 0;",
                    }, 100);
                }, 100);
            } else {
                if ($(this).hasClass('bid_not_logged_in')) {
                    $('.popup_msg').html('<p>Please <a href="/?msg=login_req&redirect_url=/?p=1790">Login</a> or <a href="/register">Register</a> to bid.</p>');
                    $.magnificPopup.open({
                        items: {
                            src: '.popup_msg'
                        },
                        type: 'inline',
                        closeOnBgClick: false,
                        closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                    });
                } else {
                    $('.popup_msg').html('Insufficient credits! <br>Click <a target="_blank" href="/buy-bids/">here</a> to charge');
                    $.magnificPopup.open({
                        items: {
                            src: '.popup_msg'
                        },
                        type: 'inline',
                        closeOnBgClick: false,
                        closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                    });
                }
            }
        });

        /**
         * Single Page Pause Autobid
         */
        // jQuery(document).on('click', '.pauseAutobid', function (e) {
        //     e.preventDefault();
        //     var id = jQuery('.mm_bid_mm').attr('rel');
        //
        //     if (jQuery(this).hasClass('paused')) {
        //         self.resumeAutobid(id);
        //         jQuery(this).removeClass('paused').text('Pause Autobid.');
        //     } else {
        //         self.pauseAutobid(id);
        //         jQuery(this).addClass('paused').text('Resume Autobid.');
        //     }
        // });

        /**
         * Single Page Set Autobid
         */
        // jQuery(document).on('click', '.setAutobid', function (e) {
        //     e.preventDefault();
        //     var id = jQuery('.mm_bid_mm').attr('rel');
        //     var amount = jQuery('input[name="max_credits"]').val();
        //     Number(jQuery('.balance2').text()) >= amount ? self.setAutobid(id, amount) : alert('You don\'t have enough credits!');
        // });
    },
    /**
     * SOCKET Methods
     */
    bindSocketEvents: function () {
        var self = this;

        this.socket.on('AUCTION_REFUNDED', function ($data) {
            if ($('.popup_msg_2').length) {
                $('.popup_msg_2').html('Auction has been closed. Your ' + $data.bids + ' bids has been refunded to your account.');
                $.magnificPopup.open({
                    items: {
                        src: '.popup_msg_2'
                    },
                    type: 'inline',
                    closeOnBgClick: false,
                    closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                });
            }
        });

        this.socket.on('NEW_BID', function ($data) {
            console.log($data);
            self.handleBidData($data);
            $('.bfh-countries').each(function () {
                var $countries;
                $countries = $(this);
                $countries.bfhcountries($countries.data());
            });
        });

        this.socket.on('AUTOBID_OK', function ($data) { // this is sent only to current user
            self.handleAutobidData($data);
        });

        this.socket.on('BID_OK', function ($data) {
            self.setCredits($data);
        });

        this.socket.on('USER_NOT_LOGGED', function ($data) {
            switch ($data) {
                case "MISSING_USER_ID": {
                    alert("Please relog.");
                }
                    break;

                case "MISSING_SESSION": {
                    location.reload();
                }
                    break;
            }
        });

        this.socket.on('AUCTION_END', function ($data) {

            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").addClass("no_link");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").removeClass("mm_bid_mm");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").removeClass("bid_now");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button .bid_now_label").html("");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button .bid_now_label").append('Bidding Ended');

            if (!self.hasWon) {
                if (jQuery('.mm_bid_mm').attr('rel')) {
                    if ($('.popup_msg').length) {
                        var noWinner = false;

                        if ($data.user == 0) {
                            noWinner = true;
                        }

                        $('.popup_msg').html('Auction ' + $data.name + ' ended! ' + ( noWinner ? '' : ( 'The winner is: ' + self.e($data.user) ) ));
                        $.magnificPopup.open({
                            items: {
                                src: '.popup_msg'
                            },
                            type: 'inline',
                            closeOnBgClick: false,
                            closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                        });
                    }
                } else {
                    if ($('.popup_msg').length) {
                        var noWinner = false;

                        if ($data.user == 0) {
                            noWinner = true;
                        }

                        $('.popup_msg').html('Auction ' + $data.name + ' ended! ' + ( noWinner ? '' : ( 'The winner is: ' + self.e($data.user) ) ));
                        $.magnificPopup.open({
                            items: {
                                src: '.popup_msg'
                            },
                            type: 'inline',
                            closeOnBgClick: false,
                            closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                        });
                    }
                }
            }
            else {
                self.hasWon = false;
            }
        });

        // this.socket.on('AUTOBID_PAUSED', function ($data) {
        //     alert('Autobid for auction with ID ' + $data.id + ' was paused.');
        // });
        //
        // this.socket.on('AUTOBID_RESUMED', function ($data) {
        //     alert('Autobid for auction with ID ' + $data.id + ' was resumed.');
        // });

        this.socket.on('AUCTION_NOT_FULFILLED', function ($data) {

            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").addClass("no_link");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").removeClass("mm_bid_mm");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").removeClass("bid_now");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button .bid_now_label").html("");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button .bid_now_label").append('Bidding Ended');

            var $singleAuctionId = jQuery('.mm_bid_mm').attr('rel');
            if (jQuery('.mm_bid_mm').attr('rel')) { // Check if element exists, than this is a single auction page
                if ($data.id == $singleAuctionId) {
                    if ($('.popup_msg').length) {
                        $('.popup_msg').html('Auction <b>' + $data.name + '</b> closed since the price did not reach its minimum limit');
                        $.magnificPopup.open({
                            items: {
                                src: '.popup_msg'
                            },
                            type: 'inline',
                            closeOnBgClick: false,
                            closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                        });
                    }
                }
            } else {
                if ($('.popup_msg').length) {
                    $('.popup_msg').html('Auction <b>' + $data.name + '</b> closed since the price did not reach its minimum limit');
                    $.magnificPopup.open({
                        items: {
                            src: '.popup_msg'
                        },
                        type: 'inline',
                        closeOnBgClick: false,
                        closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                    });
                }
            }

        });

        this.socket.on('SYNC_TIMER', function ($data) {
            self.handleSyncTimer($data);
        });

        this.socket.on('AUCTION_WON', function ($data) {

            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").addClass("no_link");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").removeClass("mm_bid_mm");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button").removeClass("bid_now");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button .bid_now_label").html("");
            $('.bid[data-id="' + $data.id + '"]').find(".auction-current-bidnow .bid_now_button .bid_now_label").append('Bidding Ended');

            self.hasWon = true;
            var $singleAuctionId = jQuery('.mm_bid_mm').attr('rel');
            if (jQuery('.mm_bid_mm').attr('rel')) { // Check if element exists, than this is a single auction page
                if ($data.id == $singleAuctionId) {
                    if ($('.popup_msg').length) {
                        if (jQuery('.mm_bid_mm').hasClass('bid_auction')) {
                            $('.popup_msg').html('You have won!<br><b>' + $data.name + '</b> will be added to your account.');
                        } else {
                            $('.popup_msg').html('You have won!<br>Go to your <a href="/my-account/trophy-room/">trophy room</a> to claim your <b>' + $data.name + '</b>');
                        }
                        $.magnificPopup.open({
                            items: {
                                src: '.popup_msg'
                            },
                            type: 'inline'
                        });
                    }
                }
            } else {
                if ($('.popup_msg').length) {
                    $('.popup_msg').html('You have won!<br>Go to your <a href="/my-account/trophy-room/">trophy room</a> to claim your <b>' + $data.name + '</b>');
                    $.magnificPopup.open({
                        items: {
                            src: '.popup_msg'
                        },
                        type: 'inline'
                    });
                }
            }

        });

        this.socket.on('AUTOBID_SET', function ($data) {
            alert('Autobid for auction with ID ' + $data.id + ' was set for an amount of:' + $data.amount);
        });
    },
    /**
     * UI Methods
     */
    setTimer: function ($id, $amount) { // sets the timer of a bid

        var self = this;

        if (jQuery('.mm_bid_mm').attr('rel')) {
            $('.remaining').attr('data-time', $amount);
        } else {
            this.getBid($id).find('.remaining').attr('data-time', $amount);
        }

        if (jQuery('[data-auction-id="' + $id + '"]').is('.bid')) {
            var $this = jQuery('[data-auction-id="' + $id + '"]');
            var $el = $this.find('.remaining');
            var newTime = $amount;
            var time_difference=newTime;
            $el.attr('data-time', newTime);
            if (newTime > 0) {
                if (newTime <= 10) {
                    $el.find('span').css({'color': 'red'});
                    $el.find('span').css('visibility', 'hidden');
                    setTimeout(
                        function () {
                            $el.find('span').css('visibility', 'visible');
                        }, 500);
                }
                // var h = Math.floor(newTime / 3600);
                // newTime -= h * 3600;
                // var m = Math.floor(newTime / 60);
                // newTime -= m * 60;
                // var moddedTime = h + ":" + (m < 10 ? '0' + m : m) + ":" + (newTime < 10 ? '0' + newTime : newTime);
                // $el.find('span').text(moddedTime);

                $el.find('span').text(self.timeRemaining(time_difference));

            } else {
                $el.attr('data-time', '0');
                $el.find('span').text('00:00:00');
                $el.attr('data-time', '00:00:00');
            }
        } else {
            var $this = jQuery('[data-auction-id="' + $id + '"]');
            var $el = $this;
            var newTime = $amount;
            var time_difference=newTime;
            $el.attr('data-time', newTime);
            if (newTime > 0) {
                if (newTime <= 10) {
                    $el.find('time').css({'color': 'red'});
                    $el.find('time').css('visibility', 'hidden');
                    setTimeout(
                        function () {
                            $el.find('time').css('visibility', 'visible');
                        }, 500);
                }
                // var h = Math.floor(newTime / 3600);
                // newTime -= h * 3600;
                // var m = Math.floor(newTime / 60);
                // newTime -= m * 60;
                // var moddedTime = h + ":" + (m < 10 ? '0' + m : m) + ":" + (newTime < 10 ? '0' + newTime : newTime);
                // $el.find('time').text(moddedTime);

                $el.find('time').text(self.timeRemaining(time_difference));

            } else {
                $el.attr('data-time', '0');
                $el.find('time').text('00:00:00');
            }
        }
    },
    handleSyncTimer: function ($data) {
        clearInterval(AUCTION.prototype.timers[$data.id]);
        this.setTimer($data.id, $data.time_left);
    },
    handleAutobidData: function ($data) { // handle auto bid data
        /**
         * Update new credits after an auto bid
         * note: This can be update on any single auction page
         */
        if (jQuery('.mm_bid_mm').attr('rel')) { // Check if element exists, than this is a single auction page
            var $singleAuctionId = jQuery('.mm_bid_mm').attr('rel');
            if ($data.id == $singleAuctionId) {
                jQuery('.balance2').text($data.new_credits);
                jQuery('.credits-used').text($data.credits_used);
                jQuery('.max-credits').text($data.max_credits);
            }
        }
    },
    setCredits: function ($data) {
        /**
         * Update new credits after an auto bid
         * note: This can be update on any single auction page
         */
        if (jQuery('.mm_bid_mm').attr('rel')) { // Check if element exists, than this is a single auction page
            jQuery('#balance2').val($data.credits);
            jQuery('.landing_balance').html($data.credits);
        } else {
            jQuery('#balance2').val($data.credits);
            jQuery('.landing_balance').html($data.credits);
        }
    },
    handleBidData: function ($data) { // handle bids data
        /**
         * Single auction page
         */

        if (jQuery('.mm_bid_mm').attr('rel')) { // Check if element exists, than this is a single auction page
            var $singleAuctionId = jQuery('.mm_bid_mm').attr('rel');
            if ($data.id == $singleAuctionId) {

                /**
                 * Add new Bidding List table row
                 */
                if (jQuery('.user_wrapper').length === 0) {
                    jQuery('.user_wrapper').html("");
                }

                if (jQuery('.user_wrapper .each_user').length > 6) {
                    jQuery('.user_wrapper .each_user').last().remove();
                }

                jQuery('.no_bidders').remove();
                jQuery('.user_wrapper').prepend('<div class="each_user"><h3>'+$data.name+'</h3><div class="user_info"><div class="user_country"><span>'+$data.country+'</span><span class="bfh-countries" data-country="'+$data.country+'" data-flags="true"><i class="glyphicon bfh-flag-'+$data.country+'"></i> </span></div><div class="user_bids"><span>$'+($data.bid_amount).toLocaleString('en-IN')+'</span></div></div><div class="clear"></div></div>');

                
                /**
                 * Set current price
                 */
                jQuery('.bid_counter').text("$" + ($data.total).toLocaleString('en-IN'));

                /**
                 * Set current time left
                 */
//                jQuery('.auction-current-time').text($data.time_left);
                jQuery('.auction-current-time').attr('data-time', $data.time_left);

                /**
                 * Set highest bidder
                 */
                jQuery('.highestBidder').text(this.e($data.name));
                jQuery('.product_information').addClass('all_green');
                jQuery('.clock_img').attr('src',base_url+'/assets/themes/minbizeed/images/clock_green.png');
                jQuery('.eye_img').attr('src',base_url+'/assets/themes/minbizeed/images/eye_green.png');
                //jQuery('.large_image_holder .bids_img').attr('src',base_url+'/assets/themes/minbizeed/images/bids_green_2.png');

            }
        }

        /**
         * Landing page
         */
        else {

            jQuery('.live_auctions_page.not_s .featured_bid').each(function(){
                if(jQuery(this).data('id')==$data.id)
                    {
                        jQuery(this).parent().addClass('all_green');
                        jQuery(this).find('.clock_img').attr('src',base_url+'/assets/themes/minbizeed/images/clock_green.png');
                        jQuery(this).find('.eye_img').attr('src',base_url+'/assets/themes/minbizeed/images/eye_green.png');
                    }
            });
            this.setTimer($data.id, $data.time_left);
            this.setLastBidder($data.id, $data.name, $data.country, $data.bid_amount);
            this.setCurrentPrice($data.id, $data.total);
        }

    },
    setLastBidder: function ($id, $amount, $country, $bid_amount) { // sets the last bidder of the bid
        if ($bid_amount)
            this.getBid($id).find('.last_bidder').html("Last bidder: " + this.e($amount) + " (" + ($bid_amount).toLocaleString('en-IN') + " $)");
    },
    getBid: function ($id) { // returns the html element of the bid
        return jQuery(".bid[data-id='" + $id + "']");
    },
    setCurrentPrice: function ($id, $amount) { // sets the last updated price of the bid
        this.getBid($id).find('.price_reached').text("$" + ($amount).toLocaleString('en-IN'));
    },
    e: function ($input) {
        return $input.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    },
    /**
     * Auctions Methods
     */
    bid: function ($id) {
        this.socket.emit('NEW_BID', {id: $id});
    },
    pauseAutobid: function ($id) {
        this.socket.emit('PAUSE_AUTOBID', {id: $id});
    },
    resumeAutobid: function ($id) {
        this.socket.emit('RESUME_AUTOBID', {id: $id});
    },
    setAutobid: function ($id, $amount) {
        this.socket.emit('SET_AUTOBID', {id: $id, amount: $amount});
    }
};

//(new AUCTION).init();
var App = new AUCTION();
App.init();