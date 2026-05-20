<?php
$connection = mysqli_connect("localhost", "root", "", "dawai");
    // query functions (start)
function query($query)
{
    global $connection;
    $run = mysqli_query($connection, $query);
    if ($run) {
        while ($row = $run->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return 0;
    }
}
function single_query($query)
{
    global $connection;
    $run = mysqli_query($connection, $query);
    if ($run) {
        return 1;
    } else {
        return 0;
    }
}
// query functions (end)
// redirect functions (start)
function post_redirect($url)
{
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    die();
}
function get_redirect($url)
{
    echo " <script> 
    window.location.href = '$url'; 
    </script>";
    
}
// redirect functions (end)
// messages function (start)
function message()
{
    if ($_SESSION['message'] == "loginErr") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no account with this email !!!
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "emailErr") {
        echo "   <div class='alert alert-danger' role='alert'>
        The email address is already taken.  Please choose another
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "loginErr1") {
        echo "   <div class='alert alert-danger' role='alert'>
        The email or password is wrong!
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "noResult") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no user with this email address .
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "itemErr") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is a product with the same name .
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "noResultOrder") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no order with this ID !!!
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "noResultItem") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no product with this name !!!
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "noResultAdmin") {
        echo "   <div class='alert alert-danger' role='alert'>
        There is no admin with this email !!!
      </div>";
        unset($_SESSION['message']);
    } elseif ($_SESSION['message'] == "empty_err") {
        echo "   <div class='alert alert-danger' role='alert'>
    please don't leave anything empty !!!
  </div>";
        unset($_SESSION['message']);
    }
}
// messages function (end)
// login function (start)
function login()
{
    if (isset($_POST['login'])) {

        $adminEmail = trim($_POST['adminEmail']);
        $password = trim(strtolower($_POST['adminPassword']));
        $query = "SELECT  admin_email , admin_id , admin_password FROM admin_info WHERE admin_email= '$adminEmail' ";
        $data = query($query);
        if ($data == 0) {
            $_SESSION['message'] = "loginErr";
            post_redirect("login.php");
        } elseif ($password == $data[0]['admin_password'] and  $adminEmail == $data[0]['admin_email']) {
            $_SESSION['admin_id'] = $data[0]['admin_id'];
            post_redirect("index.php");
        } else {
            $_SESSION['message'] = "loginErr1";
            post_redirect("login.php");
        }
    }
}
// login function (end)
// user functions (start)
function all_users()
{
    $query = "SELECT user_id ,first_name ,last_name ,email, mobile ,address1, address2 FROM user_info";
    $data = query($query);
    return $data;
}
function delete_user()
{
    if (isset($_GET['delete'])) {
        $userId = $_GET['delete'];
        $query = "DELETE FROM user_info WHERE user_id ='$userId'";
        $run = single_query($query);
        get_redirect("customers.php");
    }
}
function edit_user($id)
{
    if (isset($_POST['update'])) {
        $fname = trim($_POST['first_name']);
        $lname = trim($_POST['last_name']);
        $email = trim(strtolower($_POST['email']));
        $mobile = trim($_POST['mobile']);
        $address1 = trim($_POST['address1']);
        $address2 = trim($_POST['address2']);

        if (empty($email) or empty($address1) or empty($fname) or empty($lname)) {
            $_SESSION['message'] = "empty_err";
            get_redirect("customers.php");
            return;
        }
        $check = check_email_user($email);
        if ($check == 0) {
            $query = "UPDATE user_info SET  first_name='$fname' ,last_name='$lname',email='$email', mobile='$mobile',address1='$address1', address2='$address2' WHERE user_id= '$id'";
            single_query($query);
            get_redirect("customers.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("customers.php");
        }
    } elseif (isset($_POST['cancel'])) {
        get_redirect("customers.php");
    }
}
function get_user($id)
{
    $query = "SELECT user_id ,first_name ,last_name ,email ,mobile ,address1 ,address2 FROM user_info WHERE user_id=$id";
    $data = query($query);
    return $data;
}
function check_email_user($email)
{
    $query = "SELECT email FROM user_info WHERE email='$email'";
    $data = query($query);
    if ($data) {
        return 1;
    } else {
        return 0;
    }
}
function search_user()
{
    if (isset($_GET['search_user'])) {
        $email = trim(strtolower($_GET['search_user_email']));
        if (empty($email)) {
            return;
        }
        $query = "SELECT user_id ,first_name ,last_name ,email ,mobile ,address1, address2 FROM user_info WHERE email='$email'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResult";
            return;
        }
    }
}
function get_user_details()
{
    if ($_GET['id']) {
        $id = $_GET['id'];
        $query = "SELECT * FROM user_info WHERE user_id=$id";
        $data = query($query);
        return $data;
    }
}
// user functions (end)
// item functions (start)
function all_items()
{
    $query = "SELECT * FROM products";
    $data = query($query);
    return $data;
}
function delete_item()
{
    if (isset($_GET['delete'])) {
        $itemID = $_GET['delete'];
        $query = "DELETE FROM products WHERE product_id ='$itemID'";
        $run = single_query($query);
        get_redirect("products.php");
    }
}
function edit_item($id)
{
    if(isset($_POST['btn_save']))
{
$product_name=$_POST['product_name'];
$details=$_POST['details'];
$price=$_POST['price'];
$product_type=$_POST['product_type'];
$brand=$_POST['brand'];
$tags=$_POST['tags'];

//picture coding
$picture_name=$_FILES['picture']['name'];
$picture_type=$_FILES['picture']['type'];
$picture_tmp_name=$_FILES['picture']['tmp_name'];
$picture_size=$_FILES['picture']['size'];

if($picture_type=="image/jpeg" || $picture_type=="image/jpg" || $picture_type=="image/png" || $picture_type=="image/gif")
{
	if($picture_size<=50000000)
	
		$pic_name=time()."_".$picture_name;
		move_uploaded_file($picture_tmp_name,"../product_images/".$pic_name);

mysqli_query($con,"UPDATE products SET product_cat='$product_type', product_brand='$brand',product_title='$product_name',product_price='$price', product_desc='$details', product_image='$pic_name',product_keywords='$tags' WHERE products_id= '$id'") or die ("query incorrect");
}

mysqli_close($con);
} else {
            $_SESSION['message'] = "itemErr";
            get_redirect("products.php");
        }
    } 

