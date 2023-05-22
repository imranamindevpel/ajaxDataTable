@extends('layouts.master')
@section('content')
<style>
td {
    padding: 2px 10px;
}

.sorting {
    text-transform: uppercase;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$pageName}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{$pageName}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="modal fade" id="view-create-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form enctype="multipart/form-data" method="post" id="form1">
                        <input type="hidden" id="id" value="0">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" placeholder="Enter Name" required>
                            <div class="invalid-feedback">Please enter a valid name.</div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="price" placeholder="Enter Price" required>
                            <div class="invalid-feedback">Please enter a valid price.</div>
                          </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="quantity" placeholder="Enter Quantity" required>
                            <div class="invalid-feedback">Please enter a valid quantity.</div>
                          </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="detail" placeholder="Enter Detail" required>
                            <div class="invalid-feedback">Please enter a valid detail.</div>
                          </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="save(event)">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-success" data-toggle="modal" data-target="#view-create-edit"
                                onclick=create()>Create</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table id="table1" class="display">
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    list();
});

function list() {
    $.ajax({
        url: "/get_products_data",
        type: "get",
        dataType: "json",
        success: function(response) {
            var columns = [];
            for (var key in response.data[0]) {
                var header = key.replace(/_/g, ' '); // Replace underscores with spaces
                columns.push({
                    "title": header,
                    "data": key
                });
            }
            $('#table1').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/get_products_data",
                    "type": "get"
                },
                "columns": columns
            });
        }
    });
}

function save(event) {
  var form = document.getElementById('form1');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    event.preventDefault();
    var id = $('#id').val();
    var name = $('#name').val();
    var price = $('#price').val();
    var quantity = $('#quantity').val();
    var detail = $('#detail').val();

    // Create a new FormData object to include the image file
    var formData = new FormData($('#form1')[0]);
    formData.append('id', id);
    formData.append('name', name);
    formData.append('price', price);
    formData.append('quantity', quantity);
    formData.append('detail', detail);

    $.ajax({
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        url: '/products',
        success: function(data) {
            if (data.success) {
                alert(data.success);
                $('#view-create-edit').modal('hide');
                var dataTable = $('#table1').DataTable();
                dataTable.ajax.reload();
            }
        }
    });
}


function create(id) {
    $('.modal-footer').show();
    document.getElementById('form1').reset();
}

function view($id) {
  $('.modal-footer').hide();
    var id = $id;
    $.ajax({
        type: 'get',
        data: {
            id: id,
        },
        url: '/products/' + id,
        success: function(data) {
            document.getElementById('name').value = data.name;
            document.getElementById('price').value = data.price;
            document.getElementById('quantity').value = data.quantity;
            document.getElementById('detail').value = data.detail;
        }
    })
}

function edit(id) {
  $('.modal-footer').show();
    $.ajax({
        type: 'get',
        data: {
            id: id,
        },
        url: '/products/' + id,
        success: function(data) {
            document.getElementById('id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('price').value = data.price;
            document.getElementById('quantity').value = data.quantity;
            document.getElementById('detail').value = data.detail;
        }
    })
}

function destroy(id) {
  var confirmed = confirm("Are you sure you want to delete this product?");
  if (confirmed) {
    $.ajax({
        type: 'delete',
        url: "/products/" + id,
        success: function(data) {
            if (data.success) {
                alert("Data Delete Successfully.");
                var dataTable = $('#table1').DataTable();
                dataTable.ajax.reload();
            }
        }
    });
  }
}

</script>
@endsection