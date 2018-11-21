<?php
function minbizeed_site_summary()
{
    ?>
    <div class="admin_stats container">
        <h3>
            Site Summary
        </h3>
        <div class="panel-body">
            <table class="table table-bordered" id="table">
                <thead>
                <tr>
                    <th>Total number of auctions</th>
                    <th>Open Auctions</th>
                    <th>Closed & Finished</th>
                    <th>Total Users</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo minbizeed_get_total_nr_of_auction(); ?></td>
                    <td><?php echo minbizeed_get_total_nr_of_open_auction(); ?></td>
                    <td><?php echo minbizeed_get_total_nr_of_closed_auction(); ?></td>
                    <td>
                        <?php
                        $result = count_users();
                        echo 'There are ', $result['total_users'], ' total users';
                        foreach ($result['avail_roles'] as $role => $count) echo ', ', $count, ' are ', $role, 's';
                        echo '.'; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}