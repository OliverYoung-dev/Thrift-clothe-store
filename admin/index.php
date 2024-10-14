<?php
session_start();
include("../db.php");

// Redirect to login page if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

error_reporting(0);
if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
    $user_id = $_GET['user_id'];
    mysqli_query($con, "DELETE FROM user_info WHERE user_id='$user_id'") or die("Delete query is incorrect...");
}
if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
  if (isset($_GET['cat_id'])) {
      $cat_id = $_GET['cat_id'];
      mysqli_query($con, "DELETE FROM categories WHERE cat_id='$cat_id'") or die("Delete category query is incorrect...");
  } elseif (isset($_GET['brand_id'])) {
      $brand_id = $_GET['brand_id'];
      mysqli_query($con, "DELETE FROM brands WHERE brand_id='$brand_id'") or die("Delete brand query is incorrect...");
  }
}

// Pagination
$page = $_GET['page'];
$page1 = ($page == "" || $page == "1") ? 0 : ($page * 10) - 10;

include "sidenav.php";
include "topheader.php";
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
         <div class="panel-body">
            <?php  //success message
            if(isset($_POST['success'])) {
            $success = $_POST["success"];
            echo "<h1 style='color:#0C0'>Your Product was added successfully &nbsp;&nbsp;  <span class='glyphicon glyphicon-ok'></h1></span>";
            }
            ?>
                </div>
              <div class="col-md-14">
            <div class="card ">
                <div class="card-header card-header-primary">
                    <h4 class="card-title"> Users List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive ps">
                        <table class="table table-hover tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>FirstName</th>
                                    <th>LastName</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Action</th> <!-- Added Action Column -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $result = mysqli_query($con, "SELECT * FROM user_info") or die ("query 1 incorrect.....");

                                while (list($user_id, $first_name, $last_name, $email, $password, $phone, $address1, $address2) = mysqli_fetch_array($result)) {	
                                    echo "<tr>
                                        <td>$user_id</td>
                                        <td>$first_name</td>
                                        <td>$last_name</td>
                                        <td>$email</td>
                                        <td>*****</td>
                                        <td>$phone</td>
                                        <td>$address1</td>
                                        <td>$address2</td>
                                        <td>
                                            <a href='?user_id=$user_id&action=delete' onclick='return confirm(\"Are you sure you want to delete this user?\");'>
                                                <img src='' alt='Delete' style='width:20px; height:20px; cursor:pointer;' title='Delete User'>
                                            </a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                    </tbody>
                  </table>
                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
          </div>
           <div class="row">
           <div class="col-md-6">
    <div class="card ">
        <div class="card-header card-header-primary">
            <h4 class="card-title">Categories List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive ps">
                <table class="table table-hover tablesorter">
                    <thead class="text-primary">
                        <tr>
                            <th>ID</th>
                            <th>Categories</th>
                            <th>Count</th>
                            <th>Action</th> <!-- Added Action Column -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
    $result = mysqli_query($con, "SELECT * FROM categories") or die ("query 1 incorrect.....");
    $i = 1;
    while (list($cat_id, $cat_title) = mysqli_fetch_array($result)) {	
        $sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_cat=$i";
        $query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);
        $count = $row["count_items"];
        $i++;

        echo "<tr>
            <td>$cat_id</td>
            <td>$cat_title</td>
            <td>$count</td>
            <td>
                 
                <a href='?cat_id=$cat_id&action=delete' onclick='return confirm(\"Are you sure you want to delete this category?\");' title=\"Delete Category\">Delete</a>
            </td>
        </tr>";
    }
?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card ">
        <div class="card-header card-header-primary">
            <h4 class="card-title">Brands List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive ps">
                <table class="table table-hover tablesorter">
                    <thead class="text-primary">
                        <tr>
                            <th>ID</th>
                            <th>Brands</th>
                            <th>Count</th>
                            <th>Action</th> <!-- Added Action Column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $result = mysqli_query($con, "SELECT * FROM brands") or die ("query 1 incorrect.....");
                        $i = 1;
                        while (list($brand_id, $brand_title) = mysqli_fetch_array($result)) {	
                            $sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_brand=$i";
                            $query = mysqli_query($con, $sql);
                            $row = mysqli_fetch_array($query);
                            $count = $row["count_items"];
                            $i++;

                            echo "<tr>
                                <td>$brand_id</td>
                                <td>$brand_title</td>
                                <td>$count</td>
                                <td>
                                  
                                    <a href='?brand_id=$brand_id&action=delete' onclick='return confirm(\"Are you sure you want to delete this brand?\");' title='Delete Brand'>Delete</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Categories -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="update_category.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_cat_id" name="cat_id">
                    <div class="form-group">
                        <label for="category-title" class="col-form-label">Category Title:</label>
                        <input type="text" class="form-control" id="edit_cat_title" name="cat_title" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Editing Brands -->
<div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="update_brand.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBrandLabel">Edit Brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_brand_id" name="brand_id">
                    <div class="form-group">
                        <label for="brand-title" class="col-form-label">Brand Title:</label>
                        <input type="text" class="form-control" id="edit_brand_title" name="brand_title" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>


           </div>
           <div class="col-md-5">
            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">Subscribers</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive ps">
                  <table class="table table-hover tablesorter " id="">
                    <thead class=" text-primary">
                        <tr><th>ID</th><th>email</th>
                    </tr></thead>
                    <tbody>
                      <?php 
                        $result=mysqli_query($con,"select * from email_info")or die ("query 1 incorrect.....");

                        while(list($brand_id,$brand_title)=mysqli_fetch_array($result))
                        {	
                        echo "<tr><td>$brand_id</td><td>$brand_title</td>

                        </tr>";
                        }
                        ?>
                    </tbody>
                  </table>
                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>
        <script>
    function editCategory(cat_id, cat_title) {
        // Set the category data in the modal
        document.getElementById('edit_cat_id').value = cat_id;
        document.getElementById('edit_cat_title').value = cat_title;

        // Show the modal
        $('#editCategoryModal').modal('show');
    }

    function editBrand(brand_id, brand_title) {
        // Set the brand data in the modal
        document.getElementById('edit_brand_id').value = brand_id;
        document.getElementById('edit_brand_title').value = brand_title;

        // Show the modal
        $('#editBrandModal').modal('show');
    }
</script>

      </script>
      <?php
include "footer.php";
?>
