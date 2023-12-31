<?php global $mysqli, $rows;
$var = "add_new_user";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['add_user'])) {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $grade = $_POST['grade'];
    $subject = $_POST['subject'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $sql = "insert into users_info (last_name,first_name,grade,subject,username,password,user_type) VALUES ('$lastname','$firstname','$grade','$subject','$username','$password','$user_type')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("saved successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $grade = $_POST['grade'];
    $subject = $_POST['subject'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $sql = "update users_info set last_name='$lastname',first_name='$firstname',grade='$grade',subject='$subject',username='$username',password='$password',user_type='$user_type' where id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("updated successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

?>

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page">

        <style>
            .table-1 tbody tr th, .table-1 tbody tr td {
                border-bottom: 0 !important;
                border-top: 0 !important;
            }

            .table-1 thead tr th, .table-1 thead tr td {
                border-top: 0 !important;
                border-bottom: 3px solid #ddd;
            }

            tr:nth-child(even) {
                background-color: #fbe4d5;
            }

            .table-1 thead {
                background-color: #ed7d31;
                color: white;
            }
        </style>


        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px pad-1em">


                <div class="custom-grid-container" tabindex="2">
                    <div class="custom-grid-item pad-1em">
                        <div class="f-weight-bold d-flex" style="    border: 1px solid gray;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;">
                            <h3 class="m-t-13px m-l-18px">
                                List of Users
                            </h3>

                            <div class="w-74p d-flex-end">
                                <input placeholder="search name" id="search_name" type="text" class="m-1em"
                                       onchange="searchName()"/>
                            </div>

                        </div>

                        <?php
                        $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
                        $sql = "select * from users_info WHERE CONCAT_WS('', first_name,last_name) LIKE '%$searchName%' Limit 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = isset( $row['id']) ? $row['id'] + 1 : 0;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("select * from users_info WHERE CONCAT_WS('', first_name,last_name) LIKE '%$searchName%'")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("select * from users_info WHERE CONCAT_WS('', first_name,last_name) LIKE '%$searchName%' LIMIT ?,?")) {
                            // Calculate the page to get the results we need from our table.
                            $calc_page = ($page - 1) * $num_results_on_page;
                            $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                            $stmt->execute();
                            // Get the results...
                            $result = $stmt->get_result();
                            ?>

                            <table class="table table-1 b-shadow-dark">
                                <thead>
                                <tr>
                                    <th class="t-align-center"><label for="student-list-cb"
                                                                      class="d-flex-center"></label><input
                                                id="student-list-cb" type="checkbox"
                                                onclick="checkCBStudents('student-list', 'student-list-cb')"
                                                class="sc-1-3 c-hand"/></th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="student-list">
                                <?php
                                $i = 0;
                                while ($row = $result->fetch_assoc()):
                                    $i++;
                                    ?>
                                    <tr>
                                        <td class="d-flex-center"><label>
                                                <input type="checkbox" class="sc-1-3 c-hand check"
                                                       id="<?= $row['user_lrn'] ?>"/>
                                            </label></td>
                                        <th scope="row"><?= $i ?> </th>
                                        <td><?= $row['last_name'] ?> <?= $row['first_name'] ?></td>
                                        <td><?= $row['username'] ?></td>
                                        <td><?= $row['user_type'] ?></td>
                                        <td>
                                            <label for="" class="t-color-red c-hand f-weight-bold"
                                                   onclick="editUser('<?= $row['id'] ?>','<?= $row['last_name'] ?>','<?= $row['first_name'] ?>', '<?= $row['grade'] ?>','<?= $row['subject'] ?>','<?= $row['username'] ?>','<?= $row['password'] ?>','<?= $row['user_type'] ?>', )">
                                                Edit</label>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>

                            <?php
                            $stmt->close();
                        }
                        ?>
                        Total Records: <?= $total_pages ?>
                        <div class="m-2em d-flex-end m-t-n1em">
                            <div class="d-flex-center">
                                <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="prev"><a
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page > 3): ?>
                                            <li class="start"><a
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                            </li>
                                            <li class="dots">...</li>
                                        <?php endif; ?>

                                        <?php if ($page - 2 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page - 1 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                            </li><?php endif; ?>

                                        <li class="currentpage"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                        </li>

                                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                            </li><?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                            <li class="dots">...</li>
                                            <li class="end"><a
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                            <li class="next"><a
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="custom-grid-item pad-1em">
                        <div class="b-shadow-dark">


                            <div class="pad-1em  f-weight-bold d-flex">
                                <h3>
                                    Add User
                                </h3>
                            </div>

                            <form method="post">
                                <div class="custom-grid-container" tabindex="1">
                                    <div class="custom-grid-item ">
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Last Name:</div>
                                        <input placeholder="Last Name" type="text"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="lastname"
                                               name="lastname"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> First Name:</div>
                                        <input placeholder="First Name" type="text"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="firstname"
                                               name="firstname"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Grade:</div>
                                        <select name="grade" id="grade"
                                                class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px">
                                            <option value="" disabled selected>Grade</option>
                                            <option value="1">Grade 1</option>
                                            <option value="2">Grade 2</option>
                                            <option value="3">Grade 3</option>
                                            <option value="4">Grade 4</option>
                                            <option value="5">Grade 5</option>
                                            <option value="6">Grade 6</option>
                                        </select>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Subject:</div>
                                        <input placeholder="Subject" type="text"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="subject"
                                               name="subject"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Username:</div>
                                        <input placeholder="Username" type="text"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="username"
                                               name="username"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Password:</div>
                                        <input placeholder="Password" type="password"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="password"
                                               name="password"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> User Type:</div>
                                        <select name="user_type" id="user_type"
                                                class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px">
                                            <option value="" disabled selected> </option>
                                            <option value="Admin">Admin</option>
                                            <option value="Teacher">Teacher</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex-end pad-1em">
                                    <label class="btn bg-hover-gray-dark-v2 m-b-0 t-color-white" onclick="cancel()">
                                        Cancel
                                    </label> &nbsp; &nbsp;
                                    <button type="submit"
                                            class="c-hand btn-success btn"
                                            name="add_user">Add
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="myModal">
                    <script src="../../assets/js/js_header.js"></script>

                    <div class="modal-content">
                        <div id="top-icon"
                             class="top-icon h-100p d-flex-center p-absolute w-3em c-hand f-size-26px w-2em bg-hover-white t-color-white"
                             onclick="tops()" style="left: -97px;top: -97px;height: 61px;">☰
                        </div>
                        <div class="modal-header a-center">
                        </div>
                        <div class="modal-body">
                            <div id="update-user" class="modal-child d-none">
                                <form method="post">
                                    <div class="custom-grid-container" tabindex="1">
                                        <div class="custom-grid-item ">
                                            <input placeholder="id" type="hidden"
                                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="id"
                                                   name="id"
                                                   readonly="true">
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Last Name:</div>
                                            <input placeholder="Last Name" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="lastname"
                                                   name="lastname"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> First Name:</div>
                                            <input placeholder="First Name" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="firstname"
                                                   name="firstname"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Grade:</div>
                                            <select name="grade" id="grade"
                                                    class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px">
                                                <option value="" disabled selected>Grade</option>
                                                <option value="1">Grade 1</option>
                                                <option value="2">Grade 2</option>
                                                <option value="3">Grade 3</option>
                                                <option value="4">Grade 4</option>
                                                <option value="5">Grade 5</option>
                                                <option value="6">Grade 6</option>
                                                <option value="7">Grade 7</option>
                                                <option value="8">Grade 8</option>
                                                <option value="9">Grade 9</option>
                                                <option value="10">Grade 10</option>
                                                <option value="11">Grade 11</option>
                                                <option value="12">Grade 12</option>
                                            </select>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Subject:</div>
                                            <input placeholder="Subject" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="subject"
                                                   name="subject"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Username:</div>
                                            <input placeholder="Username" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="username"
                                                   name="username"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Password:</div>
                                            <input placeholder="Password" type="password"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="password"
                                                   name="password"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> User Type:</div>
                                            <input placeholder="User Type" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="user_type"
                                                   name="user_type"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="d-flex-end pad-1em">
                                        <label class="btn bg-hover-gray-dark-v2 m-b-0 t-color-white"
                                               onclick="closeModal()">
                                            Close
                                        </label> &nbsp; &nbsp;
                                        <button type="submit"
                                                class="c-hand btn-success btn"
                                                name="update_user">Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<script>
    function cancel() {
        $('#lastname').val('');
        $('#firstname').val('');
        $('#grade').val('');
        $('#subject').val('');
        $('#username').val('');
        $('#password').val('');
        $('#user_type').val('');
    }

    function editUser(id, lastname, firstname, grade, subject, username, password, user_type) {
        $('#update-user #id').val(id);
        $('#update-user #lastname').val(lastname);
        $('#update-user #firstname').val(firstname);
        $('#update-user #grade').val(grade);
        $('#update-user #subject').val(subject);
        $('#update-user #username').val(username);
        $('#update-user #password').val(password);
        $('#update-user #user_type').val(user_type);

        showModal('update-user', 'Manage Account', '', 'small')
    }

    function searchName() {
        var search = $('#search_name').val();
        if (search !== '') {
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&searchName=' + search;
        } else {
            window.location.href = '?id=<?php echo $_GET['id'] ?>';
        }
    }

    function loadPage() {
        var searchName = '<?php echo isset($_GET['searchName']) ? $_GET['searchName'] : '' ?>';
        if (searchName !== '') {
            $('#search_name').val(searchName);
        }
    }

    loadPage();
</script>

