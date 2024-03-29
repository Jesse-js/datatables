<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employees - Datatables</title>
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"  rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

</head>

<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Employees</h2>
                </div>
                <div class="pull-right mb-2">
                    <a href="javascript:void(0)" onclick="add()" class="btn btn-success">Create Employee</a>
                </div>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="card-body">
        <table class="table table-bordered" id="employee-table">
            <thead>
                <th>Id</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>Address</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="employee-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EmployeeModal">Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="EmployeeForm" name="EmployeeForm" class="form-horizontal"
                        method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                        {{-- @csrf --}}
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Name" maxlength="200" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-12">
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter Email" maxlength="150" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="adress" class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="adress" name="adress"
                                        placeholder="Enter Address" maxlength="200" required>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10 mt-2">
                                <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save
                                    changes</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('#_token').val()
                }
            });
            $('#employee-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employees.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'adress', name: 'adress' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [
                    [0, 'desc']
                ]
            });
        });
        function add() {
            $('#EmployeeForm').trigger("reset");
            $('#EmployeeModal').html("Add Employee");
            $('#employee-modal').modal('show');
            $('#id').val('');
        }

        function editFunction(id) {
            $.ajax({
                type: 'GET',
                url: "{{ route('employees.edit') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#EmployeeModal').html("Edit Employee");
                    $('#employee-modal').modal('show');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#adress').val(data.adress);
                }
            });
        }

        function deleteFunction(id) {
            if (confirm("Are You sure want to delete?")) {
                var id = id;
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('employees.destroy') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        var table = $('#employee-table').DataTable();
                        table.ajax.reload();
                    }
                })
            }
        }

        $('#EmployeeForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('employees.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    // console.log(data);
                    $('#employee-modal').modal('hide');
                    var table = $('#employee-table').DataTable();
                    table.ajax.reload();
                    //table.fnDraw(false);
                    $('#btn-save').html('Submit');
                    $('#btn-save').prop('disabled', false);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
    </script>
</body>

</html>