function get_item($id)
{
    $query = "SELECT * FROM products WHERE product_id=$id";
    $data = query($query);
    return $data;
}
function check_name($name)
{
    $query = "SELECT product_title FROM products WHERE product_title='$name'";
    $data = query($query);
    if ($data) {
        return 1;
    } else {
        return 0;
    }
}
function search_item()
{
    if (isset($_GET['search_products'])) {
        $name = trim($_GET['search_product_name']);
        $query = "SELECT * FROM products WHERE product_title LIKE '%$name%'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResultItem";
            return;
        }
    }
}
function add_item()
{
    if (isset($_POST['add_products'])) {
        $name = trim($_POST['name']);
        $brand = trim($_POST['brand']);
        $cat = trim($_POST['cat']);
        $tags = trim($_POST['tags']);
        $quantity = trim($_POST['quantity']);
        $price = trim($_POST['price']);
        $details = trim($_POST['details']);
        $picture_name=$_FILES['picture']['name'];

        $pic_name=time()."_".$picture_name;
        move_uploaded_file($picture_name, "../product_images/".$pic_name);

        $check = check_name($name);
        if (
            empty($name) or empty($brand) or empty($cat)  or
            empty($tags) or empty($image) or empty($quantity) or empty($price) or empty($details)
        ) {
            $_SESSION['message'] = "empty_err";
            get_redirect("products.php");
            return;
        }
        if ($check == 0) {
            $query = "INSERT INTO products (product_title, product_brand, product_cat, product_desc  ,
            product_keywords ,product_image ,product_price) VALUES
            ('$name' ,'$brand' ,'$cat' ,'$details' ,'$tags' ,'$image'  ,'$price')";
            $run = single_query($query);
            get_redirect("products.php");
        } else {
            $_SESSION['message'] = "itemErr";
            get_redirect("products.php");
        }
    } elseif (isset($_POST['cancel'])) {
        get_redirect("products.php");
    }
}

