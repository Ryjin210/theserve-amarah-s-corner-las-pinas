<?php
session_start();
if (!isset($_SESSION['adminloggedin']) && $_SESSION['adminloggedin'] == false) {
    header("Location: ./login");
}
require_once '../includes/database_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">


    <!-- datatable lib -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="../assets/css/admin.css">

    <style>
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody::-webkit-scrollbar {
            width: 0px;
        }

        .dataTables_wrapper .dataTables_info {
            color: #936500 !important;
        }

        .dataTables_filter {
            margin-bottom: 10px;
        }

        .dataTables_filter label {
            color: #ffaf08;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ffaf08;
            color: #ffaf08;
        }

        table.dataTable thead {
            border-radius: 5px !important;
        }

        table.dataTable thead tr {
            background-color: #ffaf08;
            color: #070506;
            white-space: nowrap;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 5px 10px;
            background-color: #ffaf08 !important;
            color: #070506 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #070506 !important;
            border-color: #ffaf08;
            color: #ffaf08 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            background-color: #936500 !important;
            color: #070506 !important;
        }

        .dataTables_wrapper .dataTables_length select {
            color: #ffaf08 !important;
            border-color: #936500;
            background: #070506 !important;
        }

        table thead tr th:first-child {
            border-top-left-radius: 5px !important;
        }

        table thead tr th:last-child {
            border-top-right-radius: 5px !important;
        }
    </style>
    <title>Admin Panel</title>
</head>

