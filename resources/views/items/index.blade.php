<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel AJAX CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .action-icons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .edit-icon, .delete-icon {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-primary">PHP - Simple To Do List App</h2>
    <hr>
    <div class="row py-2 mb-3 justify-content-md-center">
        <div class="col col-md-4">
            <input type="text" id="name" class="form-control" placeholder="Enter Item Name">
        </div>
        <div class="col-md-2">
            <button id="addItem" class="btn btn-primary">Add Item</button>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Item</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="itemTable">
            <!-- Items will be loaded here -->
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <button id="showAll" class="btn btn-success mr-2">All Data</button>
        <button id="showSelected" class="btn btn-info">Selected Data</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        fetchItems();

        function fetchItems() {
            $.get("{{ route('items.fetch') }}", function(items) {
                $('#itemTable').html('');
                $.each(items, function(index, item) {
                    var status = item.status == 0 ? '<input type="checkbox" class="status-checkbox" data-id="'+item.id+'">' : 'Done';
                    $('#itemTable').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td class="editable" data-id="${item.id}">${item.name}</td>
                            <td>${status}</td>
                            <td>
                                <div class="action-icons">
                                    <span class="edit-icon text-primary" data-id="${item.id}">&#9998;</span>
                                    <span class="delete-icon text-danger" data-id="${item.id}">&#10060;</span>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            });
        }

        $('#addItem').click(function() {
            var name = $('#name').val();
            $.ajax({
                url: "{{ route('items.store') }}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: name
                },
                success: function(item) {
                    fetchItems();
                    $('#name').val('');
                    $('#error-message').remove();
                },
                error: function(response) {
                    if(response.status === 422) {
                        var errors = response.responseJSON.errors;
                        var errorMessage = '';
                        if(errors.name) {
                            errorMessage = errors.name[0];
                        }
                        $('#name').after('<div id="error-message" class="text-danger mt-2">' + errorMessage + '</div>');
                    }
                }
            });
        });

        $(document).on('change', '.status-checkbox', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '/items/' + id + '/status',
                method: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    fetchItems();
                }
            });
        });

        $(document).on('click', '.delete-icon', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '/items/' + id,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    fetchItems();
                }
            });
        });

        $(document).on('click', '.edit-icon', function() {
            var id = $(this).data('id');
            var currentName = $(this).closest('tr').find('.editable').text();
            var newName = prompt("Edit Item Name:", currentName);
            if (newName && newName !== currentName) {
                $.ajax({
                    url: '/items/' + id,
                    method: 'PATCH',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: newName
                    },
                    success: function(response) {
                        fetchItems();
                    }
                });
            }
        });

        $('#showAll').click(function() {
            fetchItems();
        });

        $('#showSelected').click(function() {
            $.get("{{ route('items.selected') }}", function(items) {
                $('#itemTable').html('');
                $.each(items, function(index, item) {
                    var status = item.status == 0 ? '<input type="checkbox" class="status-checkbox" data-id="'+item.id+'">' : 'Done';
                    $('#itemTable').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td class="editable" data-id="${item.id}">${item.name}</td>
                            <td>${status}</td>
                            <td>
                                <div class="action-icons">
                                    <span class="edit-icon text-primary" data-id="${item.id}">&#9998;</span> 
                                    <span class="delete-icon text-danger" data-id="${item.id}">&#10060;</span>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            });
        });
    });
</script>
</body>
</html>
