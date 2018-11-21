<?php
function minbizeed_user_balances() {

	if ( isset( $_GET['src_usr'] ) ) {
		$src_usr = $_GET['src_usr'];
	} else {
		$src_usr = "";
	}

	if ( isset( $_GET['order_by'] ) ) {
		$order_by = $_GET['order_by'];
	} else {
		$order_by = 'user_registered';
	}

	if ( isset( $_GET['order'] ) ) {
		$order = $_GET['order'];
	} else {
		$order = 'desc';
	}

	?>
    <div class="admin_stats container">
        <h3>Users List</h3>
        <div class="elt srch">
            <form method="get" action="<?php echo get_bloginfo( 'siteurl' ); ?>/wp-admin/admin.php">
                <input type="hidden" name="page" value="user_balances"/>
                <span>Search Users:</span>
                <input type="text" size="35" value="<?php echo $_GET['src_usr']; ?>"
                       name="src_usr" placeholder="Enter username to search"/>
                <input type="submit" value="Submit" name="" class="btn btn-primary"/>
                <a href="/wp-admin/admin.php?page=user_balances" class="btn btn-warning">Clear</a>
            </form>
        </div>
		<?php
		if ( ! $src_usr ) {
			?>
            <div class="elt srch orderPar">
                <span>Order:</span>
                <select class="order_select"
                        onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">

                    <option <?php the_mbz_bal_sel( 'user_registered', 'desc', $order_by, $order ); ?>
                            value="/wp-admin/admin.php?page=user_balances">
                        -Default- Registered Date (DESC)
                    </option>
                    <option <?php the_mbz_bal_sel( 'user_registered', 'asc', $order_by, $order ); ?>
                            value="/wp-admin/admin.php?page=user_balances&order=asc">
                        Registered Date (ASC)
                    </option>
                    <option <?php the_mbz_bal_sel( 'bids', 'desc', $order_by, $order ); ?>
                            value="/wp-admin/admin.php?page=user_balances&order_by=bids&order=desc">
                        Total Bids (DESC)
                    </option>
                    <option <?php the_mbz_bal_sel( 'bids', 'asc', $order_by, $order ); ?>
                            value="/wp-admin/admin.php?page=user_balances&order_by=bids&order=asc">
                        Total Bids (ASC)
                    </option>
                </select>
            </div>
			<?php
		}
		?>
        <div class="panel-body holder">

			<?php
			$rows_per_page = 10;
			global $wpdb;
			$tbl_users    = $wpdb->users;
			$tbl_usermeta = $wpdb->usermeta;

			if ( isset( $_GET['pj'] ) ) {
				$pageno = $_GET['pj'];
			} else {
				$pageno = 1;
			}


			if ( $src_usr ) {
				$s1 = "select ID from " . $wpdb->users . " where user_login like '%$src_usr%' order by user_registered desc ";
				$s  = "select * from " . $wpdb->users . " where user_login like '%$src_usr%' order by user_registered desc ";
			} else {

				if ( $order_by == "bids" ) {
					$s1 = "SELECT " . $tbl_users . ".ID FROM " . $tbl_users . " LEFT JOIN " . $tbl_usermeta . " ON " . $tbl_users . ".ID = " . $tbl_usermeta . ".user_id AND " . $tbl_usermeta . ".meta_key='user_credits' ORDER BY ABS(" . $tbl_usermeta . ".meta_value) $order ";
					$s  = "SELECT " . $tbl_users . ".ID," . $tbl_users . ".user_login," . $tbl_users . ".user_email," . $tbl_users . ".user_registered," . $tbl_usermeta . ".meta_value FROM " . $tbl_users . " LEFT JOIN " . $tbl_usermeta . " ON " . $tbl_users . ".ID = " . $tbl_usermeta . ".user_id AND " . $tbl_usermeta . ".meta_key='user_credits' ORDER BY ABS(" . $tbl_usermeta . ".meta_value) $order ";
				} else {
					$s1 = "select ID from " . $tbl_users . " order by user_registered $order ";
					$s  = "select * from " . $tbl_users . " order by user_registered $order ";
				}
			}
			$limit = 'LIMIT ' . ( $pageno - 1 ) * $rows_per_page . ',' . $rows_per_page;

			$r        = $wpdb->get_results( $s1 );
			$nr       = count( $r );
			$lastpage = ceil( $nr / $rows_per_page );

			$r = $wpdb->get_results( $s . $limit );

			if ( $nr > 0 ) {
				?>
                <p class="ajax_return" style=""></p>
                <img class="loader" src="<?php echo get_bloginfo( 'template_url' ); ?>/images/ajax_loader.gif"/>

                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Date Registered</th>
                        <th>Cash Balance</th>
                        <th>Options</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>


					<?php
					foreach ( $r as $row ) {
						$user = get_userdata( $row->ID );

						$nonce    = wp_create_nonce( "credits_update" );
						$ajax_url = admin_url( 'admin-ajax.php?action=credits_update&nonce=' . $nonce );


						?>
                        <tr>
                            <td>
                                <a href="/wp-admin/admin.php?page=user_stats&u=<?php echo $row->ID; ?>"><?php echo $user->user_login; ?></a>
                            </td>
                            <td>
								<?php echo $row->user_email; ?>
                            </td>
                            <td>
								<?php echo $row->user_registered; ?>
                            </td>
                            <td>
                                <span id="money<?php echo $row->ID; ?>"><?php echo minbizeed_get_credits( $row->ID ); ?></span>
                            </td>
                            <td>
                                <div class="form-group intable_group">
                                    <label class="intable_label">Increase Credits:</label>
                                    <input type="text" size="4"
                                           id="increase_credits<?php echo $row->ID; ?>"
                                           rel="<?php echo $row->ID; ?>"/>
                                </div>
                                <div class="form-group intable_group">
                                    <label class="intable_label">Decrease Credits:</label>
                                    <input type="text" size="4"
                                           id="decrease_credits<?php echo $row->ID; ?>"
                                           rel="<?php echo $row->ID; ?>"/>
                                </div>
                                <input type="button" value="Update" class="update_btn btn btn-danger"
                                       alt="<?php echo $row->ID; ?>"
                                       data-nonce="<?php echo $nonce; ?>"
                                       data-ajax_url="<?php echo $ajax_url; ?>"/>
                            </td>
                            <td>
                                <a class="btn btn-info"
                                   href="/wp-admin/admin.php?page=user_stats&u=<?php echo $row->ID; ?>">View</a>
                            </td>
                        </tr>
						<?php
					}
					?>

                    </tbody>
                </table>
                <ul class="pagination">
					<?php
					for ( $i = 1; $i <= $lastpage; $i ++ ) {
						?>
                        <li>
							<?php
							if ( $src_usr ) {
								?>
                                <a href="<?php echo get_bloginfo( 'siteurl' ); ?>/wp-admin/admin.php?page=user_balances&pj=<?php echo $i; ?>&src_usr=<?php echo $_GET['src_usr']; ?>"><?php echo $i; ?></a>
								<?php
							} else {
								?>
                                <a href="<?php echo get_bloginfo( 'siteurl' ); ?>/wp-admin/admin.php?page=user_balances&pj=<?php echo $i; ?>&order_by=<?php echo $order_by ?>&order=<?php echo $order; ?>"><?php echo $i; ?></a>
								<?php
							}
							?>
                        </li>
						<?php
					}
					?>
                </ul>
				<?php
			} else {
				if ( ! empty( $_GET['src_usr'] ) ) {
					_e( 'No users found.', 'minbizeed' );
				} else {
					_e( 'Sorry no users yet.', 'minbizeed' );
				}
			}
			?>
        </div>
    </div>
    <style type="text/css">
        .clear {
            clear: both;
        }

        .header {
            margin: 20px 0;
        }

        .header .elt {
            display: inline-block;
        }

        .header .elt h2 {
            margin: 0 0 10px 0;
        }

        .header .elt.srch {
            float: right;
        }

        .header .elt.srch a {
            -webkit-box-shadow: 1.7px 1.7px 1px #787878;
            -moz-box-shadow: 1.7px 1.7px 1px #787878;
            box-shadow: 1.7px 1.7px 1px #787878;
            padding: 5px;
            text-decoration: none;
            border: 1px solid #000;
        }

        .orderPar {
            margin: 20px 0;
        }

        .loader {
            display: none;
            margin: 20px auto;
            width: 100%;
            max-width: 20px;
        }

        .ajax_return {
            text-align: center;
            margin: 20px 0;
            display: none;
            font-size: 16px;
        }

        .updated_yes {
            background-color: green;
            padding: 2px;
            color: white;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .updated_no {
            background-color: red;
            padding: 2px;
            color: white;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .widefat input {
            padding: 3px !important;
            font-size: 14px !important;
        }

        .intable_group {
            margin: 0 0 5px;
        }

        .intable_label {
            min-width: 140px;
        }
    </style>
    <script type="text/javascript">

        jQuery(document).ready(function () {

            jQuery('.update_btn*').click(function () {
                var loader = jQuery('.loader');
                var nonce = jQuery(this).attr('data-nonce');
                var ajax_url = jQuery(this).attr('data-ajax_url');
                var id = jQuery(this).attr('alt');
                var increase_credits = jQuery('#increase_credits' + id).val();
                var decrease_credits = jQuery('#decrease_credits' + id).val();
                loader.css('display', 'block').hide().fadeIn();
//                                loader.slideDown();
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: ajax_url,
                    data: {
                        action: "credits_update",
                        nonce: nonce,
                        uid: id,
                        increase_credits: increase_credits,
                        decrease_credits: decrease_credits
                    },
                    success: function (response) {
                        if (response.type == "success") {
                            jQuery("#money" + id).html(response.total);
                            jQuery('#increase_credits' + id).val("");
                            jQuery('#decrease_credits' + id).val("");
                            loader.slideUp();
                            jQuery('.ajax_return').css({"color": "green"});
                            jQuery('.ajax_return').html(response.html_success);
                            jQuery('.ajax_return').slideDown();
                            jQuery('#money' + id).addClass('updated_yes');
                            setTimeout(
                                function () {
                                    jQuery('#money' + id).removeClass('updated_yes');
                                }, 3000);
                            setTimeout(
                                function () {
                                    jQuery('.ajax_return').slideUp();
                                }, 3000);
                        } else {
                            loader.slideUp();
                            jQuery('.ajax_return').css({"color": "red"});
                            jQuery('.ajax_return').html(response.html_error);
                            jQuery('.ajax_return').slideDown();
                            jQuery('#money' + id).addClass('updated_no');
                            setTimeout(
                                function () {
                                    jQuery('#money' + id).removeClass('updated_no');
                                }, 3000);
                            setTimeout(
                                function () {
                                    jQuery('#money' + id).removeClass('updated_no');
                                    jQuery('.ajax_return').slideUp();
                                }, 3000);
                        }
                    }
                });


            });


        });


    </script>
	<?php
}

function the_mbz_bal_sel( $theSelOrderBy, $theSelOrder, $theOrderBy, $theOrder ) {
	if ( $theSelOrderBy == $theOrderBy && $theSelOrder == $theOrder ) {
		echo 'selected="selected"';
	}
}