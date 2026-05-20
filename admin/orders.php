<?php
include "includes/head.php";
?>

<body>
    <?php
    include "includes/header.php"
    ?>


    <?php
    include "includes/sidebar.php";
    ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php
        message();
        ?>
        <div class="container">
            <div class="row align-items-start">
                <div class="col">
                    <br>
                    <h2>Order details</h2>
                    <br>
                </div>
                <div class="col">
                </div>
                <div class="col">
                    <br>
                    <form class="d-flex" method="GET" action="orders.php">
                        <input class="form-control me-2 col" type="search" name="search_order_id" placeholder="Search for order (ID)" aria-label="Search">
                        <button class="btn btn-outline-secondary" type="submit" name="search_order" value="search">Search</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">User ID</th>
                        <th scope="col">product ID</th>
                        <th scope="col">Product quantity</th>
                        <th scope="col">TRX ID</th>
                        <th scope="col">Order status</th>
                </thead>

                <tbody>
                    <?php
                    $data = all_orders();
                    delete_order();
                    if (isset($_GET['search_order'])) {
                        $query = search_order();
                        if (!empty($query)) {
                            $data = $query;
                        } else {
                            get_redirect("orders.php");
                        }
                    }
                    $num = sizeof($data);
                    for ($i = 0; $i < $num; $i++) {

                    ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $data[$i]['order_id'] ?></td>
                            <td><?php echo $data[$i]['user_id'] ?></td>
                            <td><?php echo $data[$i]['product_id'] ?></td>
                            <td><?php echo $data[$i]['qty'] ?></td>
                            <td><?php echo $data[$i]['trx_id'] ?></td>
                            <?php if ($data[$i]['p_status'] == 1) {
                            ?>
                                <td style="color: green;">
                                    completed
                                </td>
                            <?php
                            } else {
                            ?>
                                <td style="color: red;">
                                    pending
                                </td>
                            <?php
                            }
                            ?>
                            <td>
                                <button type="button" class="btn  btn-outline-danger"><a style="text-decoration: none; color:black;" href="orders.php?delete=<?php echo $data[$i]['order_id'] ?>">Delete</a></button>
                            </td>

                            <?php if ($data[$i]['p_status'] == 1) {
                            ?>
                                <td>
                                    <button type="button" class="btn  btn-outline-danger"><a style="text-decoration: none; color:black;" href="orders.php?undo=<?php echo $data[$i]['order_id'] ?>">&nbsp;Undo&nbsp;</a></button>
                                </td>
                            <?php
                            } else {
                            ?>
                                <td>
                                    <button type="button" class="btn  btn-outline-success"><a style="text-decoration: none; color:black;" href="orders.php?done=<?php echo $data[$i]['order_id'] ?>">&nbsp;Done&nbsp;</a></button>

                                </td>
                            <?php
                            }
                            ?>
                            <td>
                                <button type="button" class="btn  btn-outline-info"><a style="text-decoration: none; color:black;" href="customers.php?id=<?php echo $data[$i]['user_id'] ?>"> &nbsp;User details&nbsp; </a></button>
                            </td>
                            <td>
                                <button type="button" class="btn  btn-outline-info"><a style="text-decoration: none; color:black;" href="products.php?id=<?php echo $data[$i]['product_id'] ?>">Product details</a></button>

                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    </div>
    </div>
    <?php
    include "includes/footer.php"
    ?>
</body>