<body>
    <!-- TOAST -->
    <div class="toast" id="toast">
        <div class="toast-content" id="toast-content">
            <i id="toast-icon" class="fa-solid fa-triangle-exclamation warning"></i>

            <div class="message">
                <span class="text text-1" id="text-1"></span>
                <span class="text text-2" id="text-2"></span>
            </div>
        </div>
        <i class="fa-solid fa-xmark close"></i>
        <div class="progress"></div>
    </div>

    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'empty field') {
        echo '<script>
        window.addEventListener("load", function () {
            document.querySelector(".toast").classList.toggle("active");
            document.querySelector(".text-1").textContent = "Error";
            document.querySelector(".text-2").textContent = "All fields are required!";
            document.querySelector(".progress").classList.toggle("active");
            document.querySelector(".close").addEventListener("click", () => {
                document.querySelector(".toast").classList.remove("active");
            });
        })
    </script>';
        unset($_SESSION['status']);
    }
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'empty category title') {
        echo '<div class="alert">
        <span class="fa-solid fa-circle-exclamation"></span>
        <span class="msg">Input category title!</span>
        <span class="close-btn" id="close-alert">
            <span class="fas fa-times"></span>
        </span>
    </div>';
        unset($_SESSION['status']);
    }
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'image does not exist') {
        echo '<div class="alert">
        <span class="fa-solid fa-circle-exclamation"></span>
        <span class="msg">Upload category thumbnail!</span>
        <span class="close-btn" id="close-alert">
            <span class="fas fa-times"></span>
        </span>
    </div>';
        unset($_SESSION['status']);
    }
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'invalid img ext') {
        echo '<div class="alert">
        <span class="fa-solid fa-circle-exclamation"></span>
        <span class="msg">File not supported!</span>
        <span class="close-btn" id="close-alert">
            <span class="fas fa-times"></span>
        </span>
    </div>';
        unset($_SESSION['status']);
    }
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'too large') {
        echo '<div class="alert">
        <span class="fa-solid fa-circle-exclamation"></span>
        <span class="msg">Image size is too large!</span>
        <span class="close-btn" id="close-alert">
            <span class="fas fa-times"></span>
        </span>
    </div>';
        unset($_SESSION['status']);
    }
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'Successfully added!') {
        echo '<div class="alert">
        <span class="fa-solid fa-circle-exclamation"></span>
        <span class="msg">Successfully added!</span>
        <span class="close-btn" id="close-alert">
            <span class="fas fa-times"></span>
        </span>
    </div>';
        unset($_SESSION['status']);
    }
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'Something went wrong!') {
        echo '<div class="alert">
        <span class="fa-solid fa-circle-exclamation"></span>
        <span class="msg">Something went wrong!</span>
        <span class="close-btn" id="close-alert">
            <span class="fas fa-times"></span>
        </span>
    </div>';
        unset($_SESSION['status']);
    }
    ?>

    <!-- VIEW -->
    <div id="popup-outer" class="popup-outer view-modal">
        <div id="popup-box" class="popup-box">
            <div id="popup-box" class="popup-box">
                <div class="top">
                    <h3>Edit Category</h3>
                    <div id="modalClose" class="fa-solid fa-xmark"></div>
                </div>
                <hr>
                <form enctype="multipart/form-data">
                    <h5>Category: <span style="color: #ffaf08; padding-left: 5px;" id="category_title_view"></span></h5>
                    <h5>Category Thumbnail: <br> <img id="category_thumbnail_view" style="width: 150px; margin-top: 5px;" src=""></h5>
                </form>
                <hr>
                <div class="bottom">
                    <div class="buttons">
                        <button id="modalClose" type="button" class="cancel">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- UPDATE -->
    <div id="popup-outer" class="popup-outer edit-modal">
        <div id="popup-box" class="popup-box">
            <div class="top">
                <h3>Edit Category</h3>
                <div id="modalClose" class="fa-solid fa-xmark"></div>
            </div>
            <hr>
            <form enctype="multipart/form-data" id="edit-category">
                <div style="display: none;" class="form-group">
                    <span>Category ID</span>
                    <input type="text" id="update_category_id" name="update_category_id" value="">
                </div>
                <div class="form-group">
                    <span>Category Title</span>
                    <input type="text" id="update_category_title" name="update_category_title" value="">
                </div>
                <div class="form-group">
                    <span>Category Image Name:</span>
                    <input style="background-color: #3b3b3b; color: #949494;" type="text" class="file" name="category_thumbnailDB" id="category_thumbnailDB" readonly>
                </div>
                <div class="form-group">
                    <span>Select Category Image</span>
                    <input type="file" accept=".jpg, .jpeg, .png" class="file" name="update_category_thumbnail" id="update_category_thumbnail">
                </div>
                <hr>
                <div class="bottom">
                    <div class="buttons">
                        <button id="modalClose" type="button" class="cancel">CANCEL</button>
                        <button type="submit" id="update_category" name="update_category" class="save">SAVE CHANGES</button>
                    </div>
            </form>
        </div>
    </div>
    </div>

    <?php include 'top.php'; ?>

    <!-- MAIN -->
    <main>
        <h1 class="title">View Category</h1>
        <ul class="breadcrumbs">
            <li><a href="index">Home</a></li>
            <li class="divider">/</li>
            <li><a href="view-category" class="active">View Category</a></li>
        </ul>
        <section class="view-category">
            <div class="wrapper">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Category Title</th>
                            <th>Category Thumbnail</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </section>


        <script>
            var dataTable = $('#example').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "paging": true,
                "pagingType": "simple",
                "scrollX": true,
                "sScrollXInner": "100%",
                "bLengthChange": false,
                "iDisplayLength": 10,
                "ajax": {
                    url: "get_category",
                    type: "post"
                }
            });
        </script>

        <script>
            // VIEW
            $(document).on('click', '#getView', function(e) {
                e.preventDefault();
                var category_id_view = $(this).data('id');
                $.ajax({
                    url: 'processing',
                    type: 'POST',
                    data: 'category_id_view=' + category_id_view,
                    success: function(res) {
                        var obj = JSON.parse(res);
                        $(".view-modal").addClass("active");
                        $("#category_title_view").text(obj.category_title);
                        $("#category_thumbnail_view").attr("src", "../assets/images/" + obj.category_thumbnail);
                    }
                })
            });

            $(document).on('click', '#getEdit', function(e) {
                e.preventDefault();
                var category_id_edit = $(this).data('id');
                $.ajax({
                    url: 'processing',
                    type: 'POST',
                    data: 'category_id_edit=' + category_id_edit,
                    success: function(res) {
                        $('#example').DataTable().ajax.reload();
                        var obj = JSON.parse(res);
                        $(".edit-modal").addClass("active");
                        $("#update_category_id").val(obj.category_id);
                        $("#update_category_title").val(obj.category_title);
                        $("#category_thumbnailDB").val(obj.category_thumbnail);
                        $("#update_category_thumbnail").attr("src", "../assets/images/" + obj.category_thumbnail);
                    }
                })
            });

            $(document).on('click', '#modalClose', function() {
                $('.edit-modal').removeClass("active");
                $('.view-modal').removeClass("active");
                $("#edit-category")[0].reset();
            })
        </script>

        <script>
            $(document).ready(function() {
                $("#edit-category").on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "update-category",
                        data: new FormData(this),
                        dataType: 'text',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(response) {
                            if (response === 'category is empty') {
                                $('#toast').addClass('active');
                                $('.progress').addClass('active');
                                // $('#toast-icon').removeClass('fa-solid fa-triangle-exclamation').addClass('fa-solid fa-circle-exclamation');
                                $('.text-1').text('Error!');
                                $('.text-2').text('Category title field is empty!');
                                setTimeout(() => {
                                    $('#toast').removeClass("active");
                                    $('.progress').removeClass("active");
                                }, 5000);
                            }

                            if (response === 'category title already exist') {
                                $('#toast').addClass('active');
                                $('.progress').addClass('active');
                                $('.text-1').text('Error!');
                                $('.text-2').text('Category title already exist!');
                                setTimeout(() => {
                                    $('#toast').removeClass("active");
                                    $('.progress').removeClass("active");
                                }, 5000);
                            }

                            if (response === 'title updated') {
                                $('.edit-modal').removeClass("active");
                                $('#toast').addClass('active');
                                $('.progress').addClass('active');
                                $('#toast-icon').removeClass('fa-solid fa-triangle-exclamation').addClass('fa-solid fa-circle-exclamation');
                                $('.text-1').text('Success!');
                                $('.text-2').text('Category title updated successfully!');
                                setTimeout(() => {
                                    $('#toast').removeClass("active");
                                    $('.progress').removeClass("active");
                                }, 5000);
                                $('#example').DataTable().ajax.reload();
                            }

                            if (response === 'invalid file') {
                                $('#toast').addClass('active');
                                $('.progress').addClass('active');
                                $('.text-1').text('Error!');
                                $('.text-2').text('File not supported!');
                                setTimeout(() => {
                                    $('#toast').removeClass("active");
                                    $('.progress').removeClass("active");
                                }, 5000);
                                $('#example').DataTable().ajax.reload();
                            }
                            if (response === 'invalid file') {
                                $('#toast').addClass('active');
                                $('.progress').addClass('active');
                                $('.text-1').text('Error!');
                                $('.text-2').text('File is too large!');
                                setTimeout(() => {
                                    $('#toast').removeClass("active");
                                    $('.progress').removeClass("active");
                                }, 5000);
                                $('#example').DataTable().ajax.reload();
                            }

                            if (response === 'updated successfully') {
                                $('.edit-modal').removeClass("active");
                                $('#toast').addClass('active');
                                $('.progress').addClass('active');
                                $('#toast-icon').removeClass('fa-solid fa-triangle-exclamation').addClass('fa-solid fa-circle-exclamation');
                                $('.text-1').text('Success!');
                                $('.text-2').text('Category title and thumbnail updated successfully!');
                                setTimeout(() => {
                                    $('#toast').removeClass("active");
                                    $('.progress').removeClass("active");
                                }, 5000);
                                $('#example').DataTable().ajax.reload();
                            }
                        }
                    })
                })
            });
        </script>


        <?php include 'bottom.php' ?>

</body>

</html>