function get_item_details()
{
    if ($_GET['id']) {
        $id = $_GET['id'];
        $query = "SELECT * FROM products WHERE product_id=$id";
        $data = query($query);
        return $data;
    }
}
// item functions (end)
// admin functions (start)
function all_admins()
{
    $query = "SELECT admin_id ,admin_name ,admin_email  FROM admin_info";
    $data = query($query);
    return $data;
}
function get_admin($id)
{
    $query = "SELECT admin_id ,admin_name ,admin_email  FROM admin_info WHERE admin_id=$id";
    $data = query($query);
    return $data;
}
function edit_admin($id)
{
    if (isset($_POST['admin_update'])) {
        $fname = trim($_POST['admin_name']);
        $email = trim(strtolower($_POST['admin_email']));
        $password = trim($_POST['admin_password']);
        $check = check_email_admin($email);
        if ($check == 0) {
            $query = "UPDATE admin_info SET admin_email='$email' ,admin_name='$fname' ,admin_password='$password'  WHERE admin_id= '$id'";
            single_query($query);
            get_redirect("admin.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("admin.php");
        }
    } elseif (isset($_POST['admin_cancel'])) {
        get_redirect("admin.php");
    }
}
function check_email_admin($email)
{
    $query = "SELECT admin_email FROM admin_info WHERE admin_email='$email'";
    $data = query($query);
    if ($data) {
        return $data;
    } else {
        return 0;
    }
}
function add_admin()
{
    if (isset($_POST['add_admin'])) {
        $fname = trim($_POST['admin_name']);
        $email = trim(strtolower($_POST['admin_email']));
        $password = trim($_POST['admin_password']);
        $check = check_email_admin($email);
        if ($check == 0) {
            $query = "INSERT INTO admin_info (admin_name, admin_email, admin_password) 
            VALUES ('$fname','$email','$password')";
            single_query($query);
            get_redirect("admin.php");
        } else {
            $_SESSION['message'] = "emailErr";
            get_redirect("admin.php");
        }
    } elseif (isset($_POST['admin_cancel'])) {
        get_redirect("admin.php");
    }
}
function delete_admin()
{
    if (isset($_GET['delete'])) {
        $adminId = $_GET['delete'];
        $query = "DELETE FROM admin_info WHERE admin_id ='$adminId'";
        $run = single_query($query);
        get_redirect("admin.php");
    }
}
function search_admin()
{
    if (isset($_GET['search_admin'])) {
        $email = trim(strtolower($_GET['search_admin_email']));
        if (empty($email)) {
            return;
        }
        $query = "SELECT admin_id ,admin_name ,admin_email FROM admin_info WHERE admin_email='$email'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResultAdmin";
            return;
        }
    }
}
function check_admin($id)
{
    $query = "SELECT admin_id FROM admin_info where admin_id='$id'";
    $row = query($query);
    if (empty($row)) {
        return 0;
    } else {
        return 1;
    }
}
// admin functions (end)
// order functions (start)
function all_orders()
{
    $query = "SELECT * FROM orders";
    $data = query($query);
    return $data;
}
function search_order()
{
    if (isset($_GET['search_order'])) {
        $id = trim($_GET['search_order_id']);
        if (empty($id)) {
            return;
        }
        $query = "SELECT * FROM orders WHERE order_id='$id'";
        $data = query($query);
        if ($data) {
            return $data;
        } else {
            $_SESSION['message'] = "noResultOrder";
            return;
        }
    }
}
function delete_order()
{
    if (isset($_GET['delete'])) {
        $order_id = $_GET['delete'];
        $query = "DELETE FROM orders WHERE order_id ='$order_id'";
        $run = single_query($query);
        get_redirect("orders.php");
    } elseif (isset($_GET['done'])) {
        $order_id = $_GET['done'];
        $query = "UPDATE orders SET p_status = 1 WHERE order_id='$order_id'";
        single_query($query);
        get_redirect("orders.php");
    } elseif (isset($_GET['undo'])) {
        $order_id = $_GET['undo'];
        $query = "UPDATE orders SET p_status = 0 WHERE order_id='$order_id'";
        single_query($query);
        get_redirect("orders.php");
    }
}
// order functions (end)
